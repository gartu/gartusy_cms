<?php

namespace Applications\Frontend;

/*	Notre classe Frontend est l'application côté client,
 *	l'espace globale du site
 */
class Frontend extends \Library\Application {


	/**
	 * Constructeur de notre frontend
	 * @access public
	 * @return voir
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'Frontend';
	}


	/**
	 * Méthode qui se charge de lancer l'application
	 * @access public
	 * @return void
	 */
	public function run() {

		// on récupère la route ainsi que les variables d'url
		$controller = $this->getController($this->httpRequest->getRequestURI());
		// on gère les langues, si possible avec la variable url récupérée
		$this->runLanguage();
		$controller->execute();

		$this->getHttpResponse()->setPage($controller->getPage());
		$this->getHttpResponse()->send();
	}


}
?>