<?php

namespace Library\Form\Builders;

class SimpleTextFormBuilder extends \Library\Form\Builders\FormBuilder {


	/**
	 * Permet de créer un formulaire propre au texte simple
	 * @access public
	 * @return void
	 */
	public function build() {
		$this->form->add(new \Library\Form\Elements\TextField(array(
			'label' 	=> \Library\LanguagesManager::get('content'),
			'name'		=> 'content',
			'rows'		=> 20,
			'alone'		=> true,
			'parameter' => '-editor',
			'validators'=> array(new \Library\Form\Validators\NotNullValidator('Il vous faut entrer du contenu.'))
			)));

		$this->form->add(new \Library\Form\Elements\CheckboxField(array(
			'label' 	=> \Library\LanguagesManager::get('private'),
			'classes'	=> 'invisible',
			'name'		=> 'private'
			)));
	}


}
?>