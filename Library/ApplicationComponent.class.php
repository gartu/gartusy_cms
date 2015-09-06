<?php

namespace Library;

abstract class ApplicationComponent {

	protected $app; 	// Application

	/**
	* Constructeur de notre composant d'application
	* @access public
	* @param $app Application; application à laquelle est lié notre composant
	* @return void
	**/
	public function __construct(Application $app){
		$this->app = $app;
	}

	/**
	* accesseur à l'application relative à notre composant
	* @access public
	* @return Application l'application liée au composant
	* @return void
	**/
	public function getApplication(){
		return $this->app;
	}
}

?>