<?php

namespace Library\Form\Builders;

class UserFormBuilder extends \Library\Form\Builders\FormBuilder {

	protected $categoryManager;


	/**
	 * constructeur d'un formulaire pour utilisateur
	 * @access public
	 * @param Entity $entity l'entité sur la base de laquelle le formulaire va être créer
	 * @param Manager $categoryManager la manager des catégorie afin de récupérer la liste de celle-ci
	 * @return void
	 */
	public function __construct(\Library\Entity $entity, \Library\Manager $categoryManager) {
		parent::__construct($entity);
		$this->categoryManager = $categoryManager;
	}

	/**
	 * créé le formulaire propre aux utilisateurs
	 * @access public
	 * @return void
	 */
	public function build() {
		$this->form->add(
			new \Library\Form\Elements\StringField(array(
				'label' 	=> \Library\LanguagesManager::get('login'),
				'name' 		=> 'login',
				'maxLength' =>	20,
				'classes'	=> 'alignment',
				'validator' => array(
						new \Library\Form\Validators\MaxLengthValidator('Le login ne doit pas dépasser 20 caractères', 20),
						new \Library\Form\Validators\NotNullValidator('Le champs Login est obligatoire.')
					)
				)
			)
		);

		// désactivation, on met le champs en caché
		$this->form->add(
			new \Library\Form\Elements\HiddenField(array(
				'label' 	=> \Library\LanguagesManager::get('surname'),
				'name' 		=> 'surname',
				'maxLength' =>	20,
				'classes'	=> 'alignment',
				'validator' => array(
						new \Library\Form\Validators\MaxLengthValidator('Le prénom ne doit pas dépasser 20 caractères', 20)
					)
				)
			)
		);
		
		// désactivation, on met le champs en caché
		$this->form->add(
			new \Library\Form\Elements\HiddenField(array(
				'label' 	=> \Library\LanguagesManager::get('first_name'),
				'name' 		=> 'name',
				'maxLength' =>	20,
				'classes'	=> 'alignment',
				'validator' => array(
						new \Library\Form\Validators\MaxLengthValidator('Le nom ne doit pas dépasser 20 caractères', 20)
					)
				)
		 	)
		);

		$this->form->add(
			new \Library\Form\Elements\PasswordField(array(
				'label' 	=> \Library\LanguagesManager::get('pwd'),
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

		// désactivation, on met le champs en caché
		$this->form->add(
			new \Library\Form\Elements\HiddenField(array(
				'label' 	=> \Library\LanguagesManager::get('mail'),
				'name' 		=> 'mail',
				'maxLength' =>	50,
				'classes'	=> 'alignment',
				'validator' => array(
						new \Library\Form\Validators\MaxLengthValidator('Votre adresse mail ne doit pas dépasser 50 caractères', 50)
					)
				)
			)
		);

		/* les catégories sont désactivées (n'ont pas étées implémentées complétement car manque d'utilité)
		// notre formulaire contient la liste des catégorie, on doit donc les récupérer via notre manager
		$categoryList = $this->categoryManager->getList();
		foreach ($categoryList as $category) {
			$categoryArray[$category->getName()] = $category->getId();
		}

		$category = $this->form->getEntity()->getCategory();
		$selectData = array(
				'label' 	=> \Library\LanguagesManager::get('category'),
				'name' 		=> 'categoryId',
				'classes'	=> 'alignment',
				'options'	=> $categoryArray,
				'validator' => array(new \Library\Form\Validators\NotNullValidator('Le champs Login est obligatoire.'))
			);

		// On place la valeur par défaut si celle-ci a déjà été selectionnée
		if (!empty($category)){
			$selectData['value'] = $category->getId();
		}

		// on créé notre champs de selection multiple
		$this->form->add(new \Library\Form\Elements\SelectField($selectData));
		*/
	}

}
?>