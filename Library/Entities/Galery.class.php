<?php

namespace Library\Entities;

class Galery extends \Library\Entity {

	protected $title;
	protected $description;
	protected $pictures; // tableau contenant la liste des images composant la galerie

	/**
	 * Récupère le titre de la galerie
	 * @access public
	 * @return string; le titre de la galerie
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * Récupère le texte de description de la galerie
	 * @access public
	 * @return string; la description de la galerie
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * met à jour le titre de la galerie
	 * @access public
	 * @param string $title;	le titre de la galerie
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * met à jour le texte descriptif de la galerie
	 * @access public
	 * @param String $description le texte descriptif
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * récupère l'image supécifiée de la galerie
	 * @access public
	 * @param int $num l'indice de l'image désirée
	 * @return l'image de l'indice demandé
	 */
	public function getPicture($num){
		return $this->pictures[$num];
	}

	/**
	 * récupère les images de la galerie
	 * @access public
	 * @return array les images liées à la galerie
	 */
	public function getPictures(){
		return $this->pictures;
	}

	/**
	 * met à jour la liste complète des images de la galerie
	 * @access public
	 * @param array $pictures un tableau contenant la liste des images définissant la galerie
	 * @return void
	 */
	public function setPictures(array $pictures) {
		$this->pictures = $pictures;
	}


	/**
	 * Ajoute une image à la liste déjà définie
	 * @access public
	 * @param Picture $picture l'image à ajouter
	 * @return void
	 */
	public function addPicture(\Library\Entities\Picture $picture) {
		$this->pictures[] = $picture;
	}



}
?>