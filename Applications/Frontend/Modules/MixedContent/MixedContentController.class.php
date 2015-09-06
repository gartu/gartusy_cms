<?php


namespace Applications\Frontend\Modules\MixedContent;

class MixedContentController extends \Library\BackController {


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */

	public function executeShow(\Library\HTTPRequest $request) {

		$manager = $this->managers->getManagerOf('MixedContent');

		$contentsList = $manager->getList($_GET['id'], $this->managers, $this->app->getCurrentUser()->isLogged());

		$this->page->addVar('contentsList', $contentsList);

	}


}
?>