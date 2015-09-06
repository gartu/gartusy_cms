<?php

namespace Library;


/*	permet de définir la classe mère regroupant toutes
 * 	les différentes entity; Imlémente ArrayAccess afin
 *	de pouvoir être parcouru de manière simplifiée
 */
abstract class Entity implements \ArrayAccess {

	protected  $errors = array();
	protected  $id;


	/**
	 * Constructeur appelant la méthode hydrate afin de placer les données dans l'objet
	 * @access public
	 * @param array $data;	tableau contenant les données relative à l'hydratation
	 * @return void
	 */
	public function __construct(array $data = array()) {
		if (!empty($data)) {
			$this->hydrate($data);
		}
	}


	/**
	 * Permet de savoir si l'objet possède un id, donc s'il a été hydraté
	 * @access public
	 * @return bool 
	 */
	public function isNew() {
		return empty($this->id);
	}


	/**
	 * Permet de récupérer le tableau contenant les erreurs
	 * @access public
	 * @return array 	tableau contenant les erreurs
	 */
	public function getErrors() {
		return $this->errors;
	}


	/**
	 * getteur de l'id relatif à l'objet
	 * @access public
	 * @return int 	numéro de l'id de l'objet
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * met à jour l'id
	 * @access public
	 * @param int $id; id de la news
	 * @return void
	 */
	public function setId($id) {
		if (is_numeric($id) && (int)$id > 0) {
			$this->id = $id;
		}
	}

	/**
	 * Méthode d'hydratation, permet d'aller chercher les données et les placer dans l'objet.
	 * @access protected
	 * @param array $data 	tableau de données à placer dans l'objet
	 * @return void
	 */
	protected function hydrate($data) {
		foreach ($data as $name => $value) {
			$setter = 'set'.ucfirst($name);
			if (is_callable(array($this, $setter))) {
				$this->$setter($value);
			}
		}
	}


	/**
	 * redéfinission de la méthode offsetGet de l'interface ArrayAccess
	 * @access public
	 * @param $var 
	 * @return object
	 */
	public function offsetGet($var) {
		if (isset($this->$var) && is_callable(array($this, $var))) {
			return $this->$var();
		}
	}


	/**
	 * redéfinission de la méthode offsetSet de l'interface ArrayAccess
	 * @access public
	 * @param int $var 
	 * @param object $value 
	 * @return void
	 */
	public function offsetSet($var,  $value) {
		$method = 'set'.ucfirst($var);
		if (isset($this->$method) && is_callable(array($this, $method))) {
			$this->$method($value);
		}
	}


	/**
	 * redéfinission de la méthode offsetExists de l'interface ArrayAccess
	 * @access public
	 * @param int $var 
	 * @return bool
	 */
	public function offsetExists($var) {
		return isset($this->$var) && is_callable(array($this, $var));
	}


	/**
	 * redéfinission de la méthode offsetUnset de l'interface ArrayAccess
	 * @access public
	 * @param int $var 
	 * @return void
	 */
	public function offsetUnset($var) {
		throw new \Exception('Impossible de supprimer de valeur');
		
	}

	/**
	 * permet de récupérer le nom de l'entité traitée
	 * @access public 
	 * @return String le nom de l'entité
	 */
	public function getClassName() {
		$tmp = get_class($this);
		$tmp = explode('\\', $tmp);

		return $tmp[count($tmp)-1];
	}



}
?>