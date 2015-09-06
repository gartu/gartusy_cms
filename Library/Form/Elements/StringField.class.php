<?php

namespace Library\Form\Elements;

class StringField extends \Library\Form\Elements\Field {

	protected $maxLength;
	protected $abbr;
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

		$view .= '<label for="'.$this->name.'"';
		if($this->getClasses()!=""){
			$view .= 'class="'.$this->getClasses().'"';
		}
		$view .= '>'.$this->label.'</label><abbr title="'.$this->abbr.'"><input type="text" id="'.$this->name.'" name="'.$this->name.'"';

		if (!empty($this->value)) {
			$view .= ' value="'.$this->value.'"';
		}

		if (!empty($this->maxLength)) {
			$view .= ' maxLength="'.$this->maxLength.'"';
		}

		$view .= ' /></abbr>';
		return $view;
	}


	/**
	 * Met à jour le contenu d'info-bulle du champs
	 * @access public
	 * @param String $caption le contenu de l'info-bulle
	 * @return void
	 */
	public function setAbbr($caption){
		$this->abbr = $caption;
	}

	/**
	 * Met à jour la taille maximale du champs
	 * @access public
	 * @param int $maxLength la grandeur maximale du champs
	 * @return void
	 */
	public function setMaxLength($maxLength) {
		$maxLength = (int)$maxLength;

		if ($maxLength > 0) {
			$this->maxLength = $maxLength;
		}else{
			throw new \RunetimeException('La longueur du champs est nulle ou négative.');
		}
	}


}
?>