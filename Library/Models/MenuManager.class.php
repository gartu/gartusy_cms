<?php

namespace Library\Models;

class MenuManager extends \Library\Manager {

	protected $simpleTextManager;
	protected $changed = false;

	/**
	* Permet de donner le gestionnaire de texte aux menu car ceux-ci étant lié directement au moment
	* il faut qu'ils puissent être modifié par ici
	* @param \Library\Manager $textManager le gestionnaire de texte
	**/
	public function setTextManager(\Library\Manager $textManager){
		$this->simpleTextManager = $textManager;
	}

	/**
	 * Permet de signaler qu'un changement de module a été opéré
	 * @access public
	 */
	public function moduleChanged(){
		$this->changed = true;
	}

	/**
	 * Permet de savoir si un changement de module a été effectué
	 * @access public
	 * @return Boolean si oui ou non le module a été modifié
	 */
	public function moduleHasChanged(){
		return $this->changed;
	}

	/**
	 * Permet de récupérer un menu spécifique
	 * @access public
	 * @param  int $menuId l'id du menu 
	 * @return MainMenu le menu correspondant à l'id passé en paramètre
	 */
	public function getById($menuId, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, metric, private, visible, description,
											   options, module_name as module, controller_name as controller
										FROM v_menu 
										WHERE id = :menuId AND language = :language
										ORDER BY metric');
		$request->bindValue(':menuId', $menuId, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\MainMenu');
		$menu = $request->fetchAll();
		$menu = $menu[0];

		$menu->setSubmenus($this->getSubmenus($menuId));
		
		return $menu;
	}

	/**
	 * Permet de récupérer un sous-menu spécifique
	 * @access public
	 * @param  int $menuId l'id du menu 
	 * @param  int $subMenuId l'id du sous-menu 
	 * @return Submenu le sous-menu correspondant aux id passé en paramètre
	 */
	public function getSubById($subMenuId, $language = LANGUAGE) {

		$request = $this->dao->prepare('SELECT id, name, metric, visible, description, options, 
											   module_name as module, controller_name as controller, menu_id as menuId 
										FROM v_submenu 
										WHERE id = :subMenuId AND language = :language
										ORDER BY metric');
		$request->bindValue(':subMenuId', $subMenuId, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Submenu');
		$submenu = $request->fetchAll();
		
		return $submenu[0];
	}

	/**
	 * Permet de récupérer les sous-menus relatif à un id de menu
	 * @access protected
	 * @param  int $menuId l'id de menu parent
	 * @return array le tableau contenant les sous-menus
	 */
	protected function getSubmenus($menuId, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, metric, visible, description, options, 
											   module_name as module, controller_name as controller, 
											   menu_id as menuId 
										FROM v_submenu 
										WHERE menu_id = :menuId AND language = :language
										ORDER BY metric');
		$request->bindValue(':menuId', $menuId, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Submenu');
		$submenus = $request->fetchAll();
		return $submenus;
	}


	/**
	 * Permet de récupérer tous les menus
	 * @access public
	 * @return array le tableau contenant tous les menus
	 */
	public function getMenus($language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, metric, private, visible, description, options, 
											   module_name AS module, controller_name AS controller
										FROM v_menu 
										WHERE language = :language
										ORDER BY metric');
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\MainMenu');
		$menus = $request->fetchAll();

		// on ajoute les sous-menus correspondant à chaque menu
		foreach ($menus as $menu) {
			$menu->setSubmenus($this->getSubmenus($menu->getId()));
		}
		return $menus;
	}


	/**
	 * Permet de récupérer tous les modules
	 * @access public
	 * @return array le tableau contenant le nom de tous les modules
	 */
	public function getModules(){
		$request = $this->dao->prepare('SELECT * FROM module');
		$request->execute();

		$modules = $request->fetchAll();

		return $modules;
	}

	/**
	 * Permet de récupérer tous les controlleurs utilisable pour un module donné
	 * @access public
	 * @param String $moduleName le nom du module
	 * @return array le tableau contenant les controlleurs utilisable par le module 
	 */
	public function getControllers($moduleName){
		$request = $this->dao->prepare('SELECT controller_name 
										FROM module_has_controller 
										WHERE module_name = :moduleName');
		$request->bindValue(':moduleName', $moduleName, \PDO::PARAM_STR);
		$request->execute();

		$acceptedControllers = $request->fetchAll();

		$request = $this->dao->prepare('SELECT * FROM controller');
		$request->execute();
		
		$controllers = $request->fetchAll();

		$validControllers = array();		
		// on va vérifier quels controlleurs sont accepté ou pas.
		$acceptedList = explode(',', $acceptedControllers);
		foreach ($acceptedList as $acceptedId) {
			foreach ($controlleurs as $controller) {
				if($controlleur['id'] === acceptedId){
					array_push($valid, $controlleur['name']);
					break;
				}
			}
		}
		return $validControllers;
	}


	/**
	 * supprime un sous-menu
	 * @access public
	 * @param int $submenuId l'id du sous-menu à retirer
	 */
	public function deleteSubmenu($submenuId) {
		
		$request = $this->dao->prepare('DELETE
										FROM submenu_has_language 
										WHERE submenu_id = :id');
		$request->bindValue(':id', $submenuId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM submenu
										WHERE id = :id');
		$request->bindValue(':id', $submenuId, \PDO::PARAM_INT);
		$request->execute();
	}


	/**
	 * supprime un menu ainsi que ses sous-menus
	 * @access public
	 * @param int $mainMenuId l'id du menu à retirer
	 */
	public function deleteMainMenu($mainMenuId) {

		$submenus = $this->getSubmenus($mainMenuId);

		// on supprime les news associées à cette catégorie
		foreach ($submenus as $submenu) {
			$this->deleteSubmenu($submenu->getId());
		}

		$request = $this->dao->prepare('DELETE
										FROM menu_has_language 
										WHERE menu_id = :id');
		$request->bindValue(':id', $mainMenuId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM menu
										WHERE id = :id');
		$request->bindValue(':id', $mainMenuId, \PDO::PARAM_INT);
		$request->execute();
	}


	/**
	 * sauvegarde un menu principal, soit suite à une modification, soit à un ajout
	 * @access public
	 * @param MainMenu $menu le menu à sauver
	 * @return void
	 */
	public function saveMainMenu(\Library\Entities\MainMenu $menu, $language = LANGUAGE) {
		// on ajoute un nouveau texte
		if ($menu->isNew()) {
			$request = $this->dao->prepare('INSERT INTO menu (module_has_controller_module_name, module_has_controller_controller_name, metric, private, options) 
											VALUES (:module, :controller, :metric, :private, :options)');
			$request->bindValue(':module', $menu->getModule(), \PDO::PARAM_STR);
			$request->bindValue(':controller', $menu->getController(), \PDO::PARAM_STR);
			$request->bindValue(':metric', $menu->getMetric(), \PDO::PARAM_INT);
			$request->bindValue(':private', $menu->getPrivate(), \PDO::PARAM_BOOL);
			$request->bindValue(':options', $menu->getOptions(), \PDO::PARAM_STR);
			$request->execute();

			$menuId = $this->getLastInsertId();
			$this->lockId();

			// on ajoute le contenu dans la langue actuelle
			$request = $this->dao->prepare('INSERT INTO menu_has_language (menu_id, name, description, visible, language_abbreviation) 
											VALUES (:id, :name, :description, :visible, :language)');
			$request->bindValue(':id', $menuId, \PDO::PARAM_INT);
			$request->bindValue(':name', $menu->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $menu->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':visible', $menu->getVisible(), \PDO::PARAM_BOOL);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();

			// on ajoute le contenu "vide" dans les autres langues
			foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
				if($value != $language){
					$request = $this->dao->prepare('INSERT INTO menu_has_language (menu_id, name, description, visible, language_abbreviation) 
													VALUES (:id, :name, :description, :visible, :language)');
					$request->bindValue(':id', $menuId, \PDO::PARAM_INT);
					$request->bindValue(':name', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
					$request->bindValue(':description', '', \PDO::PARAM_STR);
					$request->bindValue(':visible', 0, \PDO::PARAM_BOOL);
					$request->bindValue(':language', $value, \PDO::PARAM_STR);
					$request->execute();
				}
			}

		// on modifie un texte déjà existant
		}else{
			// si on veut modifier le module, on doit supprimer le contenu présent dans options, relatif à celui-ci
			$oldMenu = $this->getById($menu->getId());
			if($oldMenu->getModule() === $menu->getModule()){
				$options = $menu->getOptions();
			}else if($menu->getModule() == 'texte'){
				$this->moduleChanged();
				$options = $this->simpleTextManager->create();
				$menu->setController('contenu');
			}else if($oldMenu->getModule() == 'texte'){
				$this->moduleChanged();
				$this->simpleTextManager->delete($oldMenu->getOptions());
				$menu->setController('liste');
				$options = '';
			}else{
				$this->moduleChanged();
				$options = '';
			}
				
			$request = $this->dao->prepare('UPDATE menu SET metric = :metric, private = :private, options = :options, module_has_controller_module_name = :module, module_has_controller_controller_name = :controller 
											WHERE id = :id');
			$request->bindValue(':id', $menu->getId(), \PDO::PARAM_INT);
			$request->bindValue(':module', $menu->getModule(), \PDO::PARAM_STR);
			$request->bindValue(':controller', $menu->getController(), \PDO::PARAM_STR);
			$request->bindValue(':metric', $menu->getMetric(), \PDO::PARAM_INT);
			$request->bindValue(':private', $menu->getPrivate(), \PDO::PARAM_BOOL);
			$request->bindValue(':options', $options, \PDO::PARAM_STR);
			$request->execute();

			// on ne met jamais la langue à jour ! uniquement le contenu !!
			$request = $this->dao->prepare('UPDATE menu_has_language SET visible = :visible, name = :name, description = :description 
											WHERE menu_id = :id AND language_abbreviation = :language');
			$request->bindValue(':id', $menu->getId(), \PDO::PARAM_INT);
			$request->bindValue(':visible', $menu->getVisible(), \PDO::PARAM_BOOL);
			$request->bindValue(':name', $menu->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $menu->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();
		}
		
	}


	/**
	 * sauvegarde un sous-menu, soit suite à une modification, soit à un ajout
	 * @access public
	 * @param Submenu $menu le menu à sauver
	 * @return void
	 */
	public function saveSubmenu(\Library\Entities\Submenu $menu, $language = LANGUAGE) {
		// on ajoute un nouveau texte		
		if ($menu->isNew()) {
			$request = $this->dao->prepare('INSERT INTO submenu (module_has_controller_module_name, module_has_controller_controller_name, menu_id, metric, options) 
											VALUES (:module, :controller, :idMenu, :metric, :options)');
			$request->bindValue(':module', $menu->getModule(), \PDO::PARAM_STR);
			$request->bindValue(':controller', $menu->getController(), \PDO::PARAM_STR);
			$request->bindValue(':idMenu', $menu->getMenuId(), \PDO::PARAM_INT);
			$request->bindValue(':metric', $menu->getMetric(), \PDO::PARAM_INT);
			$request->bindValue(':options', $menu->getOptions(), \PDO::PARAM_STR);
			$request->execute();

			$submenuId = $this->getLastInsertId();
			$this->lockId();

			// on ajoute pour la langue courante
			$request = $this->dao->prepare('INSERT INTO submenu_has_language (submenu_id, name, description, visible, language_abbreviation) 
											VALUES (:id, :name, :description, :visible, :language)');
			$request->bindValue(':id', $submenuId, \PDO::PARAM_INT);
			$request->bindValue(':visible', $menu->getVisible(), \PDO::PARAM_BOOL);
			$request->bindValue(':name', $menu->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $menu->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();


			// on ajoute le contenu "vide" dans les autres langues
			foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
				if($value != $language){
					$request = $this->dao->prepare('INSERT INTO submenu_has_language (submenu_id, visible, name, description, language_abbreviation) 
													VALUES (:id, :visible, :name, :description, :language)');
					$request->bindValue(':id', $submenuId, \PDO::PARAM_INT);
					$request->bindValue(':visible', 0, \PDO::PARAM_BOOL);
					$request->bindValue(':name', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
					$request->bindValue(':description', '', \PDO::PARAM_STR);
					$request->bindValue(':language', $value, \PDO::PARAM_STR);
					$request->execute();
				}
			}

		// on modifie un texte déjà existant
		}else{
			// si le module a été modifié alors il faut réinitialiser la partie options, car c'est la spécificité du module
			$oldMenu = $this->getSubById($menu->getId());
			if($oldMenu->getModule() === $menu->getModule()){
				$options = $menu->getOptions();
			}else if($menu->getModule() == 'texte'){
				$this->moduleChanged();
				$options = $this->simpleTextManager->create();
				$menu->setController('contenu');
			}else if($oldMenu->getModule() == 'texte'){
				$this->moduleChanged();
				$this->simpleTextManager->delete($oldMenu->getOptions());
				$menu->setController('liste');
				$options = '';
			}else{
				$this->moduleChanged();
				$options = '';
			}

			$request = $this->dao->prepare('UPDATE submenu 
											SET menu_id = :idMenu, metric = :metric, options = :options, module_has_controller_module_name = :module, module_has_controller_controller_name = :controller
											WHERE id = :id');
			$request->bindValue(':id', $menu->getId(), \PDO::PARAM_INT);
			$request->bindValue(':idMenu', $menu->getMenuId(), \PDO::PARAM_INT);
			$request->bindValue(':module', $menu->getModule(), \PDO::PARAM_STR);
			$request->bindValue(':controller', $menu->getController(), \PDO::PARAM_STR);
			$request->bindValue(':metric', $menu->getMetric(), \PDO::PARAM_INT);
			$request->bindValue(':options', $options, \PDO::PARAM_STR);
			$request->execute();

			// on ne met pas à jour la langue, ce qui est normal, ce serait vite bien trop complexe pour la gestion et pour peu d'utilité
			$request = $this->dao->prepare('UPDATE submenu_has_language
											SET visible = :visible, name = :name, description = :description
											WHERE submenu_id = :id AND language_abbreviation = :language');
			$request->bindValue(':id', $menu->getId(), \PDO::PARAM_INT);
			$request->bindValue(':visible', $menu->getVisible(), \PDO::PARAM_BOOL);
			$request->bindValue(':name', $menu->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $menu->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();
		}
	}



}
?>