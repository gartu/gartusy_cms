<?php

namespace Library\Form\Builders;

class PasswordFormBuilder extends \Library\Form\Builders\FormBuilder {


	/**
	 * On créé le formulaire propre au mot de passe (pour un utilisateur lambda)
	 * @access public
	 * @return void
	 */
	public function build() {
		$this->form->add(
			new \Library\Form\Elements\PasswordField(array(
				'label' 	=> \Library\LanguagesManager::get('pwd'),
				'name'		=> 'currentPassword',
				'maxLength' => 25,
				'classes'	=> 'alignment',
				'validators'=> array(
						new \Library\Form\Validators\MaxLengthValidator('Votre ancien mot de passe contient 25 caractères au plus', 25)
					)
				)
			)
		);		
		$this->form->add(
			new \Library\Form\Elements\PasswordField(array(
				'label' 	=> \Library\LanguagesManager::get('new_pwd'),
				'name' 		=> 'password',
				'maxLength' =>	25,
				'classes'	=> 'alignment',
				'validator' => array(
						new \Library\Form\Validators\MaxLengthValidator('Le mot de passe ne doit pas dépasser 25 caractères', 25)
					)
				)
			)
		);
		$this->form->add(
			new \Library\Form\Elements\PasswordField(array(
				'label' 	=> \Library\LanguagesManager::get('confirm_pwd'),
				'name' 		=> 'passwordConfirm',
				'maxLength' =>	25,
				'classes'	=> 'alignment',
				'validator' => array(
						new \Library\Form\Validators\MaxLengthValidator('Le mot de passe ne doit pas dépasser 25 caractères', 25)
					)
				)
			)
		);
	}


}
?>