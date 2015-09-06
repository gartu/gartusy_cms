<?php

namespace Applications\Backend\Modules\Menu;

class MenuController extends \Library\BackController {


	/**
	 * permet d'effectuer une modification d'une page complète
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * permet d'ajouter une nouvelle page et son menu correspondant
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
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
		$manager = $this->managers->getManagerOf('Menu');

		$menu = $manager->getById($request->getGetData('id'));
		// les contenu de type texte sont "liés" directement au menu, il faut
		// donc le supprimer avec le menu directement
		if($menu->getModule() == 'texte'){
			$this->managers->getManagerOf('SimpleText')->delete($menu->getOptions());
		}

		$manager->deleteMainMenu($request->getGetData('id'));
		$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('delete_completed'));

		$this->app->getHttpResponse()->redirect($this->app->getConfig()->getParam('defaultRedirection'));
	}



	/**
	 * On construit un formulaire pour les catégories, tout est detecté automatiquement (si ajout, modif)
	 * @access protected
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	protected function processForm(\Library\HTTPRequest $request){
		$error = false;
		$menuManager = $this->managers->getManagerOf('Menu');

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {

			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$menu = new \Library\Entities\MainMenu();

			$menu->setName($request->getPostData('MainMenu_name'));
			$menu->setDescription($request->getPostData('MainMenu_description'));
			$menu->setController($request->getPostData('MainMenu_controller'));
			$menu->setModule($request->getPostData('MainMenu_module'));
			$menu->setOptions($request->getPostData('MainMenu_options'));
			$menu->setMetric($request->getPostData('MainMenu_metric'));

			// si on veut modifier un menu, alors l'identifiant est transmit
			if ($request->getPostData('MainMenu_id') != '') {
				$menu->setId($request->getPostData('MainMenu_id'));
				$menuManager->setTextManager($this->managers->getManagerOf('SimpleText'));

			// si on créé un menu contenu du texte simple, on doit ajouter un nouveau contenu texte
			}else if($menu->getModule() == 'texte'){
				$menu->setController('contenu'); // oui je sais c'est dég de faire comme ça.. sera changé en version 7.8.4.21 :-/
				$menu->setOptions($this->managers->getManagerOf('SimpleText')->create());
			}

			$checkboxes = $request->getPostData('checkboxes');
			$menu->setVisible($checkboxes['MainMenu_visible']);
			$menu->setPrivate($checkboxes['MainMenu_private']);

		// on prépare un formulaire
		}else{
			// si on veut modifier un menu, alors l'identifiant est transmit
			if ($request->getExists('id')) {
				// on modifie une page relative à un menu principal
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_menu'));
				$menu = $menuManager->getById($request->getGetData('id'));
	
			// on créé un nouveau menu principal donc on a besoin d'un formulaire vide
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_menu'));
				$menu = new \Library\Entities\MainMenu();
			}
		}
		// on créé notre gestion de formulaire
		$formBuilder = new \Library\Form\Builders\MenuFormBuilder($menu, $menuManager);
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		// on met de base le bouton ajouter, celui-ci ne sera afficher que dans la vue de création, pas dans l'update
		$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");

		$formHandler = new \Library\Form\FormHandler($form, $menuManager, $request);
		if(!$menu->isNew()){
			$formHandler->addLanguageHelp($this->managers);
		}
		
		// s'il n'y a aucune erreur, que le formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			if($menu->isNew()){
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('add_menu_success'));
				$this->app->getHttpResponse()->redirect('page-'.$menuManager->getLastInsertId().'.html');
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_menu_success'));
				// si le module a été modifé, il faut recharger la page
				if($menuManager->moduleHasChanged()){
					$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
				}
			}
		}

		$this->page->addVar('menuForm', $form);

		if (!$menu->isNew()) {
			// on offre une variable d'idMenu afin de permettre d'ajouter un sous-menu relatifs à ce menu
			$this->page->addVar('id', $menu->getId());

			// on a créé la partie supérieur, il reste à créer le formulaire du contenu de la page
			$contentController = $this->app->getController('/admin'.$menu->getURI());

			$contentController->execute();
			$this->page->addVar('content', $contentController->getPage()->getGeneratedContentPage());
			$this->page->addVar('imgEdit', $contentController->module != 'News');
		}
		


	}


}
?>