<?php

namespace Applications\Frontend\Modules\News;

class NewsController extends \Library\BackController {

	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */

	public function executeTease(\Library\HTTPRequest $request) {
		$limitNews = $this->app->getConfig()->getParam('numberNews');
		$lengthNews = $this->app->getConfig()->getParam('numberCharsPreviews');

		$this->page->addVar('title', \Library\LanguagesManager::get('title_news_list'));

		$pictureManager = $this->managers->getManagerOf('Picture');
		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);
		
		$newsList = $newsManager->getList(0, $limitNews);

		$this->page->addVar('listeNews', $newsList);
		$this->page->addVar('imgPath', $this->app->getConfig()->getParam('urlImage'));
		$this->page->addVar('thumbPath', $this->app->getConfig()->getParam('urlThumbnail'));
	}


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */

	public function executeShow(\Library\HTTPRequest $request) {
		$limitNews = $this->app->getConfig()->getParam('numberNews');
		$lengthNews = $this->app->getConfig()->getParam('numberCharsPreviews');

		$this->page->addVar('title', \Library\LanguagesManager::get('title_news'));

		$pictureManager = $this->managers->getManagerOf('Picture');
		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);
		
		$news = $newsManager->getById($request->getGetData('id'));

		// on a créé la partie persistante 'news' placée avec les parties de texte
		$newsController = $this->app->getController('/'.\Library\LanguagesManager::getLanguage().'/liste-news-rapide.html');

		$newsController->execute();
		$this->page->addVar('newsPart', $newsController->getPage()->getGeneratedContentPage());

		$this->page->addVar('news', $news);
		$this->page->addVar('imgPath', $this->app->getConfig()->getParam('urlImage'));
		$this->page->addVar('thumbPath', $this->app->getConfig()->getParam('urlThumbnail'));
	}


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */

	public function executeIndex(\Library\HTTPRequest $request) {
		$limitNews = $this->app->getConfig()->getParam('numberNews');
		$lengthNews = $this->app->getConfig()->getParam('numberCharsPreviews');

		$this->page->addVar('title', \Library\LanguagesManager::get('title_news_list'));

		$pictureManager = $this->managers->getManagerOf('Picture');
		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);
		
		$newsList = $newsManager->getList(0, $limitNews);

		$this->page->addVar('listeNews', $newsList);
		$this->page->addVar('imgPath', $this->app->getConfig()->getParam('urlImage'));
		$this->page->addVar('thumbPath', $this->app->getConfig()->getParam('urlThumbnail'));
	}


}
?>
