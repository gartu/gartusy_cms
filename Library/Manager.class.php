<?php
namespace Library;

/*	Définition de la classe mère "Manager" implémentant
 *	obligatoirement un constructeur avec l'objet de gestion DAO (Data Access Object)
 */
abstract class Manager {

	protected $dao;
	private $lockedId;

	/**
	* Constructeur d'un manageur en lui passant son système de gestion 
	* @param object $dao; 	objet représentant l'interface de communication avec nos données stoquées
	**/
	public function __construct($dao){
		$this->dao = $dao;
	}

	/**
	 * Récupère le dernier ID inséré dans la base de données, si la dernière entrée 
	 * n'ajoutait pas d'id et qu'un id a été préalablement verrouillé alors c'est 
	 * celui-ci qui sera retourné
	 * @return int Id du dernier id inséré
	 */
	public function getLastInsertId(){
		return $this->dao->lastInsertId() == 0 ? $this->lockedId : $this->dao->lastInsertId();
	}


	/**
	 * Permet de verrouiller un id afin qu'il ne soit pas remplacé en cas d'insertion de valeur dans id
	 */
	protected function lockId(){
		$this->lockedId = $this->dao->lastInsertId();
	}

}

?>