<?php

namespace Applications\Backend;

class Backend extends \Library\Application {

	protected $authenticator;

	/**
	 * Constructeur de notre backend
	 * @access public
	 * @return voir
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'Backend';
	}


	/**
	 * Méthode qui se charge de lancer l'application
	 * @access public
	 * @return void
	 */
	public function run() {

		$user = $this->currentUser->getUser();

		// si l'utilisateur est connecté, et que sont auth est valide alors on instancie 
		// le controlleur qu'il désir, sinon on execute celui de connexion
		if($user->isAdmin()){
			$controller = $this->getController($this->httpRequest->getRequestURI());
		}else{
			// on déconnecte l'utilisateur
			$this->currentUser->cleanAllAttributes();

			// on redirige vers la connexion du frontend
			$redirection = $this->getConfig()->getParam('redirectToLogin');
			$this->getHttpResponse()->redirect($redirection);
		}

		$this->runLanguage();
		$controller->execute();

		$this->getHttpResponse()->setPage($controller->getPage());
		$this->getHttpResponse()->send();
	}


}



?>