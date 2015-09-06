<?php

namespace Library;
session_start();

class CurrentUser {

	/**
	 * Place les valeurs d'un utilisateur dans celui courrant
	 * @access public
	 * @param User $user l'utilisateur à créer en tant que courant
	 */
	public function hydrate(\Library\Entities\User $user){
		$_SESSION['user'] = serialize($user);
	}


	/**
	 * Récupère l'utilisateur courant (null si non-connecté)
	 * @access public
	 * @return User l'utilisateur courant
	 */
	public function getUser(){
		return isset($_SESSION['user']) ? unserialize($_SESSION['user']) : new \Library\Entities\User();
	}


	/**
	 * Permet de récupérer un attribut spécifique
	 * @access public
	 * @param mixed $attribut; 	nom de l'attribut à récupérer 
	 * @return mixed; 			valeur de l'attribut demandé, null si il n'existe pas ou s'il s'agit d'un attribut spécifique
	 */
	public function getAttribute($attribute) {
		return isset($_SESSION[$attribute]) ? $_SESSION[$attribute] : null;
	}


	/**
	 * Permet de savoir si l'utilisateur est connecté ou pas
	 * @access public
	 * @return bool; 	si l'utilisateur est connecté ou pas
	 */
	public function isLogged() {
		return (!is_null($this->getAttribute('user')));
	}

	/**
	 * permet de définir un attribut s'il n'existe pas, ou de le remplacer
	 * @access public
	 * @param string $name;	nom de l'attribut
	 * @param mixed $value;	valeur à associer à l'attribut
	 */
	public function setAttribute($name, $value) {
		$_SESSION[$name] = $value;
	}


	/**
	 * permet de savoir si un message a été transmit
	 * @access public
	 * @return bool
	 */
	public function hasMessage() {
		return isset($_SESSION['message']);
	}

	/**
	 * permet de savoir si un message a été transmit
	 * @access public
	 * @return bool
	 */
	public function getMessage() {
		$message = $_SESSION['message'];
		unset($_SESSION['message']);
		return $message;
	}


	/**
	 * permet de supprimer un attribut stoqué en session
	 * @access public
	 * @param string $attribute l'attribut à supprimer
	 */
	public function cleanAttribute($attribute){
		if (isset($_SESSION[$attribute])) {
			unset($_SESSION[$attribute]);
		}
	}


	/**
	 * permet de supprimer tous les attributs stoqué en session
	 * @access public
	 */
	public function cleanAllAttributes(){
		$_SESSION = array();
	}

}

?>