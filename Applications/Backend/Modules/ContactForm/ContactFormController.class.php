<?php

namespace Applications\Backend\Modules\ContactForm;

class ContactFormController extends \Library\BackController {



	/**
	 * Affiche les différents formulaire et permet de les modifier
	 * @access public
	 * @param \Library\HTTPRequest $request la requête du client
	 */
	public function executeIndex(\Library\HTTPRequest $request) {		
		$contactFormManager = $this->managers->getManagerOf('ContactForm');
		$forms = $contactFormManager->getList();
		$this->page->addVar('forms', $forms);
	}


	/**
	 * affiche la page de modification d'un formulaire de contact, celle d'ajout si l'id est invalide
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */
	public function executeShow(\Library\HTTPRequest $request) {

		$contactFormManager = $this->managers->getManagerOf('ContactForm');
		$forms = $contactFormManager->getList();
		$this->page->addVar('forms', $forms);
		$this->page->addVar('selected', $request->getExists('idForm') ? $request->getGetData('idForm') : '');

		// permet de pouvoir impacter sur le menu / sous-menu selon un champs inferieur, ainsi il est possible
		// de modifier le champs options sur lequel pointe le menu selon le contexte définit par le formulaire de contact
		$tmp = $this->app->getRouter()->getRoute($request->getRequestURI())->getModule();
		$tmp = ($tmp === 'Menu') ? 'MainMenu' : $tmp;
		$this->page->addVar('typeMenu', $tmp);
	}


	/**
	 * affiche la page de modification d'un formulaire de contact, celle d'ajout si l'id est invalide
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */

	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * Permet d'ajouter un formulaire de contact
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */

	public function executeInsert(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * @access public
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	public function executeDelete(\Library\HTTPRequest $request) {
		$manager = $this->managers->getManagerOf('ContactForm');
		$manager->delete($request->getGetData('id'));
		$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('delete_completed'));

		$this->app->getHttpResponse()->redirect($this->app->getConfig()->getParam('defaultRedirection'));
	}



	/**
	 * On construit un formulaire pour la création de formulaire de contact, tout est detecté automatiquement (si ajout, modif)
	 * @access protected
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	protected function processForm(\Library\HTTPRequest $request){
		$contactFormManager = $this->managers->getManagerOf('ContactForm');
		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {
			
			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$contactForm = new \Library\Entities\ContactForm(array(
				'name'   	  => $request->getPostData('ContactForm_name'),
				'description' => $request->getPostData('ContactForm_description'),
				'receiver' 	  => $request->getPostData('ContactForm_receiver')
				));
			// on ajoute le contenu de tous les champs
			$checkboxes = $request->getPostData('checkboxes');
			$i = 0;
			$field;
			foreach ($checkboxes as $key => $value) {
				// on construit le champs à ajouter
				$field = new \Library\Entities\Field();
				if(!is_null($request->getPostData('ContactForm_fieldId'.$i))){
					$field->setId($request->getPostData('ContactForm_fieldId'.$i));
				}
				$field->setName($request->getPostData('ContactForm_nameField'.$i));
				$field->setDescription($request->getPostData('ContactForm_helpMessage'.$i));
				$field->setMetric($request->getPostData('ContactForm_metric'.$i));
				$field->setFieldTypeId($request->getPostData('ContactForm_fieldType'.$i));
				$field->setRequired($value);

				// on ajoute le champs au formulaire
				$contactForm->addField($field);
				$i += 1;
			}

			if ($request->postExists('ContactForm_id')) {
				$contactForm->setId($request->getPostData('ContactForm_id'));
			}

		// on prépare un formulaire
		}else{
			// si on veut modifier lun formulaire déjà existant, alors l'identifiant est transmit
			if ($request->getExists('idForm')) {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_contact_form'));
				$contactForm = $contactFormManager->getById($request->getGetData('idForm'));
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_contact_form'));
				$contactForm = new \Library\Entities\ContactForm();
			}
		}
		
		$formBuilder = new \Library\Form\Builders\ContactFormFormBuilder($contactForm, $contactFormManager);
		// si on envoie via POST, on veut pas d'interférence avec les paramètres GET
		if($request->getExists('params') && $request->method() != 'POST') {
			$formBuilder->build($request->getGetData('params'));
		}else{
			$formBuilder->build();
		}


		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");
		
		$formHandler = new \Library\Form\FormHandler($form, $contactFormManager, $request);
		if(!$contactForm->isNew()){
			$this->page->addVar('id', $request->getGetData('idForm'));
			$formHandler->addLanguageHelp($this->managers);
		}

		// si un formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {			
			if($contactForm->isNew()){
				$this->app->getHttpResponse()->redirect('formulaire-'.$contactFormManager->getLastInsertId().'.html');
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_contact_form_success'));
			}

			// Placer la redirection ici si désiré
		}
		$this->page->addVar('form', $form);

	}


}
?>