<?php

namespace Applications\Backend\Modules\News;

class NewsController extends \Library\BackController {



	/**
	 * Affiche l'intégralité des news, selon la page
	 * @access public
	 * @param \Library\HTTPRequest $request la requête du client
	 */
	public function executeIndex(\Library\HTTPRequest $request) {
		$this->page->addVar('title', \Library\LanguagesManager::get('title_news_list'));

		$displayedObject = $this->app->getConfig()->getParam('admNumberDisplayedObject');
		
		$pictureManager = $this->managers->getManagerOf('Picture');
		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);

		$newsList = $newsManager->getList();
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		// s'il y a plus de news que le nombre max affiché alors on doit les séparer
		if ($displayedObject < count($newsList)) {
			$newsList = $newsManager->getList($page*$displayedObject, $displayedObject);
		}else{
			$newsList = $newsManager->getList();
		}

		// on créé la liste de news en tant que variable de page pour la récupérer dans la vue
		$this->page->addVar('newsList', $newsList);
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
		$manager = $this->managers->getManagerOf('News');
		$picManager = $this->managers->getManagerOf('Picture');
		$picManager->setImageDirectory($this->app->getConfig()->getParam('urlImage'));
		$picManager->setThumbnailsDirectory($this->app->getConfig()->getParam('urlThumbnail'));
		$manager->setPictureManager($picManager);

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

		$pictureManager = $this->managers->getManagerOf('Picture');
		$newsManager = $this->managers->getManagerOf('News');
		$newsManager->setPictureManager($pictureManager);

		// on receptionne un formulaire POST
		if ($request->method() == 'POST') {

			$pictureManager->setImageDirectory($this->app->getConfig()->getParam('urlImage'));
			$pictureManager->setThumbnailsDirectory($this->app->getConfig()->getParam('urlThumbnail'));

			$checkboxes = $request->getPostData('checkboxes');

			$this->page->addVar('title', \Library\LanguagesManager::get('treatment'));
			$news = new \Library\Entities\News(array(
				'visible'   => $checkboxes['News_visible'],
				'title'   => $request->getPostData('News_title'),
				'content' => $request->getPostData('News_content'),
				'subjectId' => $request->getPostData('News_newsSubjectId')
				));

			if ($request->getPostData('News_id') != '') {
				$news->setId($request->getPostData('News_id'));
			}
			
			// si l'image a été supprimée alors on ne l'ajoute pas à la news
			if($request->getPostData('News_suppression') == '0'){
				// on construit l'image à ajouter 
				$picture = new \Library\Entities\Picture();
				if(!is_null($request->getPostData('News_pictureId'))){
					$picture->setId($request->getPostData('News_pictureId'));
				}
				$picture->setName($request->getPostData('News_pictureName'));
				$picture->setDescription('');
				$picture->setFormat($request->getPostData('News_pictureFormat'));

				// on ajoute le champs au formulaire
				$news->setPicture($picture);

			// si on ajoute une image à la news
			}else if(isset($_FILES['News_upload'])){
				// premièrement on vérifie l'extension du fichier
				$tmp = explode('.', $_FILES['News_upload']['name']);
				$ext = strtolower($tmp[count($tmp)-1]);
				if(in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){
					$picture = new \Library\Entities\Picture();
					$picture->setName($_FILES['News_upload']['name']);
					$picture->setDescription('');

					// seul le format jpeg est reconnu 
					$ext = (($ext == 'jpg') ? 'jpeg' : $ext);
					$picture->setFormat($ext);

					$news->setPicture($picture);
				}
			}

		// on prépare un formulaire
		}else{
			if(count($this->managers->getManagerOf('NewsSubject')->getList()) == 0){
				$this->app->getHttpResponse()->redirect('insert-news-category.html');
			}
			// si on veut modifier la news, alors l'identifiant est transmit
			if ($request->getGetData('id') != '') {
				$this->page->addVar('title', \Library\LanguagesManager::get('title_modify_news'));
				$news = $newsManager->getById($request->getGetData('id'));
			}else{
				$this->page->addVar('title', \Library\LanguagesManager::get('title_add_news'));
				$news = new \Library\Entities\News();
			}
		}
		
		// on ajoute l'id de l'auteur, l'utilisateur courant
		$formBuilder = new \Library\Form\Builders\NewsFormBuilder($news, $this->managers->getManagerOf('NewsSubject'), $this->app->getConfig()->getParam('urlThumbnail'));
		$formBuilder->build();

		$form = $formBuilder->getForm();
		$formBuilder->addNamePrefix($form->getEntity()->getClassName().'_');
		$form->setSubmit(\Library\LanguagesManager::get('add'));
		$form->setId("mainForm");
		
		$formHandler = new \Library\Form\FormHandler($form, $this->managers->getManagerOf('News'), $request);
		if(!$news->isNew()){
			$this->page->addVar('id', $request->getGetData('id'));
			$formHandler->addLanguageHelp($this->managers);
		}

		// si un formulaire a été envoyé via POST et qu'il est valide
		if ($formHandler->process()) {
			if(isset($_FILES['News_upload']) && in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){
				// premièrement on vérifie l'extension du fichier
				// si tout est ok on ajoute l'image miniature
				$pictureManager->resizeImage($_FILES['News_upload']['tmp_name'], $this->app->getConfig()->getParam('urlThumbnail').$pictureManager->getLastInsertPictureId().'.'.$ext, 200, 200);
				// et on déplace l'originale
				$pictureManager->movePicture($_FILES['News_upload']['tmp_name'], $this->app->getConfig()->getParam('urlImage').$pictureManager->getLastInsertPictureId().'.'.$ext);
				// on recharge la page pour la mise à jour de l'image (impossible car id définit lors de l'insertion en bd, étant effectuée après la génération du formulaire)
				if(!$news->isNew()){
					$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_news_success'));
					$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
				}
			}
			if($news->isNew()){
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('add_news_success'));
				$this->app->getHttpResponse()->redirect('news-'.$newsManager->getLastInsertId().'.html');
			}else{
				$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('modify_news_success'));
			}
		}else if(isset($_FILES['News_upload']) && in_array($ext, array('jpg', 'jpeg', 'bmp', 'png', 'gif'))){

			$this->app->getCurrentUser()->setAttribute('message', \Library\LanguagesManager::get('img_upload_last'));
			$this->app->getHttpResponse()->redirect($this->app->getHttpRequest()->getRequestURI());
		}

		$this->page->addVar('form', $form);
	}


}
?>