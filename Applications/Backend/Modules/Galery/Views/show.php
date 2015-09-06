<?php
	echo '<h2>'.\Library\LanguagesManager::get('title_manage_galery').'</h2>';

	if(!empty($galeries)){
		if(empty($selected)){
			$selected = $galeries[0]->getId();
			// si des fomulaires existes, alors on enregistre tout de suite la sélection du premier (petit trick sur le onload d'une image)
			$actionOnLoad = '<input type="image" src="'.DS.'Applications'.DS.'Backend'.DS.'Templates'.DS.'img'.DS.'save.png" onload="javascript:saveData()" style="display:none"/>';
		}
		echo \Library\LanguagesManager::get('galery_choice').' : 
			<select name="'.$typeMenu.'_options" onchange="javascript:saveData()">'; // document.location.href=location.href.replace(\'/'.LANGUAGE.'/\', \'/\'+this[this.selectedIndex].value+\'/\')>"';
		foreach ($galeries as $galery) {
			echo '<option';
			if($galery->getId() === $selected){
				echo ' selected="selected"';
			}
			echo ' value="'.$galery->getId().'">'.$galery->getTitle().'</option>';
		}
		echo '</select><br/><button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/galery-'.$selected.'.html\'">'.\Library\LanguagesManager::get('modify_galery').'</button>';
		if(isset($actionOnLoad)){
			echo $actionOnLoad;
		}
	}else{
		echo \Library\LanguagesManager::get('no_galery_choice').'<br/><button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/insert-galery.html\'">'.\Library\LanguagesManager::get('create_galery').'</button>';
	}
?>