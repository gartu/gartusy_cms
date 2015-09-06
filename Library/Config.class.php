<?php

namespace Library;

class Config {

	protected  $vars = array();

	/**
	 * Permet de récupérer un paramètre spécifique.
	 * @access public
	 * @param string $name; le nom du paramètre à récupérer
	 * @return string;		la valeur du paramètre, null s'il n'existe pas
	 */
	public function getParam($name) {
		// on ne va pas parser le fichier si aucun paramètre n'est récupérer
		// mais si on le parse alors on récupére tout
		if (empty($vars)) {
			$xml = new \DOMDocument;
			$xml->load(ROOT.DS.'Library'.DS.'parameters.xml');

			$elements = $xml->getElementsByTagName('define');

			foreach ($elements as $element) {
				$this->vars[$element->getAttribute('param')] = $element->getAttribute('value');
			}			
		}

		return (isset($this->vars[$name])) ? $this->vars[$name] : null;
	}

	/**
	 * Permet de récupérer une liste d'éléments selon leur tag name
	 * @access public
	 * @param string $tagName; le nom de la liste de la tag name à récupérer
	 * @return array la liste à récupérer
	 */
	public function getList($tagName) {

		$xml = new \DOMDocument;
		$xml->load(ROOT.DS.'Library'.DS.'parameters.xml');

		$elements = $xml->getElementsByTagName($tagName);

		foreach ($elements as $element) {
			$list[$element->getAttribute('param')] = $element->getAttribute('value');
		}			

		return (isset($list)) ? $list : null;
	}

}
?>
