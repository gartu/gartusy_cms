<?php

namespace Library\Models;

class FilesManager extends \Library\Manager {

	protected $filesDirectory;


	/**
	* Constructeur d'un manageur en lui passant son système de gestion 
	* @param object $dao; 	objet représentant l'interface de communication avec nos données stoquées
	**/
	public function __construct($dao){
		parent::__construct($dao);
		$this->filesDirectory = ROOT.DS.'Files'.DS;
	}



	/**
	 * permet de récupérer la liste des fichiers présent dans le dossier y relatif
	 * @access public
	 * @param array $types; la liste des extension à récupérer, rien par défaut si on désire la liste complète
	 * @return array la liste nom des fichiers (avec leurs extension)
	 */
	public function getList(array $types = array()) {
		$list = array();
		$directory = opendir($this->filesDirectory);

		while($entry = readdir($directory)) {
			if($entry != '.' && $entry != '..') {
				$split = explode('.', $entry);
				$count = count($split);

				// on ne serlectionne que les types demandé ou tout si rien n'a été spécifié
				if(empty($types) || in_array($split[$count - 1], $types)){
					$file = new \Library\Entities\File(array(
								'name' 		=> $entry,
								'shortName' => implode('.', explode('.', $entry, -1)),
								'directory' => $this->filesDirectory
								));

					$list[] = $file;
				}
			}
		}
		return $list;
	}


	/**
	 * Enregistre un fichier, gestion niveau bd, mais aucune base de donnée nécessaire pour les fichiers
	 * @access public
	 * @param le fichier
	 */
	public function saveFile($file, $language = LANGUAGE) {
		$res = true;
		// si le nom a été modifié
		if(implode('.', explode('.', $file->getName(), -1)) != $file->getShortName()){
			$tmp = explode('.', $file->getName());
			$ext = $tmp[count($tmp) - 1];

			$res = rename($file->getURL(), $file->getDirectory().$file->getShortName().'.'.$ext);
		}
		return $res;
	}

	/**
	 * 
	 * @access public
	 * @param int $file le fichier à supprimer
	 */
	public function delete($file) {
		unlink($file);
	}



	/** 
	 * 
	 * @param String; $file le fichier
	 * @param String; $destination l'emplacement de destination de stockage
	 * @return bool si l'opération a été réalisée avec succès ou non
	 */
	public function move($file, $destination){
		$resultat = move_uploaded_file($file, $destination);
		
		return $resultat;
	}

}
?>