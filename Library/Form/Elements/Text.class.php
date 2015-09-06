<?php

namespace Library\Form\Elements;

class Text extends \Library\Form\Elements\Element {

	/**
	 * constructeur de la vue du champs
	 * @access public
	 * @return string la vue du champs correspondant en html
	 */
	public function buildElement() {
		$view = '<span';

		if(!is_null($this->name)){
			$view .= ' name="'.$this->name.'"';
		}
		if(!is_null($this->classes)){
			$view .= ' class="'.$this->classes.'"';
		}
		$view .= '>'.$this->label.'</span>';

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

		return $view.'<div class="formText">';
	}


	/**
	 * permet de savoir quel séparateur employer après le texte
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function endingSeparator(){
		return '</div>';
	}

}
?>