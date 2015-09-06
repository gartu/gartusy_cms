<?php
namespace Library;

class Router {

	protected $routes = array();

	const INEXISTANT_ROUTE = 1;

	/**
	 * méthode permettant d'ajouter une route à notre router
	 * @access public
	 * @param Route $route; route à ajouter à notre routeur
	 * @return void
	 */
	public function addRoute(Route $route) {
		if(!in_array($route, $this->routes)){
			$this->routes[] = $route;
		}
	}

	/**
	 * méthode permettant de récupérer la route correspondant à un url spécifié
	 * @access public
	 * @param string $url;  url correspondant à la route recherchée
	 * @return Route; 		la route correspondant à l'url
	 */
	public function getRoute($url) {

		/*
		* Pour chaque route que l'on possède on va vérifier si celle-ci correspond 
		* (du moins en partie) à une route existante, si c'est le cas on va récupérer
		* les paramètres de l'action et du module à executer
		*/
		foreach ($this->routes as $route) {
			if($route->isMatched($url) === 1){
				if($route->hasVars()){

					$varsValue = $route->getMatch($url);
					$varsName =  $route->getVarsNames();
					$varsList =  array();

					foreach ($varsValue as $key => $match) {
						// la première valeur contient la chaine capturée entièrement, voir doc preg_match()
						if($key !== 0){
							$varsList[$varsName[$key-1]] = $match;
						}
					}
					$route->setVars($varsList);
				}
				return $route;
			}
		}
		throw new \RuntimeException('Aucune route ne correspond à l\'url spécifié.', self::INEXISTANT_ROUTE);
		
	}
}

?>