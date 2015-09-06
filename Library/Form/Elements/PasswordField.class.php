<?php

namespace Library\Form\Elements;

class PasswordField extends \Library\Form\Elements\StringField {


	/**
	 * permet de construire un champs de mot de passe
	 * @access public
	 * @return string le contenu html représentant un champs de mot de passe
	 */
	public function buildElement() {
		$view = '';

		if (!empty($this->errorMessage)) {
			$view .= '<span class="error">'.$this->errorMessage.'</span><br/>';
		}

		$view .= '<label for="'.htmlentities($this->name).'"';
		if($this->getClasses()!=""){
			$view .= 'class="'.$this->getClasses().'"';
		}
		$view .= '>'.htmlentities($this->label).'</label><input type="password" id="'.htmlentities($this->name).'" name="'.htmlspecialchars($this->name).'"';

		if (!empty($this->maxLength)) {
			$view .= ' maxLength="'.htmlentities($this->maxLength).'"';
		}

		$view .= ' />';
		return $view;
	}


}
?>