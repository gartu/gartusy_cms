<?php

namespace Library\Models;

class NewsManager extends \Library\Manager implements \Library\IMixable {


	protected $pictureManager;

	/**
	* Permet de donner le gestionnaire d'image aux news afin d'effectuer les actions y relatives
	* @param \Library\Manager $pictureManager le gestionnaire d'image
	**/
	public function setPictureManager(\Library\Manager $pictureManager){
		$this->pictureManager = $pictureManager;
	}

	/**
	 * @access public
	 * @param int $first 
	 * @param int $limit 
	 * @return array
	 */
	public function getList($first = -1, $limit = -1, $subjectId = -1, $visible = -1, $language = LANGUAGE) {
		$sql = 'SELECT id, subject_id AS subjectId, subject_name AS subjectName, visible, title, content, created_date AS createdDate, modified_date AS modifiedDate
				FROM v_news 
				WHERE language = :language';
		if ($subjectId != -1){
			$sql .= ' AND subject_id = :subjectId';
		}

		if($visible != -1){
			$sql .= ' AND visible = :visible';
		}

		$sql .= ' ORDER BY created_date DESC';

		if ((is_numeric($first) && is_numeric($limit)) && ($first != -1 || $limit != -1)) {
			$sql .= ' LIMIT '.(int)$limit.' OFFSET '.(int)$first;
		}

		$request = $this->dao->prepare($sql);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		
		if ($subjectId != -1){
			$request->bindValue(':subjectId', $subjectId, \PDO::PARAM_INT);
		}

		if($visible != -1){
			$request->bindValue(':visible', $visible, \PDO::PARAM_INT);
		}

		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\News');

		$newsList = $request->fetchAll();

		foreach ($newsList as $news) {
			$picture = $this->getPicture($news->getId());
			
			if(!is_null($picture)){
				$news->setPicture($picture);
			}
		}
		return $newsList;
	}

	/**
	 * Récupère l'image d'une news d'après son id si celle-ci en a
	 * @access public
	 * @param int $id l'id de la news
	 * @return \Library\Entities\Picture l'image de la news, null sinon
	 */
	protected function getPicture($id){
		$request = $this->dao->prepare('SELECT picture_id
										FROM v_news 
										WHERE id = :id');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->execute();
		$pictureId = $request->fetch();

		if(is_null($pictureId[0])){
			return null;
		}else{
			return $this->pictureManager->getById($pictureId[0]);
		}
	}

	/**
	 * Récupère une news selon son ID
	 * @access public
	 * @param int $id l'id de la news à récupérer
	 * @return News la news correspondante à l'id
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, subject_id AS subjectId, subject_name AS subjectName, visible, title, content, created_date AS createdDate, modified_date AS modifiedDate
										FROM v_news 
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\News');

		$news = $request->fetch();

		// on ajoute l'image à la news si celle-ci en a une
		$picture = $this->getPicture($news->getId());
		if(!is_null($picture)){
			$news->setPicture($picture);
		}

		return $news;
	}


	/**
	 * permet de récupérer le nombre de news présentes en BD
	 * @access public
	 * @param Integer $visible le filtre savoir quel type de news on compte, par défaut, toutes
	 * @param Integer $subjectId le filtre pour le nombre de news concernant tel ou tel catégorie
	 * @return int le nombre de news présentes
	 */
	public function count($visible = -1, $subjectId = -1) {

		$sql = 'SELECT COUNT(*) FROM v_news WHERE language = :language';

		if($subjectId != -1){
			$sql .=  ' AND subject_id = :subjectId';
		}

		if($visible != -1){
			$sql .= ' AND visible = :visible';
		}

		$request = $this->dao->prepare($sql);
		$request->bindValue(':language', LANGUAGE, \PDO::PARAM_STR);

		if($subjectId != -1){
			$request->bindValue(':subjectId', $subjectId, \PDO::PARAM_INT);
		}

		if($visible != -1){
			$request->bindValue(':visible', $visible, \PDO::PARAM_INT);
		}

		$request->execute();

		$count = $request->fetch();
		return $count[0];
	}


	/**
	 * supprime une news ainsi que son image y relative s'il y en a une
	 * @access public
	 * @param int $newsId l'id de la news à supprimer
	 */
	public function delete($newsId) {
		$request = $this->dao->prepare('SELECT picture_id
										FROM news
										WHERE id = :id');
		$request->bindValue(':id', $newsId, \PDO::PARAM_INT);
		$request->execute();
		$pictureId = $request->fetch();
		
		// s'il y a une image d'associée, on la supprime
		if(!is_null($pictureId)){
			$this->pictureManager->delete($pictureId[0]);
		}

		$request = $this->dao->prepare('DELETE
										FROM news_has_language 
										WHERE news_id = :id');
		$request->bindValue(':id', $newsId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM news
										WHERE id = :id');
		$request->bindValue(':id', $newsId, \PDO::PARAM_INT);
		$request->execute();
	}


	/**
	 * Enregistre une news
	 * @access public
	 * @param News $news la news à enregistrer
	 * @return void
	 */
	public function saveNews($news, $language = LANGUAGE) {
		$state = false;

		// on ajoute une nouvelle news
		if ($news->isNew()) {
			$request = $this->dao->prepare('INSERT INTO news (news_subject_id, created_date) VALUES (:subjectId, NOW())');
			$request->bindValue(':subjectId', $news->getSubjectId(), \PDO::PARAM_INT);			
			$request->execute();

			$newsId = $this->getLastInsertId();
			$this->lockId();

			// ajout pour langue courante
			$request = $this->dao->prepare('INSERT INTO news_has_language (news_id, visible, title, content, language_abbreviation, modified_date)
											VALUES (:id, :visible, :title, :content, :language, NOW())');
			$request->bindValue(':id', $newsId, \PDO::PARAM_INT);
			$request->bindValue(':visible', $news->getVisible(), \PDO::PARAM_BOOL);
			$request->bindValue(':title', $news->getTitle(), \PDO::PARAM_STR);
			$request->bindValue(':content', $news->getContent(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			if($request->execute()){
				$state = true;
				// on ajoute le contenu "vide" dans les autres langues
				foreach (\Library\LanguagesManager::getLanguages() as $key => $value) { 
					if($value != $language){
						$request = $this->dao->prepare('INSERT INTO news_has_language (news_id, visible, title, content, language_abbreviation, modified_date)
														VALUES (:id, :visible, :title, :content, :language, NOW())');
						$request->bindValue(':id', $newsId, \PDO::PARAM_INT);
						$request->bindValue(':visible', 0, \PDO::PARAM_BOOL);
						$request->bindValue(':title', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
						$request->bindValue(':content', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
						$request->bindValue(':language', $value, \PDO::PARAM_STR);
						$request->execute();
					}
				}
			}

		// on modifie une news
		}else{
			$request = $this->dao->prepare('UPDATE news 
											SET news_subject_id = :subjectId 
											WHERE id = :id');
			$request->bindValue(':id', $news->getId(), \PDO::PARAM_INT);
			$request->bindValue(':subjectId', $news->getSubjectId(), \PDO::PARAM_INT);
			if($request->execute()){
				$state = true;
				$request = $this->dao->prepare('UPDATE news_has_language 
												SET visible = :visible, title = :title, content = :content, modified_date = NOW()
												WHERE news_id = :id AND language_abbreviation = :language');
				$request->bindValue(':id', $news->getId(), \PDO::PARAM_INT);
				$request->bindValue(':visible', $news->getVisible(), \PDO::PARAM_BOOL);
				$request->bindValue(':title', $news->getTitle(), \PDO::PARAM_STR);
				$request->bindValue(':content', $news->getContent(), \PDO::PARAM_STR);
				$request->bindValue(':language', $language, \PDO::PARAM_STR);
				$request->execute();

				// si un image était associée avant et qu'elle ne l'est plus, alors c'est une suppression à effectuer
				$picture = $this->getPicture($news->getId());
				if($picture !== null && $news->getPicture() === null){
					$this->pictureManager->delete($picture->getId());

					// il reste à retirer l'id de l'image dans la table news
					$request = $this->dao->prepare('UPDATE news 
													SET picture_id = :pictureId 
													WHERE id = :id');
					$request->bindValue(':id', $news->getId(), \PDO::PARAM_INT);
					$request->bindValue(':pictureId', null, \PDO::PARAM_INT);
					$request->execute();	
				}
			}
		}

		// si l'utilisateur a uploadé une image, on l'ajoute en bd
		if($state && $news->getPicture() != null && is_null($news->getPicture()->getId())){

			// on ajoute l'image
			$this->pictureManager->savePicture($news->getPicture());

			// il reste à ajouter l'id de l'image dans la table news
			$request = $this->dao->prepare('UPDATE news 
											SET picture_id = :pictureId 
											WHERE id = :id');
			$request->bindValue(':id', is_null($news->getId()) ? $newsId : $news->getId(), \PDO::PARAM_INT);
			$request->bindValue(':pictureId', $this->pictureManager->getLastInsertPictureId(), \PDO::PARAM_INT);
			$request->execute();	
		}
	}


	/**
	 * Recherche la news correspondante aux vars, la créé et la renvoie
	 * @access public
	 * @param array $vars tableau de variables utile 
	 * @return mixed news demandée via les vars, et récupérer dans la vue part.php sous $news
	 */
	public function getPart(array $vars = array()){
		// par défaut on renvoie la dernière news
		if (empty($vars)) {
			$tmp = $this->getList(1, 1);
			return $tmp[0];

		// sinon on traite les variables passées
		}else{
			// si on veut une seule news
			if (array_key_exists('id', $vars)) {
				return $this->getById($vars['id']);

			// si on veut une liste de news
			}else if (array_key_exists('list', $vars)) {
				return $this->getList($vars['first'], $vars['limit']);
			}
		}
	}


}
?>