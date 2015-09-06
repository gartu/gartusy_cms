<?php
/**
 * Permet de définir un utilisateur quelconque du site internet
 */
namespace Library\Entities;

class User extends \Library\Entity {

	protected $login;
	protected $name;
	protected $surname;
	protected $mail;
	protected $category;
	protected $password; // le mot de passe stocké est hashé
	protected $salt;

	
	/**
	 * permet de savoir si l'utilisateur possède des droits d'administration
	 * @access public
	 * @return bool si oui ou non l'utilisateur peut administrer le site
	 */
	public function isAdmin() {
		return ($this->category != null) ? $this->category->hasRight() : false;
	}

	
	/**
	 * permet de récupérer le pseudo de l'utilisateur
	 * @access public
	 * @return string le pseudo de l'utilisateur
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * permet de récupérer le pseudo de l'utilisateur (id de l'user en réalité)
	 * @access public
	 * @return string le pseudo de l'utilisateur
	 */
	public function getId() {
		return isset($this->id) ? $this->id : $this->login;
	}


	/**
	 * permet de récupérer le nom de l'utilisateur
	 * @access public
	 * @return string le prénom de l'utilisateur
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * permet de récupérer le nom de famille de l'utilisateur
	 * @access public
	 * @return string le nom de famille de l'utilisateur
	 */
	public function getSurname() {
		return $this->surname;
	}


	/**
	 * permet de récupérer le mail de l'utilisateur
	 * @access public
	 * @return string le mail de l'utilisateur
	 */
	public function getMail() {
		return $this->mail;
	}


	/**
	 * permet de récupérer le sel utilisé par l'utilisateur
	 * @access public
	 * @return int le sel utilisé pour cet utilisateur
	 */
	public function getSalt() {
		return $this->salt;
	}


	/**
	 * permet de récupérer la catégorie de l'utilisateur
	 * @access public
	 * @return Category la catégorie de l'utilisateur
	 */
	public function getCategory() {
		return $this->category;
	}


	/**
	 * Permet de récupérer le hash du mot de passe de l'utilisateur
	 * @access public
	 * @return string le hash du mot de passe
	 */
	public function getPassword() {
		return $this->password;
	}


	/**
	 * permet de modifier la catégorie de l'utilisateur
	 * @access public
	 * @param Category $category la catégorie de l'utilisateur
	 */
	public function setCategory(\Library\Entities\Category $category) {
		$this->category = $category;
	}

	/**
	 * permet de modifier le login de l'utilisateur
	 * @access public
	 * @param string $login le login de l'utilisateur
	 */
	public function setLogin($login) {
		$this->login = $login;
	}

	/**
	 * permet de modifier le login de l'utilisateur
	 * @access public
	 * @param string $login le login de l'utilisateur
	 */
	public function setId($login) {
		if(!isset($this->login)) {
			$this->login = $login;
		}
		$this->id = $login; // l'id des utilisateurs sont leur login, mais l'id est utilisé pour savoir si une instance est nouvelle ou pas alors on en met par défaut .. bordel.. j-2
	}


	/**
	 * permet de modifier le hash de l'utilisateur en prenant en compte le sel déjà définit
	 * @access public
	 * @param string $password le password de l'utilisateur (non-hashé)
	 */
	public function generateHash($password) {
		$this->password = sha1($password.$this->salt);
	}


	/**
	 * permet de modifier le hash de l'utilisateur en prenant en compte le sel déjà définit
	 * @access public
	 * @param string $hash le password hashé de l'utilisateur 
	 */
	public function setPassword($hash) {
		$this->password = $hash;
	}

	/**
	 * Modifie le sel utilisé avec le mot de passe, une redéfinition du mot de passe sera nécessaire
	 * @access public
	 * @param int $salt le sel à appliquer
	 */
	public function setSalt($salt) {
		if (is_numeric($salt)) {
			$this->salt = (int)$salt;
		}
	}

	/**
	 * Modifie le nom de l'utilisateur
	 * @access public
	 * @param string $name nom de l'utilisateur
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Modifie le prénom de l'utilisateur 
	 * @access public
	 * @param string $surname le prénom
	 */
	public function setSurname($surname) {
		$this->surname = $surname;
	}

	/**
	 * Modifie le mail de l'utilisateur
	 * @access public
	 * @param string $mail le mail de l'utilisateur
	 */
	public function setMail($mail) {
		$this->mail = $mail;
	}



}
?>