<?php

namespace Library\Entities;


class SimpleText extends \Library\Entity {

	protected $content;
	protected $modifiedDate;
	protected $private;


	/**
	 * Accesseur sur le contenu du texte
	 * @access public
	 * @return string le contenu texte
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * accesseur sur la date de modification
	 * @access public
	 * @return DateTime la date de la dernière modification
	 */
	public function getModifiedDate() {
		return $this->modifiedDate;
	}


	/**
	 * Met à jour le contenu texte
	 * @access public
	 * @param string $content le contenu texte
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}


	/**
	 * Met à jour la date de la dernière modification
	 * @access public
	 * @param DateTime $modifiedDate la date de la dernière modification
	 * @return void
	 */
	public function setModifiedDate(DateTime $modifiedDate) {
		$this->modifiedDate = $modifiedDate;
	}

	/**
	 * Permet de savoir si ce contenu est reservé aux personnes connectées
	 * @access public
	 * @return bool si le contenu est oui ou non privé
	 */
	public function getPrivate() {
		return $this->private;
	}


	/**
	 * Définit si le contenu est privé ou pas
	 * @access public
	 * @param bool $private si oui ou non le contenu doit etre privé
	 * @return void
	 */
	public function setPrivate($private) {
		$this->private = $private;
	}


}
?>