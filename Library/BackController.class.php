<?php

namespace Library;

/*	Class BackController, les objets de cette classe sont
 *  instancié une foi l'url decrypté et la page souhaitée 
 *  définie afin d'appeller la méthode correcte correspondante.
 */
class BackController extends ApplicationComponent {

	protected $action = '';
	protected $module = '';
	protected $view = '';
	protected $page = null;
	protected $managers = null;
	protected $parentURI;

	/**
	 * Constructeurde notre backcontroleur
	 * @access public
	 * @param Application $app; application parente
	 * @param string $module;	module appellé par le client
	 * @param string $action;	action à effectuer sur le module en question
	 * @return void
	 */
	public function __construct(Application $app, $module, $action) {
		parent::__construct($app);
		
		// on récupère le DAO de l'application dans les paramètre et on l'envoie pour le constructeur des managers
		$dao = $this->app->getConfig()->getParam('dao');
		$this->managers = new Managers(call_user_func($dao));
		$this->page = new Page($app);
		
		$this->page->addVar('title', $this->app->getConfig()->getParam('defaultTitle'.$this->app->getName()));

		$this->setModule($module);
		$this->setAction($action);
		$this->setView($action);
	}


	/**
	 * Méthode permetant d'executer l'action sur le module spécifique
	 * @access public
	 * @return void
	 */
	public function execute() {
		$method = 'execute'.ucfirst($this->action);
		
		// si la méthode existe on l'appelle, sinon on lève une exception
		if(!is_callable(array($this, $method))){
			throw new \RuntimeException('L\'action '.$this->action.' n\'est pas définie pour ce module');
		}
		$this->$method($this->getApplication()->getHttpRequest());
	}


	/**
	 * getteur d'une page
	 * @access public
	 * @return Page
	 */
	public function getPage() {
		return $this->page;
	}


	/**
	 * setteur pour l'URI parent relatif à ce controlleur
	 * @access public
	 * @param string $parentURI; 	URI utilisé pour l'appel de ce controlleur
	 * @return void
	 */
	public function setParentURI($parentURI) {
		$this->parentURI = $parentURI;
	}

	/**
	 * setteur pour le module à appeller
	 * @access public
	 * @param string $module; 	nom du module à appeler
	 * @return void
	 */
	public function setModule($module) {
		if(!is_string($module) || empty($module)){
			throw new RuntimeException('Le module doit être une chaine de caractère valide');
		}
		$this->module = $module;
	}


	/**
	 * setteur d'une action spécifique à un module
	 * @access public
	 * @param string $action;	nom de l'action à effectuer
	 * @return void
	 */
	public function setAction($action) {
		if(!is_string($action) || empty($action)){
			throw new RuntimeException('L\'action doit être une chaine de caractère valide');			
		}
		$this->action = $action;
	}


	/**
	 * setteur de la vue à employer
	 * @access public
	 * @param string $view;	vue à utiliser
	 * @return void
	 */
	public function setView($view) {
		if (!is_string($view) || empty($view)) {
			throw new RuntimeException('La vue doit être une chaine de caractère valide');			
		}
		$this->view = $view;
		$this->page->setContentFile(ROOT.DS.'Applications'.DS.$this->getApplication()->getName().DS.'Modules'.DS.$this->module.DS.'Views'.DS.$this->view.'.php');
	}


}
?>