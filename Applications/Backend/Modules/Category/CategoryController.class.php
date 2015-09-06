<?php

namespace Applications\Backend\Modules\Category;

class CategoryController extends \Library\BackController {


	/**
	 * permet d'effectuer une modification d'une catégorie
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * permet d'ajouter un nouvel élément 
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeInsert(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * permet d'afficher la liste des catégories
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request) {

		$limitUsers = $this->app->getConfig()->getParam('limitCategories');
		// si aucun numéro de page n'est demandé, alors on affiche la première

		$lastPage = $request->getGetData('page');
		if ($lastPage > 0) {
			$lastPage--;
		}

		$this->page->addVar('title', \Library\LanguagesManager::get('title_category_list'));

		$manager = $this->managers->getManagerOf('Category'); 
		$categoriesList = $manager->getList($lastPage*$limitUsers, $limitUsers);

		$this->page->addVar('categoriesList', $categoriesList);
	}


	/**
	 * On construit un formulaire pour les catégories, tout est detecté automatiquement (si ajout, modif)
	 * @access protected
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	protected function processForm(\Library\HTTPRequest $request){
		$error = false;
		$categoryManager = $this->managers->getManagerOf('Category');

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {
			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$category = new \Library\Entities\Category(array(
				'name' 			=> $request->getPostData('Category_name'),
				'description' 	=> $request->getPostData('Category_description'),
				'rights'		=> $request->getPostData('checkboxes'),
				'rightsId'		=> $request->getPostData('rightsId')
			));

			// c'est une mise à jour
			if ($request->getExists('id')) {
				$category->setId($request->getGetData('id'));
			}

		// on prépare un formulaire
		}else{
			// si on veut modifier la catégorie, alors l'identifiant est transmit
			if ($request->getExists('id')) {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_category'));
				$category = $categoryManager->getById($request->getGetData('id'));
			// on créé un formulaire vide
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_category'));
				// on créé une catégorie vide avec un tableau de droit nul
				$category = new \Library\Entities\Category();
				$category->setRights($categoryManager->getRightsList());
			}
		}
		
		// on créé notre gestion de formulaire
		$formBuilder = new \Library\Form\Builders\CategoryFormBuilder($category);
		$formBuilder->build();

		$form = $formBuilder->getForm();

		$formHandler = new \Library\Form\FormHandler($form, $categoryManager, $request);
		if(!$category->isNew()){
			$formHandler->addLanguageHelp($this->managers);
		}

		// si il n'y a aucune erreur de mot de passe, que le formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			$message = $category->isNew() ? \Library\LanguagesManager::get('add_category_success') : \Library\LanguagesManager::get('modify_category_success');
			$this->app->getCurrentUser()->setAttribute('message', $message);
		}

		$this->page->addVar('form', $form->generate());

	}


}
?>