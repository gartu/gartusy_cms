<?php

namespace Applications\Backend\Modules\Connection;

class ConnectionController extends \Library\BackController {
	

	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request) {
		$this->page->addVar('title', \Library\LanguagesManager::get('connection'));

		// si on a à faire à une requête de connexion
		if($request->postExists('login')){
			// alors on récupère le manager et on vérifie les données
			$connectionManager = $this->managers->getManagerOf('Connection');
			$userManager = $this->managers->getManagerOf('User');

			$login = $request->getPostData('login');
			$password = $request->getPostData('password');

			$user = $userManager->getByLogin($login, $this->managers->getManagerOf('Category'));

			if($user != null && $connectionManager->isValid($user->getId(), $password)){
				$this->app->getCurrentUser()->hydrate($user);

				$redirection = $this->app->getConfig()->getParam('redirectionOnLogin');
				$this->app->getHttpResponse()->redirect($redirection);
			}else{
				// on place un message d'erreur
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('invalid_connection'));
			}

		}
	}
}
?>