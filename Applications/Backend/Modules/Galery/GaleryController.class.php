<?php

namespace Applications\Backend\Modules\Galery;

class GaleryController extends \Library\BackController {



	/**
	 * Affiche l'intégralité des news, selon la page
	 * @access public
	 * @param \Library\HTTPRequest $request la requête du client
	 */
	public function executeIndex(\Library\HTTPRequest $request) {

		$galeryManager = $this->managers->getManagerOf('Galery');
		$galeries = $galeryManager->getList();
		$this->page->addVar('galeries', $galeries);
	}


	/**
	 * affiche la page de modification d'un formulaire de contact, celle d'ajout si l'id est invalide
	 * @access public
	 * @param \Library\HTTPRequest $request la requête receptionnée
	 * @return void
	 */
	public function executeShow(\Library\HTTPRequest $request) {

		$galeryManager = $this->managers->getManagerOf('Galery');
		$galeries = $galeryManager->getList();
		$this->page->addVar('galeries', $galeries);
		$this->page->addVar('selected', $request->getExists('idGalery') ? $request->getGetData('idGalery') : '');

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
		$manager = $this->managers->getManagerOf('Galery');
		$pictureManager = $this->managers->getManagerOf('Picture');
  		$pictureManager->setImageDirectory($this->app->getConfig()->getParam('urlImage'));
  		$pictureManager->setThumbnailsDirectory($this->app->getConfig()->getParam('urlThumbnail'));

		$manager->setPictureManager($pictureManager);
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
		
		$pictureManager = $this->managers->getManagerOf('Picture');
		$galeryManager = $this->managers->getManagerOf('Galery');
		$galeryManager->setPictureManager($pictureManager);

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {
			$pictureManager->setImageDirectory($this->app->getConfig()->getParam('urlImage'));
			$pictureManager->setThumbnailsDirectory($this->app->getConfig()->getParam('urlThumbnail'));

			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$galery = new \Library\Entities\Galery(array(
				'title'   	  => $request->getPostData('Galery_title'),
				'description' => $request->getPostData('Galery_description')
				));

			if ($request->postExists('Galery_id')) {
				$galery->setId($request->getPostData('Galery_id'));
			}

			// on ajoute le contenu de tous les champs
			$numPicture = $request->getPostData('Galery_numberPicture');

			for ($i = 0; $i < $numPicture; $i++) {
				// si l'image n'a pas été supprimée
				if($request->getPostData('Galery_suppression'.$i) == 0){
					// on construit l'image à ajouter 
					$picture = new \Library\Entities\Picture();
					if(!is_null($request->getPostData('Galery_pictureId'.$i))){
						$picture->setId($request->getPostData('Galery_pictureId'.$i));
					}
					$picture->setName($request->getPostData('Galery_pictureName'.$i));
					$picture->setDescription($request->getPostData('Galery_pictureDescription'.$i));
					$picture->setFormat($request->getPostData('Galery_pictureFormat'.$i));

					// on ajoute le champs au formulaire
					$galery->addPicture($picture);
				}
			}
			if(isset($_FILES['Galery_upload'])){
				// premièrement on vérifie l'extension du fichier
				$tmp = explode('.', $_FILES['Galery_upload']['name']);
				$ext = strtolower($tmp[count($tmp)-1]);
				if(in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){
					$picture = new \Library\Entities\Picture();
					$picture->setName($_FILES['Galery_upload']['name']);
					$picture->setDescription('');

					// seul le format jpeg est reconnu 
					$ext = (($ext == 'jpg') ? 'jpeg' : $ext);
					$picture->setFormat($ext);

					$galery->addPicture($picture);
				}
			}
		// si on veut modifier un formulaire déjà existant, alors l'identifiant est transmit
		}else if ($request->getExists('idGalery')) {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_galery'));
				$galery = $galeryManager->getById($request->getGetData('idGalery'));
		// on prépare un formulaire vide
		}else{
			$this->page->addVar('title', \Library\LanguagesManager::get('title_add_galery'));
			$galery = new \Library\Entities\Galery();
		}

		// on placera des aperçu des images dans la gestion de la galerie, alors on doit transmettre l'emplacement de celles-ci au constructeur de formulaire
		$formBuilder = new \Library\Form\Builders\GaleryFormBuilder($galery, $this->app->getConfig()->getParam('urlThumbnail'));
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");
		
		$formHandler = new \Library\Form\FormHandler($form, $galeryManager, $request);
		if(!$galery->isNew()){
			$this->page->addVar('id', $request->getGetData('idGalery'));
			$formHandler->addLanguageHelp($this->managers);
		}

		// si un formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			if(isset($_FILES['Galery_upload']) && in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){
				// premièrement on vérifie l'extension du fichier
				// si tout est ok on ajoute l'image miniature
				$pictureManager->resizeImage($_FILES['Galery_upload']['tmp_name'], $this->app->getConfig()->getParam('urlThumbnail').$pictureManager->getLastInsertPictureId().'.'.$ext, 200, 200);
				// et on déplace l'originale
				$pictureManager->movePicture($_FILES['Galery_upload']['tmp_name'], $this->app->getConfig()->getParam('urlImage').$pictureManager->getLastInsertPictureId().'.'.$ext);
			}
			if($galery->isNew()){
				$this->app->getHttpResponse()->redirect('galery-'.$galeryManager->getLastInsertId().'.html');
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_galery_success'));
				$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
			}
		}else if(isset($_FILES['Galery_upload']) && in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){
			$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
		}
		$this->page->addVar('form', $form);
	}

}
?>