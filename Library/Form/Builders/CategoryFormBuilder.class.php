<?php

namespace Library\Form\Builders;

class CategoryFormBuilder extends \Library\Form\Builders\FormBuilder {


	/**
	 * Permet de créer le formulaire propre aux catégories
	 * @access public
	 * @return void
	 */
	public function build() {
		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('name'),
			'name'		=> 'name',
			'classes'	=> 'alignment',
			'maxLength' => 25,
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le nom est trop long (max 25 caractères).', 25), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un nom de catégorie.'))
		)));
		$this->form->add(new \Library\Form\Elements\TextField(array(
			'label' 	=> \Library\LanguagesManager::get('description'),
			'name'		=> 'description',
			'classes'	=> 'alignment',
			'rows'		=> 8,
			'cols'		=> 50,
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('La description est trop longue (max 500 caractères).', 500))
		)));

		$rights = $this->form->getEntity()->getRights();

		// on ajoute les droits sous forme de checkbox
		foreach ($rights as $right => $value) {
			if (!is_numeric($right)) {
				$this->form->add(new \Library\Form\Elements\CheckboxField(array(
					'label' 	=> str_replace('_', ' ', $right),
					'name'		=> $right,
					'value'		=> $value
				)));
			}
		}

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'rightsId',
			'value'	=> $this->form->getEntity()->getRightsId()
		)));
	}


}
?>