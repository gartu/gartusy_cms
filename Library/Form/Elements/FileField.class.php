<?php

namespace Library\Form\Elements;

class FileField extends \Library\Form\Elements\Field {

	protected $url;
	protected $onChange;

	/**
	 * créé la vue correspondante au champs
	 * @access public
	 * @return string le code html correspondant à la vue du champs
	 */
	public function buildElement() {
		$view = '';
		
		if (!empty($this->errorMessage)) {
			$view .= '<span class="error">'.$this->errorMessage.'</span><br/>';
		}

		$view .= '<button type="button" onclick="document.getElementById(\''.$this->name.'\').click();">'.$this->label.'</button><input type="file" id="'.$this->name.'" name="'.$this->name.'"';
		if($this->onChange !== ''){
			$view .= 'onChange="'.$this->onChange.'"';
		}
		$view .= ' style="display:none" />';

		return $view;
	}


	/**
	 * permet de mettre à jour la valeur du onchange appliquée lors de la modification du champs
	 * @access public
	 * @param String $onChange; la valeur à appliquer sur le onchange
	 * @return void
	 */
	public function setOnChange($onChange) {
		$this->onChange = $onChange;
	}


}
?>