<?php

namespace Library\Form\Elements;

class HiddenField extends \Library\Form\Elements\Field {

	protected $id;

	/**
	 * créé la vue correspondante aux champs
	 * @access public
	 * @return string le code html correspondant à la vue du champs
	 */
	public function buildElement() {

		return '<input type="hidden" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" />';
	}

	/**
	 * récupère la valeur de l'id du champs
	 * @access public
	 * @return string la valeur de l'id du champs
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * met à jour l'id du champs
	 * @access public
	 * @param String $id la valeur de l'id du champs
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
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

		return $view;
	}


	/**
	 * permet de savoir quel séparateur employer après ce champs
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function endingSeparator(){
		return '';
	}


}
?>