<?php

namespace Applications\Backend\Modules\SimpleText;

class SimpleTextController extends \Library\BackController {


	/**
	 * Affiche l'intégralité des news, selon la page
	 * @access public
	 * @param \Library\HTTPRequest $request la requête du client
	 */
	public function executeIndex(\Library\HTTPRequest $request) {
		$this->page->addVar('title', \Library\LanguagesManager::get('title_text_list'));

		$displayedObject = $this->app->getConfig()->getParam('admNumberDisplayedObject');

		$textManager = $this->managers->getManagerOf('SimpleText');
		$textList = $textManager->getList();
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		// s'il y a plus de textes que le nombre max affiché alors on doit les séparer
		if ($displayedObject < count($textList)) {
			$textList = $textManager->getList($page*$displayedObject, $displayedObject);
		}else{
			$textList = $textManager->getList();
		}

		// on créé la liste de news en tant que variable de page pour la récupérer dans la vue
		$this->page->addVar('textList', $textList);
	}


	/**
	 * affiche la page de modification d'une news, celle d'ajout si l'id est invalide
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */
	public function executeShow(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * affiche la page de modification d'une news, celle d'ajout si l'id est invalide
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */

	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * Permet d'ajouter une news
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

		$manager = $this->managers->getManagerOf('SimpleText');
		$manager->delete($request->getGetData('id'));
		$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('delete_completed'));

		$this->app->getHttpResponse()->redirect($this->app->getConfig()->getParam('defaultRedirection'));
	}


	/**
	 * On construit un formulaire pour les News, tout est detecté automatiquement (si ajout, modif)
	 * @access protected
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	protected function processForm(\Library\HTTPRequest $request){
		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {
			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$tmp = $request->getPostData('checkboxes');
			$private = $tmp['SimpleText_private'];
			$text = new \Library\Entities\SimpleText(array(
				'content' => $request->getPostData('SimpleText_content'),
				'private' => $private
				));
			if ($request->getGetData('id') != '') {
				$text->setId($request->getGetData('id'));
			}
		// on prépare un formulaire
		}else{
			// si on veut modifier le texte, alors l'identifiant est transmit
			if ($request->getGetData('id') != '') {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_text'));
				$text = $this->managers->getManagerOf('SimpleText')->getById($request->getGetData('id'));
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_text'));
				$text = new \Library\Entities\SimpleText();
			}
		}
		
		$formBuilder = new \Library\Form\Builders\SimpleTextFormBuilder($text);
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		$form->setSubmit(\Library\LanguagesManager::get('add'));

		$formHandler = new \Library\Form\FormHandler($form, $this->managers->getManagerOf('SimpleText'), $request);
		if(!$text->isNew()){
			$formHandler->addLanguageHelp($this->managers);
		}

		// si un formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			$message = $text->isNew() ? \Library\LanguagesManager::get('add_text_success') : \Library\LanguagesManager::get('modify_text_success');
			$this->app->getCurrentUser()->setAttribute('message', $message);
			
			// Placer la redirection ici si désiré
		}
		$this->page->addVar('action', $this->parentURI);

		$this->page->addVar('form', $form);

	}


}
?>