<?php

namespace Library\Form\Validators;

abstract class Validator {

	protected $errorMessage;


	/**
	 * Constructeur permettant d'initialiser le validateur avec son message d'erreur correspondant
	 * @access public
	 * @param string $errorMessage le message d'erreur du validateur
	 * @return void
	 */
	public function __construct($errorMessage) {
		$this->setErrorMessage($errorMessage);
	}


	/**
	 * récupère le message d'erreur du validateur
	 * @access public
	 * @return string le message d'erreur
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}


	/**
	 * met à jour le message d'erreur
	 * @access public
	 * @param string $errorMessage le message d'erreur
	 * @return void
	 */
	public function setErrorMessage($errorMessage) {
		if (is_string($errorMessage)) {
			$this->errorMessage = $errorMessage;	
		}
		
	}


	/**
	 * vérifie si l'élément est valide
	 * @access public
	 * @param string $value la valeur à vérifier
	 * @return bool si oui ou non l'élément est valide
	 */
	public abstract function isValid($value);

}
?>