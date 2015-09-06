<?php

namespace Library;

/**
* Classe représentant la requête HTTP émise par le client
*/
class HTTPResponse {
	
	/* page à retourner dans notre réponse */
	protected $page; 	// Page

	/**
	* méthode permettant d'attribuer la page à renvoyer au client
	* @access public
	* @param $page Page; objet page devant être renvoyé au client
	* @return void
	**/
	public function setPage(Page $page){
		$this->page = $page;
	}

	/**
	* méthode permettant d'ajouter un header dans notre réponse HTTP
	* @access public
	* @param $header string; header à ajouter à la réponse
	* @return void
	**/
	public function addHeader($header){
		header($header);
	}

	/**
	* méthode permettant de rediriger notre client sur une autre page, termine le tout
	* @access public
	* @param $url string; l'URL à atteindre lors de la redirection
	* @return void
	**/
	public function redirect($location){
		header('location: '.$location);
		exit;
	}

	/**
	 * méthode renvoyant l'utilisateur à une page d'erreur 404
	 * @return void
	**/
	public function redirect404(){
		$this->page->setContentFile(ROOT.DS.'Errors'.DS.'404.html');

		$this->addHeader('HTTP/1.0 404 Not Found');

		$this->send();
	}

	/**
	* méthode permettant d'ajouter un cookie à l'utilisateur (identique à la fonction standard excepté le dernier argument)
	* @access public
	* @param $name string; 			nom du cookie à ajouter
	* @param $value='' string;  	value du cookie
	* @param $expire=0 string;		temps avant expiration du cookie (0 = fin de session)
	* @param $path=null string;		chemin de disponibilité du cookie sur le serveur
	* @param $domain=null string;	domaine sur lequel le cookie est atteignable sur le serveur
	* @param $secure=null string;	définir si le cookie doit refuser de passer à travers une connection non-sécurisée (HTTPS only)
	* @param $httpOnly=true bool;	par sécurité on refuse par défaut l'accès à nos cookies via des scripts hors protocole HTTP
	* @return void
	*/
	public function setCookie($name, $value = '', $expire = 0, $path = null,
							  $domain = null, $secure = null, $httpOnly = true){
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}
	/**
	* méthode permettant d'envoyer la requête au client et termine le tout
	* @access public
	* @return void
	**/
	public function send(){
		exit($this->page->getGeneratedPage());
	}
}

?>