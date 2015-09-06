<?php

namespace Applications\Frontend\Modules\Galery;

class GaleryController extends \Library\BackController {


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request) {

		$pictureManager = $this->managers->getManagerOf('Picture');
		$galeryManager = $this->managers->getManagerOf('Galery');
		$galeryManager->setPictureManager($pictureManager);

		$galery = $galeryManager->getById($request->getGetData('id'));
		
		$this->page->addVar('galery', $galery);
		$this->page->addVar('imgPath', $this->app->getConfig()->getParam('urlImage'));
		$this->page->addVar('thumbPath', $this->app->getConfig()->getParam('urlThumbnail'));
	}


}
?>
