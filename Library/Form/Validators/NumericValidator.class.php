<?php


namespace Library\Form\Validators;

class NumericValidator extends \Library\Form\Validators\Validator {


	/**
	 * vérifie si l'élément passé est bien un entier
	 * @access public
	 * @param string $value valeur à vérifier
	 * @return bool si oui ou non la donnée est valide
	 */
	public function isValid($value) {
		return is_numeric($value);
	}


}
?>