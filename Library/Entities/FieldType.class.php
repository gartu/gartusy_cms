<?php

namespace Library\Entities;

class FieldType extends \Library\Entity {

	protected $name;
	protected $type;

	/**
	 * Récupère le nom du champs
	 * @access public
	 * @return string; le nom du champs
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * met à jour le nom du champs
	 * @access public
	 * @param string $name;	nom champs
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Récupère le type du champs, c'est à dire la classe dérivant de \Library\Form\Elements\Element représentée
	 * @access public
	 * @return string; le type du champs
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * met à jour le type du champs
	 * @access public
	 * @param string $type;	type du champs
	 */
	public function setType($type) {
		$this->type = $type;
	}


}
?>