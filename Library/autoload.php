<?php

/**
* fonction permettant de charger le fichier définissant la classe passée en paramètre
* @param $class string; nom de la class devant être ajoutée incluant son espace de nom
**/
function autoload($class){
	require ROOT.DS.str_replace('\\', DS, $class).'.class.php';
}

spl_autoload_register('autoload');

?>