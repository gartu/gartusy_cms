<?php

namespace Library\Form\Elements;

class Image extends \Library\Form\Elements\Element {

	protected $src; 

	/**
	 * constructeur de la vue du champs
	 * @access public
	 * @return string la vue du champs correspondant en html
	 */
	public function buildElement() {
		$view = '';

		$view .= '<span>'.$this->label.'</span> <img src="'.$this->src.'"';
		
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
	 * Met à jour le champs source de l'image
	 * @param String $src la source de l'image
	 */
	public function setSrc($src){
		$this->src = $src;
	}

	/**
	 * Récupère le champs source de l'image
	 * @return String $src la source de l'image
	 */
	public function getSrc($src){
		return $this->src;
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

		return $view.'<div class="formImg">';
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