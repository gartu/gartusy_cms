<?php

namespace Applications\Frontend\Modules\SimpleText;

class SimpleTextController extends \Library\BackController {


	/**
	 * Execute l'action show de ce module, qui affiche le contenu selon l'id demandé
	 * @access public
	 * @param HTTPRequest $request la requête de lutilisateur
	 * @return void
	 */
	public function executeShow(\Library\HTTPRequest $request) {

		$manager = $this->managers->getManagerOf('SimpleText');
		$simpleText = $manager->getById($request->getGetData('id'));
		
		// si l'utilisateur n'a pas le droit de voir le contenu on ne va pas l'afficher
		if ($simpleText->getPrivate() && !$this->app->getCurrentUser()->isLogged()) {
			$simpleText->setContent('');
		}

		// on a créé la partie persistante 'news' placée avec les parties de texte
		$newsController = $this->app->getController('/'.\Library\LanguagesManager::getLanguage().'/liste-news-rapide.html');

		$newsController->execute();
		$this->page->addVar('newsPart', $newsController->getPage()->getGeneratedContentPage());

		$this->page->addVar('text', $simpleText);
	}


}
?>