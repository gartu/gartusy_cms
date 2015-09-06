<?php

namespace Library\Models;

class CategoryManager extends \Library\Manager {


	/**
	 * permet de récupérer toutes les catégorie
	 * @access public
	 * @return array la liste de toutes les catégories
	 */
	public function getList($language = LANGUAGE){
		$request = $this->dao->prepare('SELECT *
										FROM v_category
										WHERE language = :language');
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Category');

		$categories = $request->fetchAll();
		foreach ($categories as $category) {
			$category->setRights($this->getRights($category->getId()));
		}
		return $categories;
	}


	/**
	 * permet de récupérer la liste des droits
	 * @access public
	 * @return array la liste des droits 
	 */
	public function getRightsList(){
		$request = $this->dao->prepare('SELECT * FROM category');
		$request->execute();

		// on place tous les noms des droits dans un tableau, sans l'id bien entendu
		$rights = $request->fetch();
		$rightsList = array();

		foreach ($rights as $right => $value) {
			if(!is_numeric($right) && $right != 'id')
				$rightsList[$right] = 0;
		}
		return $rightsList;
	}



	/**
	 * permet de récupérer les droits d'une catégorie
	 * @access public
	 * @param string $id de la catégorie
	 * @return array droits correspondants
	 */
	public function getRights($id){
		$request = $this->dao->prepare('SELECT * 
										FROM v_category 
										WHERE id = :id
										LIMIT 1');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->execute();

		// on selectionne tout puis retire l'id au lieu de faire la selection avant, car les droits
		// risquent d'être amené à être modifié, ajouté, supprimé de manière "fréquente".
		$results = $request->fetch();
		foreach ($results as $key => $value) {
			if($key != 'id' && $key != 'name' && $key != 'description' && $key != 'language')
				$rights[$key] = $value;
		}
		return $rights;
	}


	/**
	 * Récupère une catégorie d'après un id
	 * @access public
	 * @param int $id id de la catégorie désirée
	 * @return Category la catégorie correspondante
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, description
										FROM v_category 
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Category');

		$category = $request->fetch();
		// on place les droits correspondants
		$category->setRights($this->getRights($category->getId()));
		return $category;
	}


	/**
	 * Sauvegarde une catégorie dans la base de donnée
	 * @access public
	 * @param Category $category la catégorie à sauver
	 * @return void
	 */
	public function saveCategory(\Library\Entities\Category $category, $language = LANGUAGE) {
		// on ajoute une nouvelle catégorie
		if ($category->isNew()) {

			// on ajoute premièrement les droits correspondants
			$req = 'INSERT INTO category (';
			$endReq = '';
			$rights = $category->getRights();
			$rightsNames = array_keys($rights);
			foreach ($rightsNames as $rightName) {
				$req .= $rightName.', ';
				$endReq .= ':'.$rightName.', ';
			}
			// on retire les ',' de trop et concatène le tout
			$req = substr($req, 0, -2);
			$endReq = substr($endReq, 0, -2);
			$req .= ') VALUES ('.$endReq.')';

			// on ajoute les bindValue et execute la requête
			$request = $this->dao->prepare($req);
			foreach ($rights as $rightName => $right) {
				$request->bindValue(':'.$rightName, $right, \PDO::PARAM_BOOL);
			}
			$request->execute();

			// on s'occupe d'ajouter la linguistique de la catégorie
			$request = $this->dao->prepare('INSERT INTO category_has_language (category_id, language_abbreviation, name, description)
											VALUES (:id, :language, :name, :description)');
			$request->bindValue(':id', $this->getLastInsertId(), \PDO::PARAM_INT);
			$request->bindValue(':name', $category->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $category->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();
			
		// on modifie une catégorie
		}else{
			$request = $this->dao->prepare('UPDATE category_has_language 
											SET name = :name, description = :description 
											WHERE category_id = :id AND language = :language');
			$request->bindValue(':id', $category->getId(), \PDO::PARAM_INT);
			$request->bindValue(':name', $category->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $category->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();

			// on modifie les droits correspondants
			$req = 'UPDATE category SET ';
			$rights = $category->getRights();
			$rightsNames = array_keys($rights);
			foreach ($rightsNames as $rightName) {
				$req .= $rightName.' = :'.$rightName.', ';
			}
			// on retire la ',' de trop
			$req = substr($req, 0, -2);
			$req .= ' WHERE id = :id';

			// on prépare la requête et on y ajoute les bindValue
			$request = $this->dao->prepare($req);
			$request->bindValue(':id', $category->getId(), \PDO::PARAM_BOOL);
			foreach ($rights as $rightName => $right) {
				$request->bindValue(':'.$rightName, $right, \PDO::PARAM_BOOL);
			}
			$request->execute();
		}
	}


}
?>