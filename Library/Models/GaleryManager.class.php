<?php

namespace Library\Models;

class GaleryManager extends \Library\Manager {

	protected $pictureManager;

	/**
	* Permet de donner le gestionnaire d'image à la galerie afin d'effectuer les actions y relatives
	* @param \Library\Manager $pictureManager le gestionnaire d'image
	**/
	public function setPictureManager(\Library\Manager $pictureManager){
		$this->pictureManager = $pictureManager;
	}


	/**
	 * @access public
	 * @param int $first le premier élément à récupérer
	 * @param int $limit le nombre d'élément à récupérer
	 * @return array la liste des galeries dans la tranche
	 */
	public function getList($first = -1, $limit = -1, $language = LANGUAGE) {
		
		$sql = 'SELECT id, title, description
				FROM v_galery
				WHERE language = :language';

		if ((is_numeric($first) && is_numeric($limit)) && ($first != -1 || $limit != -1)) {
			$sql .= ' LIMIT '.(int)$limit.' OFFSET '.(int)$first;
		}

		$request = $this->dao->prepare($sql);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Galery');

		$galeryList = $request->fetchAll();
		return $galeryList;
	}


	/**
	 * Récupère une galerie selon son ID
	 * @access public
	 * @param int $id l'id de la galerie à récupérer
	 * @return Galery la galerie correspondant à l'id
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, title, description
										FROM v_galery
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Galery');

		$galery = $request->fetch();

		// on ajoute ensuite les images qui composent la galerie
		$galery->setPictures($this->pictureManager->getGaleryPictures($id));

		return $galery;
	}



	/**
	 * supprime une galerie ainsi que toutes les photos y relatives
	 * @access public
	 * @param int $galeryId l'id de la galerie à supprimer
	 */
	public function delete($galeryId) {

		$request = $this->dao->prepare('SELECT id 
										FROM v_picture
										WHERE galeryId = :id');
		$request->bindValue(':id', $galeryId, \PDO::PARAM_INT);
		$request->execute();
		$pictureIds = $request->fetchAll();
		
		// on est contraint d'appeller chaque fois le delete individuel
		// de chaque image car il faut également les supprimer physiquement
		foreach ($pictureIds as $pic) {
			$this->pictureManager->delete($pic[0]);
		}

		$request = $this->dao->prepare('DELETE
										FROM galery_has_language 
										WHERE galery_id = :id');
		$request->bindValue(':id', $galeryId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM galery
										WHERE id = :id');
		$request->bindValue(':id', $galeryId, \PDO::PARAM_INT);
		$request->execute();
	}


	/**
	 * Enregistre une galerie
	 * @access public
	 * @param ContactForm contactForm le fomulaire de contact à enregistrer
	 */
	public function saveGalery($galery, $language = LANGUAGE) {
		$state = false;
		// mise à jour d'une galerie existante
		if (!$galery->isNew()) {
			$galeryId = $galery->getId();

			$request = $this->dao->prepare('UPDATE galery_has_language 
											SET title = :title, description = :description
											WHERE galery_id = :id AND language_abbreviation = :language');
			$request->bindValue(':title', $galery->getTitle(), \PDO::PARAM_STR);
			$request->bindValue(':description', $galery->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':id', $galeryId, \PDO::PARAM_INT);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			if($request->execute()){
				$state = true;
				$toKeepId = array();
				if(!is_null($galery->getPictures())){
					foreach ($galery->getPictures() as $picture) {

						// pour les images déjà présentes précédemment on fait la mise à jour
						if(!is_null($picture->getId())){
							// on doit faire un tri, si existe alors on update, sinon on delete
							$this->pictureManager->savePicture($picture);

							$toKeepId[] = $picture->getId();
						}
					}
				}
				// on a fait les mises à jours des images de la galerie, on doit donc procéder à des suppressions
				foreach (array_diff($this->pictureManager->getGaleryPicturesIds($galeryId), $toKeepId) as $idToDelete) {

					// premièrement on récupère le format de l'image que l'on devra supprimé pour pouvoir
					// la supprimé physiquement sur le serveur
					$this->pictureManager->delete($idToDelete);
				}
			}

		// ajout d'une nouvelle galerie
		}else{
			$request = $this->dao->prepare('INSERT INTO galery () 
											VALUES ()');
			$request->execute();

			$galeryId = $this->getLastInsertId();
			$this->lockId();

			$request = $this->dao->prepare('INSERT INTO galery_has_language (galery_id, language_abbreviation, title, description) 
											VALUES (:galeryId, :language, :title, :description)');
			$request->bindValue(':galeryId', $galeryId, \PDO::PARAM_INT);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->bindValue(':title', $galery->getTitle(), \PDO::PARAM_STR);
			$request->bindValue(':description', $galery->getDescription(), \PDO::PARAM_STR);
			if($request->execute()){
				$state = true;
				// on ajoute le contenu "vide" dans les autres langues
				foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
					if($value != $language){
						$request = $this->dao->prepare('INSERT INTO galery_has_language (galery_id, language_abbreviation, title, description) 
														VALUES (:galeryId, :language, :title, :description)');
						$request->bindValue(':galeryId', $galeryId, \PDO::PARAM_INT);
						$request->bindValue(':title', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
						$request->bindValue(':description', '', \PDO::PARAM_STR);
						$request->bindValue(':language', $value, \PDO::PARAM_STR);
						$request->execute();
					}
				}
			}
		}

		if($state && (count($galery->getPictures()) !== 0)){

			// on ajoute les champs qui n'existaient pas encore
			foreach ($galery->getPictures() as $picture) {
				if(is_null($picture->getId())){

					$this->pictureManager->savePicture($picture, $galeryId);
					
					// on ajoute qu'une image à la fois, jamais plus
					break;
				}
			}
		}
	}

}
?>