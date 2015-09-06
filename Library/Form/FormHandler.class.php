<?php

namespace Library\Form;

class FormHandler {

	protected  $form;
	protected  $manager;
	protected  $request;


	/**
	 * Construit un opérateur de formulaire
	 * @access public
	 * @param Form $form 
	 * @param Manager $manager 
	 * @param HTTPRequest $request 
	 * @return void
	 */
	public function __construct(\Library\Form\Form $form, \Library\Manager $manager, \Library\HTTPRequest $request) {
		$this->setEntity($form);
		$this->setManager($manager);
		$this->setRequest($request);
	}

	/**
	 * Procède à l'execution du formulaire si celui-ci a été envoyé
	 * @access public 
	 * @return bool vrai si on a à faire à un traitement (POST)
	 */
	public function process() {
		if ($this->request->method() == 'POST' && $this->form->isValid()) {
			$entity = $this->form->getEntity();
			$saveFunction = 'save'.ucfirst($entity->getClassName());
			$this->manager->$saveFunction($entity);

			return true;
		}
		return false;
	}


	/**
	 * met à jour le formulaire
	 * @access public
	 * @param Form $form l'entité
	 * @return void
	 */
	public function setEntity(\Library\Form\Form $form) {
		$this->form = $form;
	}


	/**
	 * Met à jour le manager de l'entité
	 * @access public
	 * @param Manager $manager le manager correspondant
	 * @return void
	 */

	public function setManager(\Library\Manager $manager) {
		$this->manager = $manager;
	}


	/**
	 * Met à jour la requête
	 * @access public
	 * @param HTTPRequest $request la requête
	 * @return void
	 */
	public function setRequest(\Library\HTTPRequest $request) {
		$this->request = $request;
	}

	/**
	 * Ajoute des info concernant le contenu dans les autres langues
	 * @access public
	 * @return void
	 */
	public function addLanguageHelp($managers){
		$nl = '&#13;';
		foreach ($this->form->getElements() as $field) {
			if(method_exists($field, 'setAbbr')){
			$abbr = '';
			$name = explode('_', $field->getName());
			$manager = $managers->getManagerOf($name[0]);
			$method = 'get'.ucfirst($name[count($name)-1]);
			foreach (\Library\LanguagesManager::getLanguages() as $abbreviation) {
				if($abbreviation == LANGUAGE){
					continue;
				}
				// on récupère le contenu d'une autre langue
				if(method_exists($manager, 'getById')){
					$ref = $this->form->getEntity();

					// exception pour les sous-menus.. je sais c'est dégueu.. :-/ tout ça à cause d'un manager pour mainmenu et submenu..
					if($name[0] == 'Submenu'){
						$entity = $manager->getSubById($ref->getId(), $abbreviation);
					}else{
						$entity = $manager->getById($ref->getId(), $abbreviation);
					}
					if(method_exists($entity, $method)){
						// on n'affiche l'aide que si le contenu est différent (pas besoin pour les nbre, par ex.)
						if($entity->$method() != $ref->$method()){
							$abbr .= $abbreviation.': '.$entity->$method().$nl;
						}
					}else{
						break;
					}
				}else{
					break;
				}
			}
			$field->setAbbr(substr($abbr, 0, -strlen($nl)));
		}}
	}

}
?>