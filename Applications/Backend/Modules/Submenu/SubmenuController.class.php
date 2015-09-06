<?php

namespace Applications\Backend\Modules\Submenu;

class SubmenuController extends \Library\BackController {


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
		$manager = $this->managers->getManagerOf('Submenu');

		$menu = $manager->getSubById($request->getGetData('id'));
		// les contenu de type texte sont "liés" directement au sous-menu, il faut
		// donc le supprimer avec le menu directement
		if($menu->getModule() == 'texte'){
			$this->managers->getManagerOf('SimpleText')->delete($menu->getOptions());
		}

		$manager->deleteSubmenu($request->getGetData('id'));
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
			$menu = new \Library\Entities\Submenu();

			$menu->setMenuId($request->getPostData('Submenu_menuId'));
			$menu->setName($request->getPostData('Submenu_name'));
			$menu->setDescription($request->getPostData('Submenu_description'));
			$menu->setController($request->getPostData('Submenu_controller'));
			$menu->setModule($request->getPostData('Submenu_module'));
			$menu->setOptions($request->getPostData('Submenu_options'));
			$menu->setMetric($request->getPostData('Submenu_metric'));

			// on récupère les données réceptionnées
			if ($request->getPostData('Submenu_subId') != '') {
				$menu->setId($request->getPostData('Submenu_subId'));
				$menuManager->setTextManager($this->managers->getManagerOf('SimpleText'));

			// si on créé un menu contenu du texte simple, on doit ajouter un nouveau contenu texte
			}else if($menu->getModule() == 'texte'){
				$menu->setController('contenu'); // oui je sais c'est dég de faire comme ça.. sera changé en version 7.8.4.21 :-/
				$menu->setOptions($this->managers->getManagerOf('SimpleText')->create());
			}

			$checkboxes = $request->getPostData('checkboxes');
			$menu->setVisible($checkboxes['Submenu_visible']);
			
		// on prépare un formulaire
		}else{
			// si on veut modifier un sous-menu, alors l'identifiant est transmit
			if ($request->getExists('subId')) {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_submenu'));
				$menu = $menuManager->getSubById($request->getGetData('subId'));

			// on ajoute un sous-menu à un menu existant
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_submenu'));
				$menu = new \Library\Entities\Submenu();
				// on signal que le sous-menu désiré doit être lié à l'id du menu receptionné
				$menu->setMenuId($request->getGetData('id'));
			}
		}
		// on créé notre gestion de formulaire
		$formBuilder = new \Library\Form\Builders\SubmenuFormBuilder($menu, $menuManager);
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		// on met de base je bouton ajouter, celui-ci ne sera afficher que dans la vue de création, pas dans l'update
		$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");

		$formHandler = new \Library\Form\FormHandler($form, $menuManager, $request);
		if(!$menu->isNew()){
			$this->page->addVar('id', $request->getGetData('subId'));
			$formHandler->addLanguageHelp($this->managers);
		}

		// si il n'y a aucune erreur de mot de passe, que le formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			if($menu->isNew()){
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('add_submenu_success'));
				$this->app->getHttpResponse()->redirect('page-'.$request->getGetData('id').'-'.$menuManager->getLastInsertId().'.html');
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_submenu_success'));
				// si le module a été modifé, il faut recharger la page
				if($menuManager->moduleHasChanged()){
					$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
				}
			}
		}

		$this->page->addVar('submenuForm', $form);

		if (!$menu->isNew()) {
			// on a créé la partie supérieur, il reste à créer le formulaire du contenu de la page
			$contentController = $this->app->getController('/admin'.$menu->getURI());
			
			$contentController->execute();
			$this->page->addVar('content', $contentController->getPage()->getGeneratedContentPage());
			$this->page->addVar('imgEdit', $contentController->module != 'News');
		}
		


	}


}
?>