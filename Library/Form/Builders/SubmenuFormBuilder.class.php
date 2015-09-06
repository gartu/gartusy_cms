<?php

namespace Library\Form\Builders;

class SubmenuFormBuilder extends \Library\Form\Builders\FormBuilder {

	protected $menuManager;


	/**
	 * constructeur d'un formulaire de menu. On a besoin du manager de menus pour connaître la liste des différents modules
	 * que l'on peut associer à chaque menu
	 * @access public
	 * @param Entity $entity l'entité sur la base de laquelle le formulaire va être créer
	 * @param Manager $menuManager le manager des menus
	 * @return void
	 */
	public function __construct(\Library\Entity $entity, \Library\Manager $menuManager) {
		parent::__construct($entity);
		$this->menuManager = $menuManager;
	}


	/**
	 * Permet de créer le formulaire propre aux news
	 * @access public
	 * @return void
	 */
	public function build() {
		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('menu_title'),
			'name'		=> 'name',
			'classes'	=> 'alignment',
			'maxLength' => 40,
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le nom est trop long (max 40 caractères)', 40), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un nom de menu.'))
			)));

		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('description'),
			'name'		=> 'description',
			'classes'	=> 'alignment',
			'maxLength' => 40,
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le nom est trop long (max 40 caractères)', 40)
			))));

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'subId',
			'value'	=> $this->form->getEntity()->getId()
		)));

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'menuId',
			'value'	=> $this->form->getEntity()->getMenuId()
		)));

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'options',
			'value'	=> $this->form->getEntity()->getOptions()
		)));

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'controller',
			'value'	=> (is_null($this->form->getEntity()->getController()) ? 'liste' : $this->form->getEntity()->getController())
		)));

		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('metric'),
			'name'		=> 'metric',
			'classes'	=> 'alignment',
			'maxLength' => 5,
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le nom est trop long (max 5 chiffres).', 5),
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un nom de menu.'),
								 new \Library\Form\Validators\NumericValidator('Il vous faut entrer un nombre.'))
		)));

		$this->form->add(new \Library\Form\Elements\CheckboxField(array(
			'label' 	=> \Library\LanguagesManager::get('visibility'),
			'name'		=> 'visible',
			'value'		=> (is_null($this->form->getEntity()->getVisible()) ? '1' : $this->form->getEntity()->getVisible())
		)));

		$modulesList = $this->menuManager->getModules();

		foreach ($modulesList as $module) {
			if(\Library\LanguagesManager::get($module['name']) != ''){
				$modules[\Library\LanguagesManager::get($module['name'])] = $module['name'];
			}
		}

		$selectData = array(
				'label' 	=> \Library\LanguagesManager::get('module_choice'),
				'name' 		=> 'module',
				'classes'	=> 'alignment',
				'options'	=> $modules,
				'validator' => array(new \Library\Form\Validators\NotNullValidator('Selectionnez un module pour la page'))
			);

		$menu = $this->form->getEntity();
		// On place la valeur par défaut si celle-ci a déjà été selectionnée
		if (!is_null($menu->getModule())){
			$selectData['value'] = $menu->getModule();
			// si une valeur a été atttribuée alors le changement de module est à risque
			$selectData['onChange']	= \Library\LanguagesManager::get('alert_module_modification');
		}

		// on créé notre champs de selection multiple
		$this->form->add(new \Library\Form\Elements\SelectField($selectData));
	}

}
?>