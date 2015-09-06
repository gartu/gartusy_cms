<?php

namespace Library\Form\Elements;

abstract class Field extends \Library\Form\Elements\Element {

	protected $value;
	protected $errorMessage;
	protected $validators = array();


	/**
	 * Permet de vérifier si le champs est valide
	 * @access public
	 * @return bool si oui ou non le champs est valide
	 */
	public function isValid() {
		// on passe tous les validateurs pour savoir si le champs est valide
		foreach ($this->validators as $validator) {
			if (!$validator->isValid($this->value)) {
				$this->errorMessage = $validator->getErrorMessage();
				return false;
			}
		}
		return true;
	}


	/**
	 * Ajoute des validateurs
	 * @access public
	 * @param array $validateurs le tableau des validateurs du champs
	 * @return void
	 */
	public function setValidators(array $validators) {
		foreach ($validators as $validator) {
			if ($validator instanceof \Library\Form\Validators\Validator && !in_array($validator, $this->validators)) {
				$this->validators[] = $validator;
			}
		}
	}

	/**
	 * récupère la valeur du champs
	 * @access public
	 * @return string la valeur du champs
	 */
	public function getValue() {
		return $this->value;
	}


	/**
	 * met à jour la valeur du champs
	 * @access public
	 * @param string $value la valeur du champs
	 * @return void
	 */
	public function setValue($value) {
		$this->value = $value;
	}


	/**
	 * permet de savoir quel séparateur employer avant ce champs
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function beginningSeparator(){
		$view = '';
		if($this->clear === 1){
			$view .= '<hr class="clear" />';
		}

		return $view.'<div class="formField">';
	}


	/**
	 * permet de savoir quel séparateur employer après ce champs
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function endingSeparator(){
		return '</div>';
	}

}
?>