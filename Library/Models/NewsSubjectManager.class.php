<?php

namespace Library\Models;

class NewsSubjectManager extends \Library\Manager {


	/**
	 * Récupère la liste de tous les sujet de news possibles
	 * @access public
	 * @return array la liste des sujets de news
	 */
	public function getList($language = LANGUAGE) {
		
		$request = $this->dao->prepare('SELECT id, name 
										FROM v_news_subject
										WHERE language = :language');
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\NewsSubject');

		$newsSubjectList = $request->fetchAll();
		return $newsSubjectList;
	}


	/**
	 * Récupère un sujet de news selon son ID
	 * @access public
	 * @param int $id l'id du sujet de news à récupérer
	 * @return NewsSubject le sujet de news correspondante à l'id
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name
										FROM v_news_subject 
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\NewsSubject');

		$newsSubject = $request->fetch();
		return $newsSubject;
	}


	/**
	 * supprime une catégorie de news ainsi que toutes les news y relatives
	 * @access public
	 * @param int $newsSubjectId l'id de la catégorie de news à supprimer
	 * @param int $newsManager le gestionnaire de news afin de supprimer en cascade
	 */
	public function delete($newsSubjectId, \Library\Manager $newsManager) {
		
		$newsList = $newsManager->getList(-1, -1, $newsSubjectId);

		// on supprime les news associées à cette catégorie
		foreach ($newsList as $news) {
			$newsManager->delete($news->getId());
		}

		$request = $this->dao->prepare('DELETE
										FROM news_subject_has_language
										WHERE news_subject_id = :id');
		$request->bindValue(':id', $newsSubjectId, \PDO::PARAM_INT);
		$request->execute();
		
		$request = $this->dao->prepare('DELETE
										FROM news_subject
										WHERE id = :id');
		$request->bindValue(':id', $newsSubjectId, \PDO::PARAM_INT);
		$request->execute();

	}


	/**
	 * Enregistre un sujet de news
	 * @access public
	 * @param NewsSubject $newsSubject le sujet de news à enregistrer
	 * @return void
	 */
	public function saveNewsSubject($newsSubject, $language = LANGUAGE) {
		// on ajoute un nouveau sujet
		if ($newsSubject->isNew()) {

			$request = $this->dao->prepare('INSERT INTO news_subject () 
											VALUES ()');
			$request->execute();

			$newsSubjectId = $this->getLastInsertId();

			// ajout du contenu dans la langue courante
			$request = $this->dao->prepare('INSERT INTO news_subject_has_language (news_subject_id, language_abbreviation, name) 
											VALUES (:id, :language, :name)');
			$request->bindValue(':id', $newsSubjectId, \PDO::PARAM_INT);
			$request->bindValue(':name', $newsSubject->getName(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_INT);
			$request->execute();

			// ajout "vide" pour les contenu des autres langues
			foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
				if($value != $language){
					$request = $this->dao->prepare('INSERT INTO news_subject_has_language (news_subject_id, language_abbreviation, name) 
													VALUES (:id, :language, :name)');
					$request->bindValue(':id', $newsSubjectId, \PDO::PARAM_INT);
					$request->bindValue(':name', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
					$request->bindValue(':language', $value, \PDO::PARAM_INT);
					$request->execute();
				}
			}

		// on modifie un sujet
		}else{
			$request = $this->dao->prepare('UPDATE news_subject_has_language
											SET name = :name
											WHERE news_subject_id = :id AND language_abbreviation = :language');
			$request->bindValue(':id', $newsSubject->getId(), \PDO::PARAM_INT);
			$request->bindValue(':name', $newsSubject->getName(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();
		}
	}

}
?>