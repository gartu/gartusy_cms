<?php

namespace Library\Models;

class SimpleTextManager extends \Library\Manager implements \Library\IMixable {


	/**
	 * récupère un contenu texte selon son id
	 * @access public
	 * @param int $id id du texte desiré
	 * @return SimpleText
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT * 
										FROM v_simple_text 
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\SimpleText');

		$simpleText = $request->fetch();
		
		return $simpleText;
	}

	/**
	 * Permet de créer un nouveau contenu texte et renvoyer son id
 	 * @access public
 	 * @return int l'id du nouveau contenu créé
	 */
	public function create($language = LANGUAGE) {
		$request = $this->dao->prepare('INSERT INTO simple_text (private)
										VALUES (0)');
		$request->execute();
		
		$id = $this->getLastInsertId();

		// on créé un contenu vide pour celui par défaut
		$request = $this->dao->prepare('INSERT INTO simple_text_has_language (simple_text_id, content, modified_date, language_abbreviation) 
										VALUES (:id, "", NOW(), :language)');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		// ajout "vide" pour les contenu des autres langues, avec text spécifiant aucun contenu
		foreach (\Library\LanguagesManager::getLanguages() as $key => $value) { 
			if($value != $language){
				$request = $this->dao->prepare('INSERT INTO simple_text_has_language (simple_text_id, content, modified_date, language_abbreviation) 
												VALUES (:id, :content, NOW(), :language)');
				$request->bindValue(':id', $id, \PDO::PARAM_INT);
				$request->bindValue(':content', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
				$request->bindValue(':language', $value, \PDO::PARAM_STR);
				$request->execute();
			}
		}

		return $id;
	}


	/**
	 * Récupère le texte intégré à un contenu mixte
	 * @access public
	 * @param array $vars 
	 * @return mixed
	 */
	public function getPart(array $vars = array()) {
		if (!empty($vars) && array_key_exists('id', $vars)) {
			return $this->getById($vars['id']);
		}
	}


	/**
	 * @access public
	 * @param int $first 
	 * @param int $limit 
	 * @return array
	 */
	public function getList($first = -1, $limit = -1) {
		$sql = 'SELECT * FROM v_simple_text ORDER BY modified_date DESC';

		if ((is_numeric($first) && is_numeric($limit)) && ($first != -1 || $limit != -1)) {
			$sql .= ' LIMIT '.(int)$limit.' OFFSET '.(int)$first;
		}

		$request = $this->dao->prepare($sql);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\SimpleText');

		$textList = $request->fetchAll();
		return $textList;
	}


	/**
	 * supprime la représentation d'un texte simple en base de données
	 * @access public
	 * @param int $textId l'id du contenu texte à supprimer
	 */
	public function delete($textId) {

		$request = $this->dao->prepare('DELETE
										FROM simple_text_has_language
										WHERE simple_text_id = :id');
		$request->bindValue(':id', $textId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM simple_text
										WHERE id = :id');
		$request->bindValue(':id', $textId, \PDO::PARAM_INT);
		$request->execute();
	}


	/**
	 * sauvegarde un texte, soit suite à une modification, soit à un ajout
	 * @access public
	 * @param SimpleText $SimpleText le texte à sauver
	 * @return void
	 */
	public function saveSimpleText($simpleText, $language = LANGUAGE) {
		// on ajoute un nouveau texte
		if ($simpleText->isNew()) {

			$request = $this->dao->prepare('INSERT INTO simple_text (private) VALUES (:private)');
			$request->bindValue(':private', $simpleText->getPrivate(), \PDO::PARAM_BOOL);
			$request->execute();

			$simpleTextId = $this->getLastInsertId();

			// ajout du contenu dans la langue courante
			$request = $this->dao->prepare('INSERT INTO simple_text_has_language (simple_text_id, content, modified_date, language_abbreviation) 
											VALUES (:id, :content, NOW(), :language)');
			$request->bindValue(':id', $simpleTextId, \PDO::PARAM_INT);
			$request->bindValue(':content', $simpleText->getContent(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();

			// ajout "vide" pour les contenu des autres langues
			foreach (\Library\LanguagesManager::getLanguages() as $key => $value) { 
				if($value != $language){
					$request = $this->dao->prepare('INSERT INTO simple_text_has_language (simple_text_id, content, modified_date, language_abbreviation) 
													VALUES (:id, :content, NOW(), :language)');
					$request->bindValue(':id', $simpleTextId, \PDO::PARAM_INT);
					$request->bindValue(':content', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
					$request->bindValue(':language', $value, \PDO::PARAM_STR);
					$request->execute();
				}
			}

		// on modifie un texte déjà existant
		}else{
			// on doit le faire en 2x car sinon on touche deux tables différente en une opération sur la vue
			$request = $this->dao->prepare('UPDATE simple_text_has_language 
											SET content = :content, modified_date = NOW() 
											WHERE simple_text_id = :id AND language_abbreviation = :language');
			$request->bindValue(':id', $simpleText->getId(), \PDO::PARAM_INT);
			$request->bindValue(':content', $simpleText->getContent(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();

			$request = $this->dao->prepare('UPDATE simple_text 
											SET private = :private
											WHERE id = :id');
			$request->bindValue(':id', $simpleText->getId(), \PDO::PARAM_INT);
			$request->bindValue(':private', $simpleText->getPrivate(), \PDO::PARAM_BOOL);
			$request->execute();
		}
	}


}
?>