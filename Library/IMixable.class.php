<?php

namespace Library;

interface IMixable {


	/**
	 * retourne un élément affichable avec d'autre types
	 * @access public
	 * @param array $vars les paramètre servant à définir l'élément à afficher
	 * @return mixed; soit un objet de type Entity, soit un tableau d'Entity
	 */
	public function getPart(array $vars = array());


}
?>