<?php

namespace Library\Entities;

class Field extends \Library\Entity {

	protected $name;
	protected $description;
	protected $help;
	protected $required;
	protected $metric;
	protected $fieldType; // de type \Library\Entities\FieldType.class


	/**
	 * Constructeur appelant la méthode hydrate afin de placer les données dans l'objet
	 * @access public
	 * @param array $data;	tableau contenant les données relative à l'hydratation
	 * @return void
	 */
	public function __construct(array $data = array()) {
		parent::__construct($data);
		$this->fieldType = new \Library\Entities\FieldType();
	}

	/**
	 * Récupère le nom du champs
	 * @access public
	 * @return string; le nom du champs
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * met à jour le nom du champs
	 * @access public
	 * @param string $name;	nom champs
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Récupère l'id du type du champs
	 * @access public
	 * @return string; le type du champs
	 */
	public function getFieldTypeId() {
		return $this->fieldType->getId();
	}


	/**
	 * met à jour l'id du type de champs
	 * @access public
	 * @param int $id;	id du type du champs
	 */
	public function setFieldTypeId($id) {
		$this->fieldType->setId($id);
	}

	/**
	 * Récupère le type du champs, c'est à dire la classe dérivant de \Library\Form\Elements\Element représentée
	 * @access public
	 * @return string; le type du champs
	 */
	public function getFieldType() {
		return $this->fieldType->getType();
	}


	/**
	 * met à jour le type du champs
	 * @access public
	 * @param string $type;	type du champs
	 */
	public function setFieldType($type) {
		$this->fieldType->setType($type);
	}

	/**
	 * Récupère le type du champs, c'est à dire la classe dérivant de \Library\Form\Elements\Element représentée
	 * @access public
	 * @return string; le type du champs
	 */
	public function getFieldTypeName() {
		return $this->fieldType->getName();
	}


	/**
	 * met à jour la description du champs
	 * @access public
	 * @param string $description, la description du champs
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Récupère la description du champs
	 * @access public
	 * @return string; la description du champs
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * met à jour le type du champs
	 * @access public
	 * @param string $type;	type du champs
	 */
	public function setFieldTypeName($name) {
		$this->fieldType->setName($name);
	}

	/**
	 * Récupère l'information si le champs est obligatoire
	 * @access public
	 * @return bool, si le champs est impératif
	 */
	public function getRequired() {
		return $this->required;
	}


	/**
	 * met à jour le champs sur son état impératif
	 * @access public
	 * @param bool si le champs est obligatoire
	 * @return void
	 */
	public function setRequired($required) {
		$this->required = $required;
	}

	/**
	 * Récupère la metric du champs
	 * @access public
	 * @return int la metric du champs
	 */
	public function getMetric() {
		return $this->metric;
	}


	/**
	 * met à jour la metric du champs
	 * @access public
	 * @param int; metric la metric du champs
	 * @return void
	 */
	public function setMetric($metric) {
		$this->metric = $metric;
	}


	/**
	 * Génère un object de champs de formulaire correspondant (\Library\Form\Elements\Field.class)
	 * @access public
	 * @return \Library\Form\Elements\Field.class
	 */
	public function generateFormField() {
		$tmp = array(
			'label'   => $this->name,
			'name'	  => str_replace(' ', '_', $this->name),
			'classes' => 'userFieldForm');
		if($this->required){
			$tmp['validators'] = array(new \Library\Form\Validators\NotNullValidator(\Library\LanguagesManager::get('not_null')));
		}
		
		$type = '\Library\Form\Elements\\'.$this->fieldType->getType();
		return new $type($tmp);
	}



}
?>