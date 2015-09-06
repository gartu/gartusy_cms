<?php

namespace Library\Form\Validators;

class MaxLengthValidator extends \Library\Form\Validators\Validator {

	protected $maxLength;

	/**
	 * Constructeur permettant d'initialiser le validateur avec son message d'erreur et sa taille max
	 * @access public
	 * @param string $errorMessage le message d'erreur du validateur
	 * @param int $maxLength taille maximale
	 * @return void
	 */
	public function __construct($errorMessage, $maxLength) {
		parent::__construct($errorMessage);

		$this->setMaxLength($maxLength);
	}


	/**
	 * permet de savoir si la valeur passée est valide
	 * @access public
	 * @param string $value 
	 * @return bool
	 */
	public function isValid($value) {
		return strlen($value) <= $this->maxLength;
	}


	/**
	 * récupère la taille maximale autorisée
	 * @access public
	 * @return int la taille maximale
	 */
	public function getMaxLength() {
		return $maxLength;
	}


	/**
	 * met à jour la taille maximale autorisée 
	 * @access public
	 * @param int $maxLength la taille maximale
	 * @return void
	 */
	public function setMaxLength($maxLength) {
		$maxLength = (int)$maxLength;

		if ($maxLength > 0) {
			$this->maxLength = $maxLength;	
		}else{
			throw new \RuntimeException('La taille maximale doit etre supérieur à zéro');			
		}		
	}


}
?>