<?php
/* Comme on est dans du contenu mixte, on va inclure à la suite les vues de chacun de ces contenu que l'on possède dans notre contentsList. 
 * Pour une meilleure lisibilité dans ces vues on renome la variables par le nom de la classe de l'entité en question.
 */
if (!empty($contentsList[0])) {		
	foreach ($contentsList as $content) {
		${strtolower($content['contentType'])} = $content['content'];
		require ROOT.DS.'Applications'.DS.'Frontend'.DS.'Modules'.DS.$content['contentType'].DS.'Views'.DS.'part.php';
	}
}

?>