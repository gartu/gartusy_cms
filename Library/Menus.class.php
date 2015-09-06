<?php

namespace Library;

class Menus extends ApplicationComponent {

	protected  $menus;



	/**
	 * Récupère le menus à afficher à l'utilisateur, soit publique, privé ou de l'administration 
	 * @access public
	 * @return array le menus à afficher
	 */
	public function getUserMenu(){
		// si on est dans l'administration alors on récupère les menus dans le fichier de configuration pour la création des menu
		/*if ($this->app->getName() === 'Backend') {
			$menus = $this->app->getConfig()->getList('menu');
		}else */
		
		if ($this->app->getCurrentUser()->isLogged()) {
			// on met tous les menus
			$menus = $this->getAllMenus();

		/*	// ajout à la suite du menu de déconnexion 

			$data['name'] = \Library\LanguagesManager::get('disconnection');
			$data['link'] = $this->app->getConfig()->getList('menu')['Deconnexion'];

			$disconnectionMenu = new \Library\Entities\MainMenu($data); 
			array_push($menus, $disconnectionMenu);*/
			
		}else{
			$menus = $this->getPublicMenus();
		}
		return $menus;
	}

	/**
	 * Récupère la liste de tous les menus 
	 * @access public
	 * @return array la liste de tous les menus
	 */
	public function getAllMenus() {
		if(is_null($this->menus)){
			// on récupère les données relatives au menu si on ne les possède pas via le manager
			$dao = $this->app->getConfig()->getParam('dao');
  	 		$managers = new Managers(call_user_func($dao));
  	 		$menusManager = $managers->getManagerOf('Menu');

  	 		$menus = $menusManager->getMenus();
			$this->menus = $menus;
		}
		return $this->menus;
	}


	/**
	 * Récupère la liste de tous les menus ayant une visibilité publique
	 * @access public
	 * @return array la liste des menus publiques
	 */
	public function getPublicMenus() {
		$allMenus = $this->getAllMenus();

		for ($i=0; $i < sizeof($allMenus); $i++) { 
			if ($allMenus[$i]->getPrivate()) {
				unset($allMenus[$i]);
			}
		}
		// on renvoie le contenu du tableau sans les valeurs privées
		return array_values($allMenus);
	}


	/**
	 * Récupère la liste des menus privés
	 * @access public
	 * @return array la liste des menus privé
	 */
	public function getPrivateMenus() {
		$allMenus = $this->getAllMenus();

		for ($i=0; $i < sizeof($allMenus); $i++) { 
			if (!$allMenus[$i]->getPrivate()) {
				unset($allMenus[$i]);
			}
		}
		// on renvoie le contenu du tableau sans les valeurs publiques
		return array_values($allMenus);
	}


}
?>