<?php

namespace Applications\Frontend\Modules\Disconnection;

class DisconnectionController extends \Library\BackController {


	/**
	 * Supprime la connexion courrante de l'utilisateur en effaçant ses session et réinitialisant son auth
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request) {

		// on récupère le manager et supprime les données
		$disconnectionManager = $this->managers->getManagerOf('Disconnection');
		$userManager = $this->managers->getManagerOf('User');

		$disconnectionManager->dropConnection($this->app->getCurrentUser());
		$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('disconnection_success'));

		$redirection = $this->app->getConfig()->getParam('redirectionOnLogout');
		$this->app->getHttpResponse()->redirect($redirection);

	}


}
?>