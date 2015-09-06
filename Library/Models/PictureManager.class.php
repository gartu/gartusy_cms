<?php

namespace Library\Models;

class PictureManager extends \Library\Manager {

	protected $lastInsertPictureId;
	protected $imageDirectory;
	protected $thumbnailsDirectory;

	/**
	* Constructeur d'un manager de Picture
	* @param object $dao; objet représentant l'interface de communication avec nos données stoquées
	**/
	public function __construct($dao){
		parent::__construct($dao);
		$this->lastInsertPictureId = -1;
	}


	/**
	 * permet de spécifier le dossier contenant les images réduites physiquement
	 * @access public
	 * @param String $thumbnailsDirectory le chemin d'accès aux image réduites
	 */
	public function setThumbnailsDirectory($thumbnailsDirectory) {
		$this->thumbnailsDirectory = $thumbnailsDirectory;
	}

	/**
	 * permet de spécifier le dossier contenant les images physiquement
	 * @access public
	 * @param String $imageDirectory le chemin d'accès aux image
	 */
	public function setImageDirectory($imageDirectory) {
		$this->imageDirectory = $imageDirectory;
	}


	/**
	 * permet de récupérer le dernier id d'image ayant été inséré
	 * @access public
	 * @return Integer l'id de la dernière image insérée
	 */
	public function getLastInsertPictureId() {
		return $this->lastInsertPictureId;
	}


	/**
	 * permet de récupérer une image selon son id
	 * @access public
	 * @param Integer $id, l'id de l'image
	 * @return \Library\Entities\Picture l'image correspondante
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, format, name, description
										FROM v_picture
										WHERE language = :language AND id = :id');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Picture');

		$picture = $request->fetch();

		return $picture;
	}


	/**
	 * permet de récupérer la liste des images composant une galerie
	 * @access public
	 * @param Integer $galeryId, l'id de la galerie dont on souhaite récupérer les images
	 * @return array la liste des images relative à la galerie
	 */
	public function getGaleryPictures($galeryId, $language = LANGUAGE) {
		// on récupère les champs
		$request = $this->dao->prepare('SELECT id, format, name, description
										FROM v_picture
										WHERE language = :language AND galeryId = :id');
		$request->bindValue(':id', $galeryId, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Picture');

		$pictures = $request->fetchAll();

		return $pictures;
	}


	/**
	 * Récupère la liste des id des images de la galerie
	 * @access public
	 * @param Interger id l'id de la galerie dont l'on désire récupèrer les id des images
	 * @return array la liste des id des images de la galerie
	 */
	public function getGaleryPicturesIds($galeryId) {
		$request = $this->dao->prepare('SELECT id
										FROM picture
										WHERE galery_id = :id');
		$request->bindValue(':id', $galeryId, \PDO::PARAM_INT);
		$request->execute();

		$arrayTmp = $request->fetchAll();
		$result = array();
		foreach ($arrayTmp as $tmp) {
			$result[] = $tmp[0];
		}

		return $result;
	}


	/**
	 * supprime la représentation d'une image en base de données
	 * @access public
	 * @param int $pictureId l'id de l'image à supprimer
	 */
	public function delete($pictureId) {

		$request = $this->dao->prepare('SELECT format 
										FROM picture
										WHERE id = :id');
		$request->bindValue(':id', $pictureId, \PDO::PARAM_INT);
		$request->execute();
		$format = $request->fetch();

		$request = $this->dao->prepare('DELETE
										FROM picture_has_language 
										WHERE picture_id = :id');
		$request->bindValue(':id', $pictureId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM picture
										WHERE id = :id');
		$request->bindValue(':id', $pictureId, \PDO::PARAM_INT);
		$request->execute();
		
		$this->deletePicture($this->imageDirectory.$pictureId.'.'.$format[0]);
		$this->deletePicture($this->thumbnailsDirectory.$pictureId.'.'.$format[0]);
	}


	/**
	 * Enregistre une image en bd
	 * @access public
	 * @param la picture
	 */
	public function savePicture($picture, $galeryId = 0, $language = LANGUAGE) {

		if(!is_null($picture->getId())){
			$request = $this->dao->prepare('UPDATE picture_has_language
											SET name = :name, description = :description 
											WHERE picture_id = :id');
			$request->bindValue(':id', $picture->getId(), \PDO::PARAM_INT);
			$request->bindValue(':name', $picture->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $picture->getDescription(), \PDO::PARAM_STR);
			$request->execute();
		}else{
			$request = $this->dao->prepare('INSERT INTO picture (galery_id, format) 
													VALUES (:galeryId, :format)');
			$request->bindValue(':galeryId', ($galeryId == 0) ? null : $galeryId, \PDO::PARAM_INT);
			$request->bindValue(':format', $picture->getFormat(), \PDO::PARAM_STR);
			$request->execute();

			$this->lastInsertPictureId = $this->getLastInsertId();
			$request = $this->dao->prepare('INSERT INTO picture_has_language (picture_id, language_abbreviation, name, description) 
											VALUES (:pictureId, :language, :name, :description)');
			$request->bindValue(':pictureId', $this->lastInsertPictureId, \PDO::PARAM_INT);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->bindValue(':name', $picture->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $picture->getDescription(), \PDO::PARAM_STR);
			$request->execute();

			// on ajoute le contenu "vide" dans les autres langues
			foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
				if($value != $language){
					$request = $this->dao->prepare('INSERT INTO picture_has_language (picture_id, language_abbreviation, name, description) 
													VALUES (:pictureId, :language, :name, :description)');
					$request->bindValue(':pictureId', $this->lastInsertPictureId, \PDO::PARAM_INT);
					$request->bindValue(':name', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
					$request->bindValue(':description', '', \PDO::PARAM_STR);
					$request->bindValue(':language', $value, \PDO::PARAM_STR);
					$request->execute();
				}
			}
		}
	}


	/** 
	 * Ajoute un fichier image sur le serveur
	 * @param String; $file le fichier représentant l'image
	 * @param String; $destination l'emplacement de destination de stockage
	 * @return bool si l'opération a été réalisée avec succès ou non
	 */
	public function movePicture($file, $destination){
		$resultat = move_uploaded_file($file, $destination);
		
		return $resultat;
	}


	/** 
	 * Supprime une image physiquement sur le serveur
	 * @param String; $name le nom de stockage de l'image
	 * @return Bool; le succès de l'opération
	 */
	public function deletePicture($name){
		unlink($name);
	}

	/**
	 * Ajoute une image sur le serveur, la redimentionne si elle est plus grande que la limite
	 * @param String; $file le fichier représentant l'image
	 * @param String; $destination l'emplacement de destination de stockage
	 * @param int $limit; la taille limite que l'image peut adopter (maximale)
	 */
	public function placeImage($file, $destination, $limit){
		$taille = getimagesize($file);

		// si elle est trop grande, on a redimentionne
		if($taille[0] > $limit || $taille[1] > $limit){
			$this->resizeImage($file, $destination, $limit, $limit);
		}else{
			$this->movePicture($file, $destination);
		}
	}


    /**
    * source : www.petitcode.com 
    * 
    * @param $file_src : Le chemin de l'image source (), l'image qui va être redimensionnée
    * @param $file_dest : Le chemin de la nouvelle image, qui va être créée. Si vous voulez écraser la première image, mettez le même chemin dans $file_src et $file_dest.
    * @param $new_width : La nouvelle largeur en pixel
    * @param $new_height : La nouvelle hauteur en pixel
    * @param $proportional : Argument boolean optionnel, si égale à true alors les dimensions de l'image de destination seront proportionnelles à ceux de l'image source, et donc pas forcement $new_width x $new_height,
    * 												sinon les dimensions seront exactement $new_width x  $new_height
    */
    
	function resizeImage($file_src, $file_dest, $new_width, $new_height, $proportional=true)
	{		
		$attr=getimagesize($file_src);
		$fw=$attr[0]/$new_width;
		$fh=$attr[1]/$new_height;
		
		if($proportional)
			$f=$fw>$fh?$fw:$fh;
		else
			$f=$fw>$fh?$fh:$fw;

		$w=$attr[0]/$f;
		$h=$attr[1]/$f;
        
		$file_src_infos=pathinfo($file_dest);
		
		$ext=strtolower($file_src_infos['extension']);
		if($ext=="jpg")
			$ext="jpeg";
		
		$func="imagecreatefrom".$ext;
		$src  = $func($file_src);
		
		// Création de l'image de destination. La taille de la miniature sera wxh 
		$x=0;
		$y=0;
		if($proportional)
			$dest = imagecreatetruecolor($w,$h);
		else
		{
			$dest = imagecreatetruecolor($new_width,$new_height);
			$x=($new_width-$w)/2;
			$y=($new_height-$h)/2;
		}

		// Configuration du canal alpha pour la transparence
		imagealphablending($dest,false);
 		imagesavealpha($dest,true);

		// Redimensionnement de src sur dest 
		imagecopyresampled($dest,$src,$x,$y,0,0,$w,$h,$attr[0],$attr[1]);

		$func="image".$ext;
		$func($dest, $file_dest);
		imagedestroy($dest);
		
		return true;		
	}

}
?>