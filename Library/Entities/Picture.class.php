<?php

namespace Library\Entities;

class Picture extends \Library\Entity {

	protected $name;
	protected $description;
	protected $format;

	/**
	 * Récupère le nom de l'image
	 * @access public
	 * @return string; le nom de l'image
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * met à jour le nom de l'image
	 * @access public
	 * @param string $name;	nom de l'image
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * Récupère le format de l'image
	 * @access public
	 * @return string; le format de l'image
	 */
	public function getFormat() {
		return $this->format;
	}


	/**
	 * met à jour le format de l'image
	 * @access public
	 * @param string $format;	format de l'image
	 * @return void
	 */
	public function setFormat($format) {
		$this->format = $format;
	}

	/**
	 * met à jour la description de l'image
	 * @access public
	 * @param string $description, la description de l'image
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Récupère la description de l'image
	 * @access public
	 * @return string; la description de l'image
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * Récupère le nom de l'image sur le serveur
	 * @access public
	 * @return String le nom de l'image tel que stocké sur le serveur
	 */
	public function getUrl() {
		// on retourne l'id car les images sont renommées selon leur id
		return $this->id.'.'.$this->format;
	}

}
?>