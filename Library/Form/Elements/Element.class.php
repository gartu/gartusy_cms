<?php

namespace Library\Form\Elements;

abstract class Element {

	protected $name;
	protected $label;
	protected $classes;
	protected $alone;
	protected $clear;


	/** 
	 * Permet d'hydrater notre champs à l'initialisation
	 * @access public
	 * @param array $params les paramètres correspondantes à notre champs
	 * @return void
	 */
	public function __construct($params = array()) {
		$this->alone = false;
		if(!empty($params))
			$this->hydrate($params);
	}


	/**
	 * constructeur de la vue du champs
	 * @access public
	 * @return string la vue du champs correspondant en html
	 */
	public abstract function buildElement() ;

	/**
	 * Permet de vérifier si le champs est valide
	 * @access public
	 * @return bool si oui ou non le champs est valide
	 */
	public abstract function isValid();


	/**
	 * Permet de générer le champs de manière dynamique avec les paramètres
	 * @access public
	 * @param array $params le tableau de paramètres définissant le champs
	 * @return void
	 */
	public function hydrate($params) {
		foreach ($params as $element => $value) {
			$method = 'set'.ucfirst($element);
			if(is_callable(array($this, $method))){
				$this->$method($value);
			}
		}
	}


	/**
	 * récupère le nom du champs
	 * @access public
	 * @return string le nom du champs
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * récupère le label du champs
	 * @access public
	 * @return string le label du champs
	 */
	public function getLabel() {
		return $this->label;
	}


	/**
	 * récupère la valeur de l'attribut class
	 * @access public 
	 * @return string la valeur de l'attribut
	 */
	public function getClasses() {
		return $this->classes;
	}


	/**
	 * met à jour la valeur d'alone, qui définit si l'élément doit 
	 * être impérativement seul sur sa ligne
	 * @access public
	 * @param boolean $alone si l'élément doit être placé seul
	 */
	public function setAlone($alone) {
		$this->alone = $alone;
	}


	/**
	 * récupère la valeur de l'attribut alone
	 * @access public 
	 * @return boolean la valeur de l'attribut
	 */
	public function isAlone() {
		return $this->alone;
	}


	/**
	 * met à jour le nom du champs
	 * @access public
	 * @param string $name le nom du champs
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}



	/**
	 * récupère la valeur de l'attribut clear
	 * @access public 
	 * @return string la valeur de l'attribut
	 */
	public function getClear() {
		return $this->clear;
	}


	/**
	 * met à jour la valeur de l'attribut clear
	 * @access public
	 * @param string $clear la valeur à attribuer (1 / 0)
	 * @return void
	 */
	public function setClear($clear) {
		$this->clear = $clear;
	}


	/**
	 * met à jour le label du champs
	 * @access public
	 * @param string $label le label du champs
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * met à jour la valeur de l'attribut class
	 * @access public
	 * @param string $classes la valeur de l'attribut
	 * @return void
	 */
	public function setClasses($classes) {
		$this->classes = $classes;
	}

	/**
	 * permet de savoir quel séparateur employer avant cet élément
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public abstract function beginningSeparator() ;

	/**
	 * permet de savoir quel séparateur employer après cet élément
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public abstract function endingSeparator() ;
}
?>