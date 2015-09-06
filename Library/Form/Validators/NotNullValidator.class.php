<?php

namespace Library\Form\Validators;

class NotNullValidator extends \Library\Form\Validators\Validator {


	/**
	 * On vérifie si la donnée passée est bien valide, non-nulle
	 * @access public
	 * @param string $value la valeur à tester
	 * @return bool si oui ou non la valeur est non-nulle
	 */
	public function isValid($value) {
		return $value != null && $value != '';
	}


}
?>