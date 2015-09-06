<?php

namespace Library\Form\Builders;

class NewsSubjectFormBuilder extends \Library\Form\Builders\FormBuilder {


	/**
	 * Permet de créer le formulaire propre aux news
	 * @access public
	 * @return void
	 */
	public function build() {
		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('name'),
			'name'		=> 'name',
			'maxLength' => 40,
			'classes'	=> 'alignment',
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le nom est trop long (max 40 caractères)', 40), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un nom.'))
			)));
		
		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'id',
			'value'	=> $this->form->getEntity()->getId()
		)));

	}
}
?>