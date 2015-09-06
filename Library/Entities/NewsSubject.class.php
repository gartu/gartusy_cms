<?php

namespace Library\Entities;

class NewsSubject extends \Library\Entity {

	protected $name;

	/**
	 * Récupère le sujet de la news
	 * @access public
	 * @return string; de nom de du sujet de news
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * met à jour le nom du sujet
	 * @access public
	 * @param string $name;	nom du sujet de la news
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

}
?>