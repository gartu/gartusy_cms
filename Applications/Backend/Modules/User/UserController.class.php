<?php

namespace Applications\Backend\Modules\User;

class UserController extends \Library\BackController {


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */

	public function executeIndex(\Library\HTTPRequest $request) {
		$limitUsers = $this->app->getConfig()->getParam('limitUsers');
		// si aucun numéro de page n'est demandé, alors on affiche la première

		$lastPage = $request->getGetData('page');
		if ($lastPage > 0) {
			$lastPage--;
		}

		$this->page->addVar('title', \Library\LanguagesManager::get('title_user_list'));

		$manager = $this->managers->getManagerOf('User'); 
		$usersList = $manager->getList($this->managers->getManagerOf('Category'), $lastPage*$limitUsers, $limitUsers);

		$this->page->addVar('usersList', $usersList);
	}


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */
	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */
	public function executeInsert(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * On construit un formulaire pour les News, tout est detecté automatiquement (si ajout, modif)
	 * @access protected
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	protected function processForm(\Library\HTTPRequest $request){
		$error = false;
		$categoryManager = $this->managers->getManagerOf('Category');
		$userManager = $this->managers->getManagerOf('User');

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {
			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$user = new \Library\Entities\User(array(
				'login' => $request->getPostData('User_login'),
				'name' => $request->getPostData('User_name'),
				'surname' => $request->getPostData('User_surname'),
				'mail' => $request->getPostData('User_mail')
				//'category' => $categoryManager->getById($request->getPostData('categoryId'))
				));

			$password = $request->getPostData('User_password');

			// c'est une mise à jour
			if ($request->getExists('id')) {
				$user->setId($request->getGetData('id'));

				// si un login similaire à notre modification existe déjà, alors on ne peut pas executer la requête
				if ($userManager->issetLogin($user->getLogin(), $user->getId())) {
					$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('existing_login'));
					$error = true;				
				}
			}else if ($userManager->issetLogin($user->getLogin())) {
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('existing_login'));
				$error = true;				
			}

			if (!empty($password)) {
				// les deux mots de passe correspondent
				if ($password === $request->getPostData('User_passwordConfirm')) {
					// ici il est important d'envoyer le sel avant de générer le hash
					$salt = rand();
					$user->setSalt($salt);
					$user->generateHash($password);
				// les deux mot de passes ne correspondent pas
				}else{
					$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('pwd_not_identical'));
					$error = true;
				}
			// l'utilisateur n'a pas rentré de mot de passe lors de la création
			}else if ($user->isNew()) {
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('pwd_required'));
				$error = true;
			// on ne change pas le mdp, alors on lui attribue son précédent
			}else{
				$treatingUser = $userManager->getByLogin($user->getId(), $categoryManager);
				$user->setSalt($treatingUser->getSalt());
				$user->setPassword($treatingUser->getPassword());
			}

			// on vérifie le login, chaque login est unique


		// on prépare un formulaire
		}else{
			// si on veut modifier l'utilisateur, alors l'identifiant est transmit
			if ($request->getExists('id')) {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_user'));
				$user = $userManager->getByLogin($request->getGetData('id'), $categoryManager);
			// on créé un formulaire vide
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_user'));
				$user = new \Library\Entities\User();
			}
		}
		
		// on créé notre gestion de formulaire
		$formBuilder = new \Library\Form\Builders\UserFormBuilder($user, $categoryManager);
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		$form->setId("mainForm");

		$formHandler = new \Library\Form\FormHandler($form, $userManager, $request);

		// si il n'y a aucune erreur de mot de passe, que le formulaire a été envoyé via POST et qu'il est valide
		if (!$error && $formHandler->process()) {
			if($user->isNew()){
				$message = \Library\LanguagesManager::get('add_user_success');
				$this->app->getCurrentUser()->setAttribute('message', $message);
			}else{
				$message = \Library\LanguagesManager::get('modify_user_success');
				$this->app->getCurrentUser()->setAttribute('message', $message);
				// on a modifié le nom du login
				if($user->getId() != $user->getLogin()){
					$currentUser = $this->app->getCurrentUser();
					if($currentUser->getUser()->getLogin() == $user->getId()){
						$currentUser->hydrate($user);
					}
					$this->app->getHttpResponse()->redirect($this->app->getConfig()->getParam('defaultRedirection'));
				}
			} 
		}

		$this->page->addVar('form', $form->generate());

	}

}
?>