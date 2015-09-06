<?php

namespace Library\Form\Elements;

class Button extends \Library\Form\Elements\Element {

	protected $onClick;

	/**
	 * constructeur de la vue du champs
	 * @access public
	 * @return string la vue du champs correspondant en html
	 */
	public function buildElement() {
		$view = '<button type="button"';
		
		if(!is_null($this->name)){
			$view .= ' name="'.$this->name.'"';
		}
		if(!is_null($this->classes)){
			$view .= ' class="'.$this->classes.'"';
		}
		if(!is_null($this->onClick)){
			$view .= ' onClick="javascript:'.htmlentities($this->onClick).'"';
		}
		$view .= '>'.$this->label.'</button>';

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
	 * permet de savoir quel séparateur employer avant ce bouton
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function beginningSeparator(){
		$view = '';
		if($this->clear === 1){
			$view .= '<hr class="clear" />';
		}

		return $view.'<div class="formButton">';
	}


	/**
	 * permet de savoir quel séparateur employer après ce bouton
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function endingSeparator(){
		return '</div>';
	}

	
	/**
	 * permet de mettre en place une réaction lors du clique sur le bouton, le contenu doit être du javascript
	 * @access public
	 * @param String $content le code javascript à effectuer lors du clique sur le bouton
	 * @return void
	 */
	public function setOnClick($content) {
		$this->onClick = $content;
	}
}
?>