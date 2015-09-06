<?php

namespace Library;

/* Classe permettant de gérer tous les types de managers d'un certain type
 * chaque module possède X manager, via PDO, ou xml, peut importe
 * mais chaque manager d'un certain type possèderont le même système "managers" de connexion, etc.
 */
class Managers {

	protected  $dao = null;
	protected  $managers = array();


	/**
	 * Constructeur de notre liste de managers
	 * @access public
	 * @param object $dao 
	 * @return void
	 */
	public function __construct($dao) {
		$this->dao = $dao;
	}


	/**
	 * Permet de récupérer le manager spécifique à un module
	 * @access public
	 * @param string $module 
	 * @return object
	 */
	public function getManagerOf($module) {
		if (!is_string($module) || empty($module)) {
			throw new RuntimeException('Le nom du module doit être une chaine de caractères');
		}
		if($module == 'MainMenu' || $module == 'Submenu'){
			$module = 'Menu';
		}
		// si le module n'est pas encore répértorié alors on l'ajoute, sinon on le renvoie directement
		if (!isset($this->managers[$module])) {
			$manager = '\\Library\\Models\\'.$module.'Manager';
			$this->managers[$module] = new $manager($this->dao);
		}
		return $this->managers[$module];
	}


}
?>