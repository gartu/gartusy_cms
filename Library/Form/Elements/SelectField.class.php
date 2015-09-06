<?php

namespace Library\Form\Elements;

class SelectField extends \Library\Form\Elements\Field {

	protected $options;
	protected $onChange;

	/**
	 * créé la vue correspondante aux champs
	 * @access public
	 * @return string le code html correspondant à la vue du champs
	 */
	public function buildElement() {
		$view = '';

		if (!empty($this->errorMessage)) {
			$view .= '<span class="error">'.$this->errorMessage.'</span><br/>';
		}

		$view .= '<label for="'.$this->name.'"';
		if($this->getClasses()!=""){
			$view .= 'class="'.$this->getClasses().'"';
		}
		$view .= '>'.$this->label.'</label>
				  <select id="'.$this->name.'" name="'.$this->name.'"';

		if(!is_null($this->onChange)){
			$view .= ' onchange="alert(\''.$this->onChange.'\')"';
		}
		$view .= '>';

		foreach ($this->options as $key => $value) {
			$view .= '<option value="'.$value.'"';
			if ($value == $this->value) {
				$view .= ' selected="selected"';
			}
			$view .= '>'.$key.'</option>';
		}

		$view .= '</select>';
		return $view;
	}


	/**
	 * permet de modifier le contenu des options à placer dans la liste déroulante
	 * @access public
	 * @param array $options le tableau d'options
	 * @return void
	 */
	public function setOptions(array $options) {
		$this->options = $options;
	}

	
	/**
	 * permet de mettre en place un message d'alerte js lors du changement de la liste déroulante
	 * @access public
	 * @param String $caption le texte à afficher lors du changement
	 * @return void
	 */
	public function setOnChange($caption) {
		$this->onChange = $caption;
	}



}
?>