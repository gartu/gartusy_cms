<?php

namespace Library\Form;

class Form {

	protected $elements;
	protected $entity;
	protected $id = '';
	protected $action = '';
	protected $submit = null; // bouton à null signifie pas de bouton, sinon y placer un contenu de type string


	/**
	 * permet de construire un formulaire correspondant à un objet héritant du type Entity
	 * @access public
	 * @param Entity $entity l'élément source sur la base duquel créer le formulaire
	 * @return void
	 */
	public function __construct(\Library\Entity $entity) {
		$this->setEntity($entity);
	}


	/**
	 * On ajoute un champs à notre formalaire et le remplit avec selon notre modèle
	 * @access public
	 * @param Field $field le champs à ajouter à notre formulaire
	 * @return void
	 */
	public function add(\Library\Form\Elements\Element $element) {
		// on place dans le champs passé en paramètre la valeur contenue dans notre
		// élément modèle puis on l'ajoute à notre liste de champs.
		$method = 'get'.ucfirst($element->getName());
		// on priorise les données réceptionnée de l'objet au données par défaut, utilisée que si rien n'est transmit
		if (method_exists($this->entity, $method) && !is_null($this->entity->$method()) && method_exists($element, 'setValue')) {
			$element->setValue($this->entity->$method());
		}

		$this->elements[] = $element;
	}


	/**
	 * permet de récupérer l'élément modèle à la base du formulaire
	 * @access public
	 * @return Entity notre élément modèle sur la base de notre formulaire
	 */
	public function getEntity() {
		return $this->entity;
	}


	/**
	 * modifie l'action du formulaire
	 * @access public
	 * @param String $action la page cible lors de l'envoi du formulaire
	 * @return void
	 */
	public function setAction($action) {
		$this->action = $action;
	}


	/**
	 * permet de récupérer l'action du du formulaire
	 * @access public
	 * @return String la page issue de l'action du formulaire
	 */
	public function getAction() {
		return $this->action;
	}


	/**
	 * modifie l'id du formulaire
	 * @access public
	 * @param String $id l'id du formulaire
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * permet de récupérer l'id du formulaire
	 * @access public
	 * @return String l'id du formulaire
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Définit le texte du bouton d'envoie
	 * @access public
	 * @param String $label le texte du bouton d'envoi
	 * @return void
	 */
	public function setSubmit($label) {
		$this->submit = $label;
	}


	/**
	 * permet de récupérer le texte du bouton d'envoi du formulaire
	 * @access public
	 * @return String le texte du bouton d'envoi
	 */
	public function getsubmit() {
		return $this->submit;
	}


	/**
	 * permet de récupérer les champs du formulaire
	 * @access public
	 * @return Field[] le tableau contenant tous les champs du formulaire
	 */
	public function getElements() {
		return $this->elements;
	}


	/**
	 * ajoute le type d'objet sur la base duquel sera créé le formulaire
	 * @access public
	 * @param Entity $entity l'élément source sur la base duquel créer le formulaire
	 * @return void
	 */
	public function setEntity(\Library\Entity $entity) {
		$this->entity = $entity;
	}


	/**
	 * on vérifie si tous les champs sont valides
	 * @access public
	 * @return bool si tout est valide
	 */
	public function isValid() {
		// si un champs n'est pas valide alors le formulaire ne l'est pas
		foreach ($this->elements as $element) {
			if(!$element->isValid())
				return false;
		}
		return true;
	}


	/**
	 * génère la vue correspondante au formulaire au format html
	 * @access public
	 * @param Integer $numberByRow
	 * @return string le code html relatif au formulaire
	 */
	public function generate($numberByRow = 2) {
		$numberByRow = (int)$numberByRow;
		$view = '<div class="formContent">';

		$j = 0;
		for ($i = 0; $i < count($this->elements); $i += 1) {
			$element = $this->elements[$i];

			if($j % $numberByRow == 0){
				if($j > 0){
					$view .= '</div>';
				}

				if($element->isAlone()){
					$view .= '<div class="fullRow">';
					$j = (2 * $numberByRow) - 1;
				}else{
					$view .= '<div class="mediumRow">';
				}
			}else if($element->isAlone()){
				$view .= '</div><div class="fullRow">';
				$j = (2 * $numberByRow) - 1;
			}

			$view .= $element->beginningSeparator().$element->buildElement().$element->endingSeparator();
			$j += 1;
		}
		// on renvoie le formulaire en retirant le premier retour à la ligne
		return $view.'</div></div><hr class="clear"/>';
	}



	/**
	 * génère le début de la vue correspondante au formulaire au format html
	 * @access public
	 * @return string le code html relatif à l'entête du formulaire
	 */
	public function generateHeader() {
		return '<form action="'.$this->action.'" method="post" id="'.$this->id.'">';
	}


	/**
	 * génère le bouton d'envoi du formulaire au format html
	 * @access public
	 * @return string le code html relatif au bouton d'envoi du formulaire
	 */
	public function generateSubmit() {
		if(is_null($this->submit)){
			return '';
		}else{
			return '<button type="submit">'.$this->submit.'</button>';
		}
	}


	/**
	 * génère la balise de fin du formulaire au format html
	 * @access public
	 * @return string le code html relatif à la terminaison du formulaire
	 */
	public function generateFooter() {
		return '</form>';
	}

}
?>