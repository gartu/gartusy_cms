<?php

namespace Library\Entities;

class ContactForm extends \Library\Entity {

	protected $name;
	protected $description;
	protected $receiver;
	protected $fields; // tableau contenant la liste des champs (\Library\Entities\Field.class) supplémentaires, constituant en soit le formulaire résultant


	/**
	 * construit le code html pour ce formulaire
	 * @access public
	 * @param String $action l'action définie dans le formulaire
	 * @return String; le code html du formulaire
	 */
	public function build($action = ''){

		$form = new \Library\Form\Form(new \Library\Entities\ContactForm());
		$form->setAction($action);
		foreach ($this->fields as $field) {
			$form->add($field->generateFormField());
		}

		return $form;
	}

	/**
	 * Récupère le nom du formulaire
	 * @access public
	 * @return string; le nom du formulaire
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Récupère le texte de description du formulaire
	 * @access public
	 * @return string; la description du formulaire
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * Récupère l'adresse mail receptionnant le formulaire
	 * @access public
	 * @return String; l'adresse mail liée au formulaire
	 */
	public function getReceiver() {
		return $this->receiver;
	}


	/**
	 * Récupère les champs constituant le forumulaire
	 * @access public
	 * @return array le tableau contenant les champs du formulaire
	 */
	public function getElements() {
		return $this->fields;
	}


	/**
	 * met à jour le nom du formulaire
	 * @access public
	 * @param string $name;	le nom du formulaire
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * met à jour le mail d'envoie du formulaire
	 * @access public
	 * @param String $receiver, le mail du destinataire
	 * @return void
	 */
	public function setReceiver($receiver) {
		$this->receiver = $receiver;
	}

	/**
	 * met à jour le mail par défaut d'envoie du formulaire
	 * @access public
	 * @param String $receiver, le mail du destinataire
	 * @return void
	 */
	public function setDefaultReceiver($receiver) {
		// on met à jour que si le mail n'a pas été définit
		if(is_null($this->receiver)){
			$this->receiver = $receiver;
		}
	}


	/**
	 * met à jour le texte descriptif du formulaire
	 * @access public
	 * @param String $description le texte descriptif
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * récupère le champs supécifié du formulaire
	 * @access public
	 * @param int $num l'indice du champs désiré
	 * @return le champs de l'indice demandé
	 */
	public function getField($num){
		return $this->fields[$num];
	}

	/**
	 * met à jour la liste complète des champs du formulaire
	 * @access public
	 * @param array $fields un tableau contenant la liste des champs définissant le formulaire (de type \Library\Entities\Field !)
	 * @return void
	 */
	public function setFields(array $fields) {
		$this->fields = $fields;
	}


	/**
	 * Ajoute un champs à la liste déjà définie
	 * @access public
	 * @param Field $field le champs à ajouter
	 * @return void
	 */
	public function addField(\Library\Entities\Field $field) {
		$this->fields[] = $field;
	}



}
?>