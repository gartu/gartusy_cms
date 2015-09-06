<?php

namespace Library\Entities;

class Submenu extends \Library\Entities\Menu {


	protected  $menuId;


	/**
	 * récupère l'id du menu principal auquel ce sous-menu est lié
	 * @access public
	 * @return int l'id du menu principal
	 */
	public function getMenuId() {
		return $this->menuId;
	}


	/**
	 * met à jour l'id du menu principal auquel le sous-menu est lié
	 * @access public
	 * @param Int l'id du menu auquel ce sous-menu est rattaché
	 */
	public function setMenuId($menuId) {
		$this->menuId = $menuId;
	}


}
?>