<?php

namespace Library\Form\Elements;

class TextField extends \Library\Form\Elements\Field {

	protected $parameter;
	protected $rows;
	protected $cols;
	protected $width;
	protected $abbr;

	/**
	 * créé la vue correspondante au champs
	 * @access public
	 * @return string le code html correspondant à la vue du champs
	 */
	public function buildElement() {
		// ancien contenu présent et utilisé avec nicEdit
		/*
		$view = '';
		$id = htmlentities($this->name).$this->parameter;

		if (!empty($this->errorMessage)) {
			$view .= '<span class="error">'.$this->errorMessage.'</span><br/>';
		}

		$view .= '<label for="'.$id.'"';
		if($this->getClasses()!=""){
			$view .= ' class="'.$this->getClasses().'"';
		}
		$view .= '>'.$this->label.'</label>';
		if($this->parameter == '-editor')
			$view .= '<hr class="clear"/><abbr title="'.$this->abbr.'"><div class="textarea">';

		$view .= '<textarea id="'.$id.'" name="'.$this->name.'"';

		if (!empty($this->rows)) {
			$view .= ' rows="'.htmlentities($this->rows).'"';
		}

		if (!empty($this->cols)) {
			$view .= ' cols="'.htmlentities($this->cols).'"';
		}else if (!empty($this->width)) {
			$view .= ' style="max-width:'.$this->width.'px;"';
		}

		$view .= ' >';

		if (!empty($this->value)) {
			$view .= $this->value;
		}

		$view .= '</textarea>';
		if($this->parameter == '-editor')
			$view .= '</div></abbr><hr class="clear"/>';
		*/
		$view = '';
		$id = htmlentities($this->name).$this->parameter;

		if (!empty($this->errorMessage)) {
			$view .= '<span class="error">'.$this->errorMessage.'</span><br/>';
		}

		$view .= '<label for="'.$id.'"';
		if($this->getClasses()!=""){
			$view .= ' class="'.$this->getClasses().'"';
		}
		$view .= '>'.$this->label.'</label>';
		$view .= '<textarea id="'.$id.'" name="'.$this->name.'"';

		if (!empty($this->rows)) {
			$view .= ' rows="'.htmlentities($this->rows).'"';
		}

		if (!empty($this->cols)) {
			$view .= ' cols="'.htmlentities($this->cols).'"';
		}else if (!empty($this->width)) {
			$view .= ' style="max-width:'.$this->width.'px;"';
		}

		if($this->parameter == '-editor'){
			$view .= ' class="editor"';
		}
		
		$view .= ' >';

		if (!empty($this->value)) {
			$view .= $this->value;
		}

		$view .= '</textarea>';

		return $view;
	}


	/**
	 * Met à jour le contenu d'info-bulle du champs
	 * @access public
	 * @param String $caption le contenu de l'info-bulle
	 * @return void
	 */
	public function setAbbr($caption){
		$this->abbr = $caption;
	}


	/**
	 * met à jour le nombre de ligne du champs
	 * @access public
	 * @param int $rows nombre de  lignes
	 * @return void
	 */
	public function setRows($rows) {
		$rows = (int)$rows;

		if ($rows > 0) {
			$this->rows = $rows;
		}else{
			throw new \RunetimeException('Le nombre de ligne du champs est nulle ou négative.');
		}
	}



	/**
	 * met à jour la taille en pixel du champs (si définit alors cols est null)
	 * @access public
	 * @param int $size la taille en pixel
	 * @return void
	 */
	public function setWidth($size) {
		$size = (int)$size;

		if ($size > 0) {
			$this->cols = null;
			$this->width = $size;
		}else{
			throw new \RunetimeException('La taille du champs est nulle ou négative.');
		}
	}



	/**
	 * met à jour le nombre de colonnes du champs (si définit alors width est null)
	 * @access public
	 * @param int $cols nombre de colonnes
	 * @return void
	 */
	public function setCols($cols) {
		$cols = (int)$cols;

		if ($cols > 0) {
			$this->cols = $cols;
			$this->width = null;
		}else{
			throw new \RunetimeException('Le nombre de colonnes du champs est nulle ou négative.');
		}
	}


	/**
	 * met à jour le paramètre à ajouter à l'id
	 * @access public
	 * @param string $param
	 * @return void
	 */
	public function setParameter($parameter) {
		$this->parameter = $parameter;
	}


	/**
	 * surcharge afin de définir le sépararateur approprié aux champs texte pleine page
	 * @access public
	 * @return String le séparateur html à employer
	 */
	public function beginningSeparator(){
		$view = '';
		if($this->clear === 1){
			$view .= '<hr class="clear" />';
		}

		return $view.'<div class="formTextField">';
	}

}
?>