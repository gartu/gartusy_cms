<?php

namespace Library\Entities;

class MainMenu extends \Library\Entities\Menu {


	protected  $private;
	protected  $submenus;


	/**
	 * Récupère l'état de confidentialité du menu
	 * @access public
	 * @return bool si l'état du menu est privé (1) ou public (0)
	 */

	public function getPrivate() {
		return $this->private;
	}


	/**
	 * Récupère les sous-menus relatif à ce menu
	 * @access public
	 * @return array le tableau contenant les sous-menu de ce menu
	 */
	public function getSubmenus() {
		return $this->submenus;
	}


	/**
	 * met à jour la liste des sous-menu de ce menu
	 * @access public
	 * @param array la liste des sous-menus
	 */
	public function setSubmenus($submenus) {
		$this->submenus = $submenus;
	}


	/**
	 * Met à jour l'état de confidentialité du menu
	 * @access public
	 * @param bool $state si l'état du menu est privé (1) ou public (0)
	 */

	public function setPrivate($state) {
		$this->private = $state;
	}


}
?>