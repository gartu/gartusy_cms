<?php

namespace Applications\Frontend\Modules\User;

class UserController extends \Library\BackController {


	/**
	 * Execute l'action Show du module User
	 * @access public
	 * @param HTTPRequest $request la requête utilisateur
	 * @return void
	 */
	public function executeShow(\Library\HTTPRequest $request) {

		$this->page->addVar('title', \Library\LanguagesManager::get('title_user_info'));

		$manager = $this->managers->getManagerOf('User');

		$user = $manager->getById($request->getGetData('id'), $this->managers->getManagerOf('Category'));
		
		$this->page->addVar('user', $user);
	}


	/**
	 * Execute l'action Index du module User
	 * @access public
	 * @param HTTPRequest $request la requête utilisateur
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
	 * Execute l'action updatePassword du module User
	 * @access protected
	 * @param HTTPRequest $request la requête utilisateur
	 * @return void
	 */
	protected function executeUpdatePassword(\Library\HTTPRequest $request) {
		// si l'utilisateur n'est pas connecté, alors on le revoie au login
		if (!$this->app->getCurrentUser()->isLogged()) {
   			$redirection = $this->app->getConfig()->getParam('redirectToLogin');
			$this->app->getHttpResponse()->redirect($redirection);
		}
		$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_pwd'));

		// on utilise l'utilisateur courrant
		$user = $this->app->getCurrentUser()->getUser();
		$manager = $this->managers->getManagerOf('User');
		$pwdError = false;

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {

			$password = $request->getPostData('password');
			$currentPassword = $request->getPostData('currentPassword');

			// les deux mots de passe correspondent et ne sont pas vides
			if (!empty($password) && $password === $request->getPostData('passwordConfirm')) {

				// on va vérifier si le mot de passe entré en juste
				$verificationUser = $manager->getById($user->getId(), $this->managers->getManagerOf('Category'));

				// si le mot de passe est correcte alors on peut effectuer la modification
				if ($verificationUser->getPassword() == sha1($currentPassword.$verificationUser->getSalt())) {
					// ici il est important d'envoyer le sel avant de générer le hash
					$salt = rand();
					$user->setSalt($salt);
					$user->generateHash($password);					
				// le mot de passe entré par l'utilisateur est erroné
				}else{
					$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('wrong_pwd'));
					$pwdError = true;
				}
			// les deux mot de passes ne correspondent pas ou on été laissé vide
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('pwd_not_identical'));
				$pwdError = true;
			}
		}
		
		// on créé notre gestion de formulaire
		$formBuilder = new \Library\Form\Builders\PasswordFormBuilder($user);
		$formBuilder->build();

		$form = $formBuilder->getForm();

		$formHandler = new \Library\Form\FormHandler($form, $manager, $request);

		// si il n'y a aucune erreur de mot de passe, que le formulaire a été envoyé via POST et qu'il est valide
		if (!$pwdError && $formHandler->process()) {
			$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_pwd_success'));
		}

		$this->page->addVar('form', $form->generate());

	}

}
?>