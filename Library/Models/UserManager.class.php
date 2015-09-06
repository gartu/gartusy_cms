<?php

namespace Library\Models;

class UserManager extends \Library\Manager {


	/**
	 * Permet de récupérer la liste de tous les utilisateurs
	 * @access public
	 * @param Manager $categoryManager le manager des catégories
	 * @param int $first le numéro du premier élément à retourner 
	 * @param int $limit le nombre d'élément à retourner
	 * @return array la liste de utilisateurs
	 */
	public function getList(\Library\Manager $categoryManager, $first = -1, $limit = -1) {
		// comme il faut créer pour chaque utilisateur sa correspondance catégorie, 
		// on l'effectue de manière séquentielle par id
		$sql = 'SELECT login FROM user';

		if ((is_numeric($first) && is_numeric($limit)) && ($first != -1 || $limit != -1)) {
			$sql .= ' LIMIT '.(int)$limit.' OFFSET '.(int)$first;
		}

		$request = $this->dao->prepare($sql);
		$request->execute();

		$users = array();
		while ($result = $request->fetch()) {
			$users[] = $this->getByLogin($result['login'], $categoryManager);
		}
		return $users;
	}


	/**
	 * permet de récupérer un utilisateur selon son login
	 * @access public
	 * @param string $login le login
	 * @param Manager $categoryManager le manager des catégories
	 * @return User l'utilisateur correspondant
	 */
	public function getByLogin($login, \Library\Manager $categoryManager){
		$request = $this->dao->prepare('SELECT login, password, salt, name, 
											   surname, mail, category_id AS category 
										FROM user 
										WHERE login = :login');
		$request->bindValue(':login', $login, \PDO::PARAM_STR);
		$request->execute();

		$userArray = $request->fetch();

		if(!empty($userArray)){
			$userArray['category'] = $categoryManager->getById($userArray['category']);
			$user = new \Library\Entities\User($userArray);
		}else{
			$user = new \Library\Entities\User();
		}

		return $user;

	}


	/**
	 * permet de savoir si une chaine spécifique est déjà définie en tant que login,
	 * excepté sur l'id spécifié si tel est le cas
	 * @access public
	 * @param String $login le login à rechercher
	 * @param String $userLogin le login de l'utilisateur demandant la modification de login
	 * @return bool si oui ou non le login existe déjà ailleurs
	 */
	public function issetLogin($login, $userLogin = null){
		$sqlRequest = 'SELECT * 
					   FROM user 
					   WHERE login = :login ';
		if (!is_null($userLogin)) {
			$sqlRequest .= ' AND login <> :userLogin';
		}

		$request = $this->dao->prepare($sqlRequest);
		$request->bindValue(':login', $login, \PDO::PARAM_STR);
		if (!is_null($userLogin)) {
			$request->bindValue(':userLogin', $userLogin, \PDO::PARAM_STR);
		}
		$request->execute();

		$result = $request->fetch();
		return !empty($result);
	}


	/**
	 * permet de récupérer le nombre d'utilisateur présent en BD
	 * @access public
	 * @return int le nombre d'utilisateur présents
	 */
	public function count() {

		$request = $this->dao->prepare('SELECT COUNT(*) FROM user');
		$request->execute();

		$count = $request->fetch();
		return $count;
	}



	/**
	 * Sauvegarde un utilisateur dans la DB
	 * @access public
	 * @param User $user l'utilisateur à sauvegarder
	 * @return void
	 */
	public function saveUser(\Library\Entities\User $user) {
		// on ajoute un nouvel utilisateur
		if ($user->isNew()) {
			$request = $this->dao->prepare('INSERT INTO user (login, password, salt, name, surname, mail) VALUES (:login, :password, :salt, :name, :surname, :mail)');
			$request->bindValue(':login', $user->getLogin(), \PDO::PARAM_STR);
			$request->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);
			$request->bindValue(':salt', $user->getSalt(), \PDO::PARAM_INT);
			$request->bindValue(':name', $user->getName(), \PDO::PARAM_STR);
			$request->bindValue(':surname', $user->getSurname(), \PDO::PARAM_STR);
			$request->bindValue(':mail', $user->getMail(), \PDO::PARAM_STR);
			//$request->bindValue(':category_id', $user->getCategory()->getId(), \PDO::PARAM_INT); // on ne gère pas les différentes catégories, il y a un default dans la db
			$request->execute();
		// on modifie une news
		}else{
			$request = $this->dao->prepare('UPDATE user SET login = :login, password = :password, salt = :salt, name = :name, surname = :surname, mail = :mail WHERE login = :id');
			$request->bindValue(':id', $user->getId(), \PDO::PARAM_STR);
			$request->bindValue(':login', $user->getLogin(), \PDO::PARAM_STR);
			$request->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);
			$request->bindValue(':salt', $user->getSalt(), \PDO::PARAM_INT);
			$request->bindValue(':name', $user->getName(), \PDO::PARAM_STR);
			$request->bindValue(':surname', $user->getSurname(), \PDO::PARAM_STR);
			$request->bindValue(':mail', $user->getMail(), \PDO::PARAM_STR);
			// $request->bindValue(':category_id', $user->getCategory()->getId(), \PDO::PARAM_INT);
			$request->execute();
		}
	}

}
?>