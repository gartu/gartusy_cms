<?php

namespace Library\Form\Elements;

class checkboxField extends \Library\Form\Elements\Field {


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

		// on ajoute une checkbox cachée afin d'avoir un résultat à 0 si pas coché
		$view .= '<input type="hidden" name="checkboxes['.$this->name.']" value="0" /><span class="checkbox">';
		$view .= '<input type="checkbox" id="'.$this->name.'" name="checkboxes['.$this->name.']" value="1"';

		if ($this->value) {
			$view .= ' checked';
		}

		// l'input du checkbox n'est pas affiché, par contre on fait du label "checkboxStyle" la nouvelle case à cocher
		// et le texte est donc inscript dans le label "checkboxCaption"
		$view .= ' /><label class="';

		if($this->getClasses()!=""){
			$view .= $this->getClasses().' ';
		}
		$view .= 'checkboxStyle" for="'.$this->name.'"></span><label for="'.$this->name.'" class="';

		if($this->getClasses()!=""){
			$view .= $this->getClasses().' ';
		}
		$view .= 'checkboxCaption">'.$this->label.'</label>';
		return $view;
	}

}
?>