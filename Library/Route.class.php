<?php

namespace Library;

class Route {

	protected $action;			// string
	protected $module;			// string
	protected $url;				// string
	protected $varsNames;		// array
	protected $vars = array();	// array


	/**
	 * @access public
	 * @param string $url;		
	 * @param string $module 
	 * @param string $action 
	 * @param array $varsName 
	 * @return void
	 */
	public function __construct($url, $module, $action, $varsNames) {
		$this->setUrl($url);
		$this->setModule($module);
		$this->setAction($action);
		$this->setVarsNames($varsNames);
	}


	/**
	 * méthode permettant de savoir si la route a des variables
	 * @access public
	 * @return bool
	 */
	public function hasVars() {
		return (!empty($this->varsNames));
	}


	/**
	 * méthode permettant de savoir si un url donné contient 
	 * une partie en commun avec celui de la route
	 * @access public
	 * @param string $url; url à analyser
	 * @return bool
	 */
	public function isMatched($url) {
		return preg_match('#^'.$this->url.'$#', $url);
	}

	/**
	 * accesseur à la partie en commun entre l'url passé en paramètre
	 * et l'url de la route
	 * @access public
	 * @param string $url 
	 * @return string de la valeur correspondante, null si rien ne match
	 */
	public function getMatch($url) {
		if($this->isMatched($url)){
			preg_match('#^'.$this->url.'$#', $url, $matches);
			return $matches;
		}else{
			return null;
		}		
	}


	/**
	 * setteur de l'action
	 * @access public
	 * @param string $action 
	 * @return void
	 */

	public function setAction($action) {
		$this->action = $action;
	}


	/**
	 * setteur du module
	 * @access public
	 * @param string $module 
	 * @return void
	 */

	public function setModule($module) {
		$this->module = $module;
	}


	/**
	 * setteur de l'url
	 * @access public
	 * @param string $url 
	 * @return void
	 */

	public function setUrl($url) {
		$this->url = $url;
	}


	/**
	 * setteur du nom des variables
	 * @access public
	 * @param array $varsNames 
	 * @return void
	 */

	public function setVarsNames($varsNames) {
		$this->varsNames = $varsNames;
	}


	/**
	 * setteur des variables
	 * @access public
	 * @param array $vars 
	 * @return void
	 */

	public function setVars($vars) {
		$this->vars = $vars;
	}


	/**
	 * accesseur à l'action de la route
	 * @access public
	 * @return string
	 */

	public function getAction() {
		return $this->action;
	}


	/**
	 * accesseur au module de la route
	 * @access public
	 * @return string
	 */

	public function getModule() {
		return $this->module;
	}


	/**
	 * accesseur à l'url de la route
	 * @access public
	 * @return string
	 */

	public function getUrl() {
		return $this->url;
	}


	/**
	 * accesseur au nom des variables
	 * @access public
	 * @return array
	 */
	public function getVarsNames() {
		return $this->varsNames;
	}


	/**
	 * accesseur au valeurs des variables
	 * @access public
	 * @return array
	 */
	public function getVars() {
		return $this->vars;
	}


}
?>