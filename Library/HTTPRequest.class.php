<?php

namespace Library;

/**
* Classe représentant la requête HTTP émise par le client
*/
class HTTPRequest {
	
	/**
	* fonction permettant de savoir sous quel méthode la requête a été executée (post ou get)
	* @return string le nom de la méthode utilisée
	**/
	public function method(){
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	* fonction permettant de récupérer le contenu URI
	* @return string renvoie le contenu URI sous forme de chaine de caractères
	**/
	public function getRequestURI(){
		return $_SERVER['REQUEST_URI'];
	}

	/**
	* fonction retournant le contenu du cookie ayant le nom correpondant à la clé passée
	* @param $key nom du cookie recherché
	* @return string renvoie le contenu du cookie, null si elle n'existe pas
	**/
	public function getCookieData($key){
		return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
	}

	/**
	* fonction permettant de savoir si un cookie existe sous un certain nom
	* @param $key nom du cookie recherché
	* @return bool renvoie true si le cookie existe, false sinon
	**/
	public function cookieExists($key){
		return isset($_COOKIE[$key]);
	}

	/**
	* fonction retournant le contenu de la variable get ayant le nom correpondant à la clé passée
	* @param $key nom de la variable get recherchée
	* @return string renvoie le contenu de cette variable, null si elle n'existe pas
	**/
	public function getGetData($key){
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}

	/**
	* fonction permettant de savoir si une certaine variable get existe
	* @param $key nom de la variable recherchée
	* @return bool renvoie true si le cookie existe, false sinon
	**/
	public function getExists($key){
		return isset($_GET[$key]);
	}

	/**
	* fonction retournant le contenu d'une variable post ayant le nom correpondant à la clé passée
	* @param $key nom de la variable recherchée
	* @return string renvoie le contenu de la variable, null si elle n'existe pas
	**/
	public function getPostData($key){
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	/**
	* fonction permettant de savoir si une variable post existe sous un certain nom
	* @param $key nom de la variable post recherchée
	* @return bool renvoie true si la variable existe, false sinon
	**/
	public function postExists($key){
		return isset($_POST[$key]);
	}

}

?>