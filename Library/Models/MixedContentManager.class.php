<?php


namespace Library\Models;

class MixedContentManager extends \Library\Manager {


	/**
	 * @access public
	 * @param int $listId; l'id de la page à afficher
	 * @param Managers $managers; la liste des managers
	 * @param bool $authenticated; si l'utilisateur est authentifié
	 * @return array un tableau contenant la liste des contenus
	 */
	public function getList($listId, $managers, $authenticated) {

		
		$list = array(array());

		// on vérifie que le contenu ne soit pas privé
		if (!$authenticated) {
			$request = $this->dao->prepare('SELECT private FROM mixed_content_list WHERE id = :listId');
			$request->bindValue(':listId', $listId, \PDO::PARAM_INT);
			$request->execute();
			if ($result = $request->fetch()) {
				// si on a a faire à une tentative d'accès non connecté à une ressource privée
				if ($result[0]) {
					return $list;
				}
			}
		}
		
		$request = $this->dao->prepare('SELECT contentType, vars FROM mixed_content WHERE mixed_page_id = :listId ORDER BY metric ASC');
		$request->bindValue(':listId', $listId, \PDO::PARAM_INT);
		$request->execute();
		
		$i = 0;
		// tant qu'on a des contenu correspondant mixé
		while ($result = $request->fetch()) {
			// on récupère le type du contenu (News, Connection, ..)
			$list[$i]['contentType'] = $result['contentType'];
			
			$manager = $managers->getManagerOf($list[$i]['contentType']);
			// On récupère les variables définissant ce contenu (stoqué en string ex.'limit=1,offset=2,id=3') 
			// et on découpe cette chaine pour obtenir un tableau : array('limit=1','offset=2','id=3')
			$elements = explode(',', $result['vars']);
			// pour chaque élément on le découpe et stock en array: vars['limit'] = 1, vars['offset'] = 2, vars['id'] = 3
			foreach ($elements as $element) {
				$temp = explode('=', $element);
				$vars[$temp[0]] = $temp[1];
			}
			// on récupère le contenu mixte généré d'après les variables
			$list[$i]['content'] = $manager->getPart($vars);
			$i++;
		}
		return $list;
	}

}
?>