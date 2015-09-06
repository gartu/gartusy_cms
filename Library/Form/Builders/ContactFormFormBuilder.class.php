<?php

namespace Library\Form\Builders;

class ContactFormFormBuilder extends \Library\Form\Builders\FormBuilder {

	protected $contactFormManager;

	/**
	 * constructeur d'un formulaire pour la création de formulaire personnalisé
	 * @access public
	 * @param Entity $entity l'entité sur la base de laquelle le formulaire va être créer
	 * @param Manager $contactFormManager la manager des formulaires personnalisés afin de récupérer la liste de ceux-ci
	 * @return void
	 */
	public function __construct(\Library\Entity $entity, \Library\Manager $contactFormManager) {
		parent::__construct($entity);
		$this->contactFormManager = $contactFormManager;
	}


	/**
	 * Permet de créer le formulaire propre aux fomulaires de contact
	 * Cette méthode fonctionne de pair avec la fonction js "saveParams"
	 * @access public
	 * @param String les paramètres des champs, receptionné via url
	 * @return void
	 */
	public function build($strParams = null) {


		$entity = $this->form->getEntity();
		// voir fonction js "saveParams"
		if(!is_null($strParams)){
			// on a tronqué le / par un ? car les slash dans l'url modifie le chemin relatif aux ressources utilisé dans le layout
			$params = unserialize(base64_decode(str_replace('?', '/', $strParams)));
			$numberFields = $params['numberFields'];
			foreach ($params as $key => $value) {
				$params[$key] = utf8_encode($value);
				$method = 'set'.ucfirst($key);
				if(method_exists($entity, $method)){
					$entity->$method(utf8_encode($value));
				}
			}
			$jsRedirection = 'document.location.href=location.href.replace("-'.$strParams.'.html", "-"+document.getElementById("params").value+".html")';
		}else{
			$numberFields = 1;
			$jsRedirection = 'document.location.href=location.href.replace(".html", "-"+document.getElementById("params").value+".html")';
		}

		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('name'),
			'name'		=> 'name',
			'maxLength' => 90,
			'classes'	=> 'alignment',
			'value'		=> !is_null($entity->getName()) ? $entity->getName() : '',
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le titre est trop long (max 90 caractères)', 90), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'))
			)));

		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('receiver'),
			'name'		=> 'receiver',
			'maxLength' => 60,
			'classes'	=> 'alignment',
			'value'		=> !is_null($entity->getReceiver()) ? $entity->getReceiver() : '',
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le titre est trop long (max 60 caractères)', 60), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'))
			)));
		
		$this->form->add(new \Library\Form\Elements\TextField(array(
			'label' 	=> \Library\LanguagesManager::get('description'),
			'name'		=> 'description',
			'rows'		=> 6,
			'parameter' => '-editor',
			'alone'		=> true,
			'value'		=> !is_null($entity->getDescription()) ? $entity->getDescription() : ''
			)));

		$this->form->add(new \Library\Form\Elements\Line(array(
			'name'		=> 'separator',
			'classes'	=> 'separator',
			'alone'		=> true
			)));


		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'id'	=> 'id',
			'name'	=> 'id',
			'value'	=> $entity->getId()
			)));


		// ajout de chaque champs, un seul de base
		$fieldsList = $entity->getElements();
		if(count($fieldsList) !== 0 && $numberFields === 1){
			$numberFields = count($fieldsList);
		}
		$i = 0;
		do{
			if(count($fieldsList) > $i){
				$field = $fieldsList[$i];
			}else{
				$field = null;
			}
			if(!($field instanceof \Library\Entities\Field)){
				$field = new \Library\Entities\Field();
			}

			$this->form->add(new \Library\Form\Elements\Text(array(
				'label' => \Library\LanguagesManager::get('field').' n°'.($i+1),
				'name'	=> 'text'.$i,
				'clear' => 1
				)));
			
			$this->form->add(new \Library\Form\Elements\HiddenField(array(
				'id'	=> 'fieldId'.$i,
				'name'	=> 'fieldId'.$i,
				'value'	=> isset($params['fieldId'.$i]) ? $params['fieldId'.$i] : (is_null($field->getId()) ? '' : $field->getId())
				)));

			$this->form->add(new \Library\Form\Elements\StringField(array(
				'label' 	=> \Library\LanguagesManager::get('title'),
				'name'		=> 'nameField'.$i,
				'maxLength' => 35,
				'classes'	=> 'alignment',
				'value'		=> isset($params['nameField'.$i]) ? $params['nameField'.$i] : (!is_null($field->getName()) ? $field->getName() : ''),
				'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le titre est trop long (max 35 caractères)', 35), 
									 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'))
				)));

			$this->form->add(new \Library\Form\Elements\StringField(array(
				'label' 	=> \Library\LanguagesManager::get('metric'),
				'name'		=> 'metric'.$i,
				'maxLength' => 35,
				'classes'	=> 'alignment',
				'value'		=> isset($params['metric'.$i]) ? $params['metric'.$i] : (!is_null($field->getMetric()) ? $field->getMetric() : ($i+1)),		// par défaut on place les champs dans l'ordre dont ils sont créés
				'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Contenu trop long', 10), 
									 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'),
									 new \Library\Form\Validators\NumericValidator('Il vous faut entrer un nombre.'))
				)));


			// on traite les params reçus

			// notre formulaire contient la liste des champs disponible, récupéré via le manager de formulaire de contact
			$fieldTypeList = $this->contactFormManager->getFieldTypeList();
			foreach ($fieldTypeList as $fieldType) {
				$fieldsTypeArray[$fieldType->getName()] = $fieldType->getId();
			}

			$selectData = array(
					'label' 	=> \Library\LanguagesManager::get('field_type'),
					'name' 		=> 'fieldType'.$i,
					'classes'	=> 'alignment',
					'options'	=> $fieldsTypeArray,
					'validators' => array(new \Library\Form\Validators\NotNullValidator('Le choix d\'un type de champs est obligatoire.'))
				);
			// On place la valeur par défaut si celle-ci a déjà été selectionnée
			if(isset($params['fieldType'.$i])){
				$selectData['value'] = $params['fieldType'.$i];
			}else if(!is_null($field->getFieldTypeId())){
				$selectData['value'] = $field->getFieldTypeId();
			}

			// on créé notre champs de selection multiple
			$this->form->add(new \Library\Form\Elements\SelectField($selectData));


			$this->form->add(new \Library\Form\Elements\StringField(array(
				'label' 	=> \Library\LanguagesManager::get('help_message'),
				'name'		=> 'helpMessage'.$i,
				'maxLength' => 75,
				'classes'	=> 'alignment',
				'value'		=> isset($params['helpMessage'.$i]) ? $params['helpMessage'.$i] : (!is_null($field->getDescription()) ? $field->getDescription() : ''),
				'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le titre est trop long (max 75 caractères)', 75))
				)));
		
			$this->form->add(new \Library\Form\Elements\CheckboxField(array(
				'label' 	=> \Library\LanguagesManager::get('obligatory'),
				'name'		=> 'obligatory'.$i,
				'value'		=> isset($params['obligatory'.$i]) ? $params['obligatory'.$i] : (!is_null($field->getRequired()) ? $field->getRequired() : '')
				)));
			
			if($numberFields != 1){
				$this->form->add(new \Library\Form\Elements\Button(array(
					'label'   => \Library\LanguagesManager::get('remove_field'),
					'name'	  => 'remove',
					'clear'	  => 1,
					'onClick' => 'saveParamsAndHandleFields('.($numberFields - 1).', '.$i.');'.$jsRedirection
				)));
			}

		}while(++$i < $numberFields);

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'id'	=> 'params',
			'name'	=> 'params'
			)));


		$this->form->add(new \Library\Form\Elements\Button(array(
			'label'   => \Library\LanguagesManager::get('add_field'),
			'name'	  => 'add',
			'clear'	  => 1,
			'onClick' => 'saveParamsAndHandleFields('.$numberFields.', \'\');'.$jsRedirection
			)));

	}


}
?>