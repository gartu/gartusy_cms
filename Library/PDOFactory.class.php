<?php
namespace Library;

/*	permet de créer des objets de type PDO
 *	selon le design patern Factory
 */
class PDOFactory {

	/**
	* Méthode permettant de récupérer la connexion PDO
	* @return PDO l'objet PDO correspondant à la connexion Mysql
	**/
	public static function getMysqlConnection(){
		$db = new \PDO('mysql:host='.MYSQL_HOST_NAME.';dbname='.MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD);
		$db->exec("SET CHARACTER SET utf8");
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $db;
	}

}

?>