<?php

namespace Library\Form\Elements;

class Line extends \Library\Form\Elements\Element {

	/**
	 * constructeur de la vue du champs
	 * @access public
	 * @return string la vue du champs correspondant en html
	 */
	public function buildElement() {
		$view = '<hr';

		if(!is_null($this->name)){
			$view .= ' name="'.$this->name.'"';
		}
		if(!is_null($this->classes)){
			$view .= ' class="'.$this->classes.'"';
		}
		$view .= ' />';

		return $view;
	}


	/**
	 * Permet de vérifier si le champs est valide
	 * @access public
	 * @return bool si oui ou non le champs est valide
	 */
	public function isValid() {
		return true;
	}


	/**
	 * permet de savoir quel séparateur employer avant le texte
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function beginningSeparator(){
		$view = '';
		if($this->clear === 1){
			$view .= '<hr class="clear" />';
		}

		return $view;
	}


	/**
	 * permet de savoir quel séparateur employer après le texte
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function endingSeparator(){
		return '';
	}

}
?>