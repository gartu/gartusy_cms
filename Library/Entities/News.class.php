<?php

namespace Library\Entities;

class News extends \Library\Entity {

	protected $title;
	protected $subjectId;
	protected $subjectName;
	protected $content;
	protected $picture;
	protected $visible;
	protected $createdDate;
	protected $modifiedDate;

	const INVALID_TITLE = 2;
	const INVALID_CONTENT = 3;
	const INVALID_DATE = 4;


	/**
	 * Constructeur permettant de placer la valeur de picture à null
	 * @access public
	 * @param array $data;	tableau contenant les données relative à l'hydratation
	 * @return void
	 */
	public function __construct(array $data = array()) {
		parent::__construct($data);
		$this->picture = null;
	}

	/**
	 * Récupère le titre de la news
	 * @access public
	 * @return string; titre de la news
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * Récupère le contenu de la news
	 * @access public
	 * @return string;	contenu de la news
	 */
	public function getContent() {
		return $this->content;
	}


	/**
	 * Récupère la date de création de la news
	 * @access public
	 * @return DateTime; date de création
	 */
	public function getCreatedDate() {
		return $this->createdDate;
	}


	/**
	 * Récupère la date de la dernière modification de la news
	 * @access public
	 * @return DateTime; date de la dernière modification
	 */
	public function getModifiedDate() {
		return $this->modifiedDate;
	}



	/**
	 * Récupère l'id du sujet de la news
	 * @access public
	 * @return int l'id du sujet concernant la news
	 */
	public function getSubjectId() {
		return $this->subjectId;
	}


	/**
	 * Récupère le nom du sujet auquel appartient la news
	 * @access public
	 * @return String le sujet de la catégorie
	 */
	public function getSubjectName() {
		return $this->subjectName;
	}

	/**
	 * Récupère la visibilité de la news, vrai si visible, faux si caché
	 * @access public
	 * @return bool la visibilité de la news
	 */
	public function getVisible() {
		return $this->visible;
	}
	
	/**
	 * Récupère l'image associée à la news
	 * @access public
	 * @return \Library\Entities\Picture l'image associée à la news
	 */
	public function getPicture() {
		return $this->picture;
	}


	/**
	 * Permet de vérifier si la news est valide
	 * @access public
	 * @return bool; 	renvoie vrai si la news est valide, faux sinon
	 */
	public function isValid() {
		return (is_string($this->title) && !empty($this->title) && is_string($this->content) && !empty($this->content));
	}


	/**
	 * met à jour le titre
	 * @access public
	 * @param string $title;	titre de la news
	 * @return void
	 */
	public function setTitle($title) {
		if (!is_string($title) || empty($title)) {
			$this->errors[] = self::INVALID_TITLE;
		}else{
			$this->title = $title;
		}
	}


	/**
	 * met à jour l'id du sujet de la news
	 * @access public
	 * @param Int $id; id du sujet auquel la news fait référence
	 * @return void
	 */
	public function setSubjectId($id) {
		$this->subjectId = $id;
	}


	/**
	 * met à jour l'image relative à la news
	 * @access public
	 * @param \Library\Entities\Picture $picture; l'image à associer à la news
	 * @return void
	 */
	public function setPicture(\Library\Entities\Picture $picture) {
		$this->picture = $picture;
	}


	/**
	 * met à jour le nom du sujet de la news
	 * @access public
	 * @param Int $id; id du sujet auquel la news fait référence
	 * @return void
	 */
	public function setSubjectName($name) {
		$this->subjectName = $name;
	}


	/**
	 * met à jour la visibilité de la news, vrai si visible, faux si caché
	 * @access public
	 * @param bool la visibilité de la news
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
	}


	/**
	 * met à jour le contenu
	 * @access public
	 * @param string $content 
	 * @return void
	 */
	public function setContent($content) {
		if (!is_string($content) || empty($content)) {
			$this->errors[] = self::INVALID_CONTENT;
		}else{
			$this->content = $content;
		}
	}


	/**
	 * met à jour la date de création
	 * @access public
	 * @param DateTime $createdDate; 	date de création
	 * @return void
	 */
	public function setCreatedDate(\DateTime $createdDate) {
		$this->createdDate = $createdDate;
	}


	/**
	 * met à jour la date de la dernière modification
	 * @access public
	 * @param DateTime $modifiedDate;	date de la dernière modification
	 * @return void
	 */
	public function setModifiedDate(\DateTime $modifiedDate) {
		if ($modifiedDate < $this->createdDate) {
			$this->errors[] = self::INVALID_DATE;
		}else{
			$this->modifiedDate = $modifiedDate;
		}
	}


}
?>