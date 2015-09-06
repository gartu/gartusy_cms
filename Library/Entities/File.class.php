<?php

namespace Library\Entities;

class File extends \Library\Entity {

	protected $name;
	protected $shortName;
	protected $directory;

	/**
	 * Constructeur appelant la méthode hydrate afin de placer les données dans l'objet
	 * @access public
	 * @param array $data;	tableau contenant les données relative à l'hydratation
	 * @return void
	 */
	public function __construct(array $data = array()) {
		parent::__construct($data);
	}

	/**
	 * Récupère le nom du fichier
	 * @access public
	 * @return string; le nom du fichier
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * met à jour le nom du fichier
	 * @access public
	 * @param string $name;	nom du fichier
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Récupère le nom du répértoire du fichier
	 * @access public
	 * @return string; le répértoire du fichier
	 */
	public function getDirectory() {
		return $this->directory;
	}


	/**
	 * met à jour le répertoire du fichier
	 * @access public
	 * @param string; le chemin du répertoire
	 */
	public function setDirectory($path) {
		$this->directory = $path;
	}
	

	/**
	 * Récupère le nom du fichier sans extension
	 * @access public
	 * @return string; le nom du ficheir sans extension
	 */
	public function getShortName() {
		return $this->shortName;
	}


	/**
	 * met à jour le nom du fichier
	 * @access public
	 * @param string; le nom du fichier sans l'extension
	 */
	public function setShortName($shortName) {
		$this->shortName = $shortName;
	}
	

	/**
	 * récupére l'url du fichier
	 * @access public
	 * @param string; l'url du fichier complet
	 */
	public function getURL() {
		return $this->directory.$this->name;
	}


	

}
?>