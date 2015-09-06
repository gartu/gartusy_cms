<?php

namespace Library\Models;

class ConnectionManager extends \Library\Manager implements \Library\IMixable {


	/**
	 * @access public
	 * @param array $vars tableau de variables permettant de savoir quel valeur retourner
	 * @return mixed récupérer dans la vue part.php sous la variable $connection
	 */
	public function getPart(array $vars = array()) {
		return array_key_exists('userName', $vars) ? $vars['userName'] : 'Username';
	}


	/**
	 * @access public
	 * @param login $login login de l'utilisateur
	 * @param string $password mot de passe en claire de l'utilisateur
	 * @return bool  si le couple login / password est valide
	 */
	public function isValid($login, $password){
		$request = $this->dao->prepare('SELECT password, salt 
										FROM user 
										WHERE login = :login 
										LIMIT 1');
		$request->bindValue(':login', $login, \PDO::PARAM_STR);
		$request->execute();
		
		// la requête s'execute correctement
		if($result = $request->fetch()){
			// on vérifie le password et renvoie en conséquence
			return sha1($password.$result['salt']) == $result['password'];
		}
		return false;
	}


}
?>