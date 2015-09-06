<?php

namespace Library\Form\Validators;

class MinValueValidator extends \Library\Form\Validators\Validator {


	protected  $minValue;


	/**
	 * Constructeur d'une validateur de valeur minimum
	 * @access public
	 * @param string $errorMessage 
	 * @param int $minValue 
	 * @return void
	 */
	public function __construct($errorMessage, $minValue) {
		parent::__construct($errorMessage);
		$this->setMinValue($minValue);
	}


	/**
	 * Permet de savoir si la donnée passée est validée
	 * @access public
	 * @param string $value la donnée à vérifier
	 * @return bool si oui ou non la donnée est valide
	 */
	public function isValid($value) {
		if (is_numeric($value)) {
			return (int)$value >= $this->minValue;
		}
	}


	/**
	 * récupère la valeur minimale
	 * @access public
	 * @return int la valeur minimale définie
	 */
	public function getMinValue() {
		return $this->minValue;
	}


	/**
	 * met à jour la valeur minimale
	 * @access public
	 * @param int $minValue la valeur minimale
	 * @return void
	 */
	public function setMinValue($minValue) {
		if (is_numeric($minValue)) {
			$this->minValue = (int)$minValue;
		}
	}


}
?>