<?php

namespace Applications\Backend\Modules\NewsSubject;

class NewsSubjectController extends \Library\BackController {



	/**
	 * affiche la page de modification d'un sujet de news, celle d'ajout si l'id est absent
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */

	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * affiche la page de modification des sujets de news
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */

	public function executeIndex(\Library\HTTPRequest $request) {
		$subjects = array();
		$newsSubjectManager = $this->managers->getManagerOf('NewsSubject');
		$originSubjects = $newsSubjectManager->getList();
		if(count($originSubjects) == 0){
			$this->app->getHttpResponse()->redirect('insert-news-category.html');
		}

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {
			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			for ($i = 0; $i < count($originSubjects); $i++) {				
				$newsSubject = new \Library\Entities\NewsSubject(array(
				'name' => $request->getPostData('NewsSubject_'.$i.'_name'),
				'id'   => $request->getPostData('NewsSubject_'.$i.'_id')
				));

				$subjects[] = $newsSubject;
			}

		// on prépare un formulaire
		}else{
			$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_news_subject'));
			$subjects = $originSubjects;
		}
		
		$htmlForm = '';
		for ($i = 0; $i < count($originSubjects); $i++) { 
			$formBuilder = new \Library\Form\Builders\NewsSubjectFormBuilder($subjects[$i]);
			$formBuilder->build();

			$form = $formBuilder->getForm();
			$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_'.$i.'_');

			$formHandler = new \Library\Form\FormHandler($form, $newsSubjectManager, $request);
			$formHandler->addLanguageHelp($this->managers);

			// si un formulaire a été envoyé via POST et qu'il est valide
			if ($formHandler->process()) {
				$message = \Library\LanguagesManager::get('modify_news_subject_success');
				$this->app->getCurrentUser()->setAttribute('message', $message);
			}

			$htmlForm .= $form->generate().
						'<button type="button" class="deleteButton newsSubject" onclick="
							if(confirm(\''.\Library\LanguagesManager::get('delete_confirm').'\')){
							self.location.href=\'delete-news-subject-'.$subjects[$i]->getId().'.html\'}">
							'.\Library\LanguagesManager::get('delete_button').'
						</button>';
		}

		$form->setId("mainForm");
		$htmlForm = $form->generateHeader().$htmlForm.$form->generateFooter();
		$this->page->addVar('htmlForm', $htmlForm);

	}


	/**
	 * Permet d'ajouter un sujet de news
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
		$pictureManager = $this->managers->getManagerOf('Picture');
		$pictureManager->setImageDirectory($this->app->getConfig()->getParam('urlImage'));
		$pictureManager->setThumbnailsDirectory($this->app->getConfig()->getParam('urlThumbnail'));

		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);

		$manager = $this->managers->getManagerOf('NewsSubject');
		$manager->delete($request->getGetData('id'), $newsManager);
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
			$newsSubject = new \Library\Entities\NewsSubject(array(
				'name' => $request->getPostData('NewsSubject_name')
				));

			if ($request->postExists('NewsSubject_id')) {
				$newsSubject->setId($request->getPostData('NewsSubject_id'));
			}

		// on prépare un formulaire
		}else{
			// si on veut modifier les sujets de news, alors l'identifiant est transmit
			if ($request->getExists('id')) {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_news_subject'));
				$newsSubject = $this->managers->getManagerOf('NewsSubject')->getById($request->getGetData('id'));
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_news_subject'));
				$newsSubject = new \Library\Entities\NewsSubject();
			}
		}
		
		$formBuilder = new \Library\Form\Builders\NewsSubjectFormBuilder($newsSubject);
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");

		$formHandler = new \Library\Form\FormHandler($form, $this->managers->getManagerOf('NewsSubject'), $request);
		if(!$newsSubject->isNew()){
			$formHandler->addLanguageHelp($this->managers);
		}

		// si un formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			if($newsSubject->isNew()){
				$this->app->getHttpResponse()->redirect('modify-news-category.html');
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_news_subject_success'));
			}			
		}

		$this->page->addVar('form', $form);

	}


}
?>