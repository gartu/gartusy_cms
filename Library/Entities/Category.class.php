<?php

namespace Library\Entities;

/**
 * Gère les différentes catégorie d'utilisateur ainsi que leurs privilèges d'administration
 * De chaque catègorie sera déduit les menus / pages à afficher lors de la création de la structure
 */

class Category extends \Library\Entity {

	protected $name;
	protected $description;
	protected $rights; // définit les privilège d'administration; ex.: contient un tableau rights[edit_text] = bool et sera facile pour l'appel de getRight($action.'+'.$contenu)
	protected $rightsId;


	/**
	 * modifie la description de la catégorie
	 * @access public
	 * @param string la description de la catégorie
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


	/**
	 * récupère la description de la catégorie
	 * @access public
	 * @return string la description de la catégorie
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * modifie le nom de la catégorie
	 * @access public
	 * @param string $name le nom de la catégorie
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * modifie l'id des droits définissant la catégorie
	 * @access public
	 * @param int $rightsId l'id de des droits définissant la catégorie
	 */
	public function setRightsId($rightsId) {
		$this->rightsId = $rightsId;
	}


	/**
	 * récupère l'id des droits définissant la catégorie
	 * @access public
	 * @return int $rightsId l'id de des droits définissant la catégorie
	 */
	public function getRightsId() {
		return $this->rightsId;
	}


	/**
	 * récupère le nom de la catégorie
	 * @access public
	 * @return string le nom de la catégorie
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * permet de savoir si cette catégorie a des droits d'administrations
	 * @access public
	 * @return bool
	 */
	public function hasRight() {
		if(!empty($this->rights)){
			foreach ($this->rights as $right) {
				if ($right) {
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * récupère la valeur d'un privilège spécifique
	 * @access public
	 * @param string $rightName nom du privilège à récupérer 
	 * @return bool droit sur le privilège
	 */
	public function getRight($rightName) {
		return isset($this->rights[$rightName]) ? $this->rights[$rightName] : false;
	}


	/**
	 * récupère le tableau entier contenant tous les privilèges
	 * @access public
	 * @return array le tableau des privilèges
	 */
	public function getRights() {
		return $this->rights;
	}


	/**
	 * met à jour le tableau entier contenant tous les privilèges
	 * @access public
	 * @param array le tableau des privilèges / droits
	 */
	public function setRights($rights){
		$this->rights = $rights;
	}
}
?>