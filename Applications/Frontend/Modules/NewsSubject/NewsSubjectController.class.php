<?php

namespace Applications\Frontend\Modules\NewsSubject;

class NewsSubjectController extends \Library\BackController {


	/**
	 * affiche la page de modification des sujets de news
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */

	public function executeIndex(\Library\HTTPRequest $request) {
		
		$pictureManager = $this->managers->getManagerOf('Picture');
		$newsSubjectManager = $this->managers->getManagerOf('NewsSubject');
		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);

		$subjects = $newsSubjectManager->getList();
		$newsPerPage = $this->app->getConfig()->getParam('newsPerCategory');
		
		if($request->getExists('id')){
			$selected = $request->getGetData('id');
		}else{
			$selected = $subjects[0]->getId();
		}
		
		if($request->getExists('page')){
			$page = $request->getGetData('page');
		}else{
			$page = 0;
		}

		// on cherche les X news de la page parmis les visibles
		$newsList = $newsManager->getList($page * $newsPerPage, $newsPerPage, $selected, 1);
		$numberNews = $newsManager->count(1, $selected);

		$this->page->addVar('imgPath', $this->app->getConfig()->getParam('urlImage'));
		$this->page->addVar('thumbPath', $this->app->getConfig()->getParam('urlThumbnail'));
		$this->page->addVar('categories', $subjects);
		$this->page->addVar('selectedCategory', $selected);
		$this->page->addVar('page', $page);
		$this->page->addVar('newsList', $newsList);
		$this->page->addVar('numberPage', $numberNews / $newsPerPage);
	}

}
?>