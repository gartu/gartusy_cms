<?php

namespace Applications\Backend\Modules\Files;

class FilesController extends \Library\BackController {


	/**
	 * permet d'effectuer une modification sur des fichiers
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeUpdate(\Library\HTTPRequest $request) {
		$this->processForm($request);
	}


	/**
	 * permet d'ajouter de nouveaux fichiers sur le serveur
	 * @access public
	 * @param HTTPRequest $request la requête de l'utilisateur
	 * @return void
	 */
	public function executeInsert(\Library\HTTPRequest $request) {
		$directory = $this->app->getConfig()->getParam('filesDirectory');
		$filesManager = $this->managers->getManagerOf('Files');

		if(isset($_FILES['File_upload'])){
			// premièrement on vérifie l'extension du fichier
			$tmp = explode('.', $_FILES['File_upload']['name']);
			$ext = strtolower($tmp[count($tmp)-1]);
			$file = new \Library\Entities\File();
			$file->setName($_FILES['File_upload']['name']);
		}

		$formBuilder = new \Library\Form\Builders\FileFormBuilder(new \Library\Entities\File(array('name' => '', 'directory' => '')), $directory);
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		//$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");

		$formHandler = new \Library\Form\FormHandler($form, $filesManager, $request);

		// si un formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			if(in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){
				$this->managers->getManagerOf('Picture')->placeImage($_FILES['File_upload']['tmp_name'], $directory.$_FILES['File_upload']['name'], 1000);
			}else{
				$filesManager->move($_FILES['File_upload']['tmp_name'], $directory.$_FILES['File_upload']['name']);
			}
			$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('add_file_success'));
		}
		$this->page->addVar('form', $form);

	}


	/**
	 * @access public
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	public function executeDelete(\Library\HTTPRequest $request) {
		$filesManager = $this->managers->getManagerOf('Files');
		$list = $filesManager->getList();
		$filesManager->delete($list[$request->getGetData('num')]->getURL());
	
		$this->app->getHttpResponse()->redirect('manage-files.html');
	}


	/**
	 * On construit un formulaire pour les catégories, tout est detecté automatiquement (si ajout, modif)
	 * @access protected
	 * @param \Library\HTTPRequest $request 
	 * @return void
	 */
	protected function processForm(\Library\HTTPRequest $request){
		$filesManager = $this->managers->getManagerOf('Files');
		
		$directory = $this->app->getConfig()->getParam('filesDirectory');
		
		if ($request->method() == 'POST') {
			$list = array();

			for($i=0; $i<count($_POST); $i++) {
				if(!isset($_POST['File_'.$i.'_name'])){
					break;
				}
				$list[] = new \Library\Entities\File(array(
							'name' 		=> $_POST['File_'.$i.'_name'],
							'shortName' => $_POST['File_'.$i.'_shortName'],
							'directory' => $directory
							));
			}
		}else{
			$list = $filesManager->getList();
		}

		$htmlForm = '';
		$redirect = false;
		$form = null;
		for($i = 0; $i < count($list); $i++) { 
			$formBuilder = new \Library\Form\Builders\FileFormBuilder($list[$i], $directory);
			$formBuilder->build();

			$form = $formBuilder->getForm();
			$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_'.$i.'_');

			$formHandler = new \Library\Form\FormHandler($form, $filesManager, $request);

			// si un formulaire a été envoyé via POST et qu'il est valide
			if ($formHandler->process()) {
				$redirect = true;
				$message = \Library\LanguagesManager::get('modify_file_success');
				$this->app->getCurrentUser()->setAttribute('message', $message);
			}

			$htmlForm .= '<a class="file" href="/Files/'.$list[$i]->getName().'" target="_blank">'.$list[$i]->getName().'</a>'.$form->generate().
						'<button type="button" class="deleteButton file" onclick="
							if(confirm(\''.\Library\LanguagesManager::get('delete_confirm').'\')){
							self.location.href=\'delete-file-'.$i.'.html\'}">
							'.\Library\LanguagesManager::get('delete_button').'
						 </button><hr class="clear"/>';
		}
		if($redirect){	
			$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
		}
		if(count($list) > 0){
			$form->setId("mainForm");
			$htmlForm = $form->generateHeader().$htmlForm.$form->generateFooter();
		}
		$this->page->addVar('htmlForm', $htmlForm);
	}


}
?>