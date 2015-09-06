<?php

namespace Library\Form\Builders;

abstract class FormBuilder {

	protected  $form;

	/**
	 * on créer un nouveau formulaire du type désiré
	 * @access public
	 * @param Entity $entity le type de formulaire désiré
	 * @return void
	 */
	public function __construct(\Library\Entity $entity) {
		$this->setForm(new \Library\Form\Form($entity));
	}


	/**
	 * permet de créer un formulaire type
	 * @access public
	 * @return void
	 */
	public abstract function build() ;

	/**
	 * permet de récupérer le formulaire
	 * @access public
	 * @return Form le formulaire
	 */
	public function getForm() {
		return $this->form;
	}


	/**
	 * met à jour le formulaire
	 * @access public
	 * @param Form $form le formulaire à prendre
	 * @return void
	 */
	public function setForm(\Library\Form\Form $form) {
		$this->form = $form;
	}

	/**
	 * ajoute un préfixe à tous les noms du formulaire
	 * @access public
	 * @param String $prefix le préfixe à ajouter
	 * @return void
	 */
	public function addNamePrefix($prefix) {
		$fields = $this->form->getElements();
		foreach ($fields as $field) {
			$field->setName($prefix.$field->getName());
		}
	}



}
?>