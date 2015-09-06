<?php

namespace Library;

class LanguagesManager {

	protected static $language;
	protected static $languageNum;
	protected static $data;


	/**
	 * initialise les éléments statique de la classe
	 * @access public
	 * @param string $traductionFile le chemin du fichier de traduction
	 * @param string $selectedLanguage la langue séléctionnée
	 * @return void
	 */
	public static function init($traductionFile, $selectedLanguage) {
		// on récupère toutes les traductions
		if (($handle = fopen($traductionFile, 'r')) !== false) {
			while (($data = fgetcsv($handle)) !== false){
				// on fait correspondre $data['clé'] = array(valeur1 (=langue1) ,valeur2 (=langue2) ,..);
				$data = explode(';', $data[0]);
				self::$data[$data[0]] = array_slice($data, 1); 
			}
		}else{
			throw new \RunetimeException('Invalid filename for traduction.');
			
		}

		// on fait correspondre la langue par défaut à notre case de tableau
		foreach (self::$data['language'] as $key => $value) {
			if ($value == $selectedLanguage) {
				self::$languageNum = $key;
				self::$language = $value;
				define('LANGUAGE', $value);
				break;
			}
		}
	}

	/**
	 * retourne le tableau contenant toutes les langues disponibles
	 * @access public
	 * @return array le tableau des langues disponibles
	 */
	public static function getLanguages(){
		return self::$data['language'];
	}

	/**
	 * retourne la langue utilisée actuellement
	 * @access public
	 * @return String l'abréviation de la langue sélectionnée
	 */
	public static function getLanguage(){
		return self::$language;
	}

	/**
	 * renvoie un tableau contenant toutes les abréviation des langues gérées
	 * @access public
	 * @return array le tableau contenant les abréviations
	 */
	public static function getList() {
		return self::$data['language'];
	}


	/**
	 * récupère un message selon sa clé et la langue désirée
	 * @access public
	 * @param string $key clé du message à récupérer
	 * @return string le message dans la langue définie
	 */
	public static function get($key, $languageNumber = '') {
		if($languageNumber == ''){
			$languageNumber = self::$languageNum;
		}
		return self::$data[$key][$languageNumber];
	}


}
?>