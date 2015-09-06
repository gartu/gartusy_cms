<?php

namespace Library\Entities;

abstract class Menu extends \Library\Entity {

	protected $name;
	protected $metric;
	protected $visible;
	protected $module;
	protected $controller;
	protected $options;
	protected $description;


	/**
	 * créé l'uri relatif au menu et le renvoie
	 * @access public
	 * @return string l'URI relatif au menu
	 */
	public function getURI(){
		$lien = '';
		
		if($this->controller != '')
			$lien .= $this->controller.'-';

		$lien .= $this->module;
		
		if($this->options != '')
			$lien .= '-'.$this->options;

		return '/'.\Library\LanguagesManager::getLanguage().'/'.$lien.'.html';
	}

	/**
	 * Récupère le nom du menu
	 * @access public
	 * @return string le nom du menu
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Récupère la métrique du menu (plus bas est le premier)
	 * @access public
	 * @return int le numéro de metrique du menu
	 */
	public function getMetric() {
		return $this->metric;
	}


	/**
	 * Récupère la visibilité du menu, vrai si visible, faux si caché
	 * @access public
	 * @return bool la visibilité du menu
	 */
	public function getVisible() {
		return $this->visible;
	}


	/**
	 * récupère la description du Menu
	 * @access public
	 * @return string le texte descriptif du menu
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * récupère le module lié au menu
	 * @access public
	 * @return string le nom du module 
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * récupère le controller du module du Menu
	 * @access public
	 * @return string le nom du controller utilisé
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * récupère les options du menu
	 * @access public
	 * @return string le texte représentant les options
	 */
	public function getOptions() {
		return $this->options;
	}


	/**
	 * met à jour le nom du menu
	 * @access public
	 * @param string le nom du menu
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * met à jour la métrique du menu (plus bas est le premier)
	 * @access public
	 * @param int le numéro de metrique du menu
	 */
	public function setMetric($metric) {
		$this->metric = $metric;
	}


	/**
	 * met à jour la visibilité du menu, vrai si visible, faux si caché
	 * @access public
	 * @param bool la visibilité du menu
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
	}


	/**
	 * met à jour la description du Menu
	 * @access public
	 * @param la nouvelle description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


	/**
	 * met à jour le module relatif au menu
	 * @access public
	 * @param string $module le module du menu
	 */
	public function setModule($module) {
		$this->module = $module;
	}


	/**
	 * met à jour le controller utilisé par le module
	 * @access public
	 * @param string $controller le controlleur du module du menu
	 */
	public function setController($controller) {
		$this->controller = $controller;
	}



	/**
	 * met à jour les options appellée avec le controlleur
	 * @access public
	 * @param String $options la liste des options complémentaires
	 */
	public function setOptions($options) {
		$this->options = $options;
	}

}
?>