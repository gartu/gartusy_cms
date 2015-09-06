<?php
namespace Library;

/**
* Classe gérant le type de notre application de manière globale (backend / frontend)
**/
abstract class Application {

	protected $name; 			// string
	protected $httpRequest;		// HTTPRequest
	protected $httpResponse;	// HTTPResponse
	protected $config;
	protected $currentUser;

	/**
	* Constructeur de notre application
	* @access protected
	* @return void
	**/
	protected function __construct(){
		$this->name = '';
		$this->httpRequest  = new HTTPRequest();
		$this->httpResponse = new HTTPResponse();
		$this->config = new Config($this);
		$this->currentUser = new CurrentUser();
//		$this->currentUser->cleanAttribute('message');
	}

	/**
	* méthode se chargeant de lancer le déroulement de l'application définie en tant qu'enfant
	* @access public
	* @return void
	**/
	abstract public function run();


	/**
	 * Lance le module de gestion de langue géré pour les erreurs, etc dans le fichier csv
	 * @access public
	 */
	public function runLanguage(){
		static $init = false;
		if(!$init){
			// on récupère la langue contenue dans l'url s'il y en a une
			if ($this->httpRequest->getExists('language')) {
				$language = $this->httpRequest->getGetData('language');

			}else{
				// on séléctionne la langue par défaut
				$language = $this->config->getParam('defaultLanguage');
			}
			// on initie la gestion des erreurs et du texte brut en multilangues
			\Library\LanguagesManager::init(ROOT.DS.'Library'.DS.'languages.csv', $language);
			$init = true;
		}
	}

	/**
	 * Permet de récupérer le controleur correspondant à la classe nécessaire 
	 * au bon déroulement des opérations contenues dans la requête du client
	 * @access public
	 * @return Controller correspondant au chemin entré par l'utilisateur dans l'URL
	 */
	public function getController($routeURI){

		// Dans le cas ou aucune route n'est transmise, page d'accueil => on choisit le premier menu comme route.
		if($routeURI == '/'){
			$this->runLanguage();
			$menus = new \Library\Menus($this);
			$tmp = $menus->getUserMenu();
			if(isset($tmp[0])){
				$routeURI = $tmp[0]->getURI();
			}else{
				$this->httpResponse->setPage(new Page($this));
				$this->httpResponse->redirect404();
			}
		}

		$router = $this->getRouter();
		// on essaye de récupérer la route correspondant à notre url
		// si on a une erreur alors on retourne une erreur 404
		try{
			$requestedRoute = $router->getRoute($routeURI);
		}catch(\RuntimeException $e){
			if($e->getCode() === \Library\Router::INEXISTANT_ROUTE){
				// la page est introuvable, on définit la langue à utiliser (idée via cookies si vraiment important lors de 404..)
				if(!defined('LANGUAGE')){
					\Library\LanguagesManager::init(ROOT.DS.'Library'.DS.'languages.csv', $this->config->getParam('defaultLanguage'));
				}
				$this->httpResponse->setPage(new Page($this));
				$this->httpResponse->redirect404();
			}
		}


		// on ajoute les variables qu'on a récupérée dans le tableau $_GET puis 
		// on instancie et renvoie le controleur correspondant à la requête
		$_GET = array_merge($_GET, $requestedRoute->getVars());

		$controllerClass = 'Applications\\'.$this->name.'\\Modules\\'.$requestedRoute->getModule().'\\'.$requestedRoute->getModule().'Controller';

		$controller = new $controllerClass($this, $requestedRoute->getModule(), $requestedRoute->getAction());
		$controller->setParentURI($routeURI);
		return $controller;
	}


	/**
	* créé et renvoie le router complété
	* @access public
	* @return Router le router contenant toutes les routes
	**/
	public function getRouter(){
		$router = new \Library\Router;

		// on récupère le contenu de notre fichier XML
		$xml = new \DOMDocument;
		$xml->load(ROOT.DS.'Applications'.DS.$this->name.DS.'Configurations'.DS.'routes.xml');

		$routes = $xml->getElementsByTagName('route');
		foreach ($routes as $route) {
			$vars = array();

			// si il y a des variables dans l'URL on place chacune d'elle dans une case de vars (voir route.xml)
			if($route->hasAttribute('vars')){
				$vars = explode(',', $route->getAttribute('vars'));
			}

			// ajout de la route récupérée dans le router
			$router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
		}

		return $router;
	}

	/**
	* accesseur au nom
	* @access public
	* @return string nom de l'application 
	**/
	public function getName(){
		return $this->name;
	}

	/**
	* accesseur à la requête faite à l'application
	* @access public
	* @return HTTPRequest la requête faite à l'application
	**/
	public function getHttpRequest(){
		return $this->httpRequest;
	}

	/**
	* accesseur à la réponse à donner au client
	* @access public
	* @return HTTPResponse la réponse qui sera faite au client
	**/
	public function getHttpResponse(){
		return $this->httpResponse;
	}

	/**
	* accesseur à la configuration de l'application
	* @access public
	* @return Config;	la confgiruation de l'application
	**/
	public function getConfig(){
		return $this->config;
	}

	/**
	* accesseur à l'utilisateur de l'application
	* @access public
	* @return User;		Utilisateur de l'application
	**/
	public function getCurrentUser(){
		return $this->currentUser;
	}
}

?>