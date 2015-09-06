<?php
	foreach ($categoriesList as $category) {
		echo '<a style="text-decoration:none" href="/admin/contenu-categorie-'.$category->getId().'.html"><div style="float:left;padding:10px;margin-right:10px">Nom : '.$category->getName().'<br/>
			  Droits : <br/>';
		$rights = $category->getRights();
		$accepted = '';
		foreach ($rights as $right => $value) {
			if (!is_numeric($right) && $value == 1) {
				$accepted .= '&ensp;&ensp;'.str_replace('_', ' ', $right).'<br/>';
			}
		}
		echo $accepted != '' ? $accepted : \Library\LanguagesManager::get('no_rights');
		echo '</div></a>';
	}
?>