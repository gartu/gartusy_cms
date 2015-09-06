<?php
	echo '<h2>'.\Library\LanguagesManager::get('title_manage_contact_form').'</h2>';
	
	if(!empty($forms)){
		if(empty($selected)){
			$selected = $forms[0]->getId();
			// si des fomulaires existes, alors on enregistre tout de suite la sélection du premier (petit trick sur le onload d'une image)
			$actionOnLoad = '<input type="image" src="'.DS.'Applications'.DS.'Backend'.DS.'Templates'.DS.'img'.DS.'save.png" onload="javascript:saveData()" style="display:none"/>';
		}
		echo \Library\LanguagesManager::get('form_choice').' : 
			<select name="'.$typeMenu.'_options" onchange="javascript:saveData()">'; // document.location.href=location.href.replace(\'/'.LANGUAGE.'/\', \'/\'+this[this.selectedIndex].value+\'/\')>"';
		foreach ($forms as $form) {
			echo '<option';
			if($form->getId() === $selected){
				echo ' selected="selected"';
			}
			echo ' value="'.$form->getId().'">'.$form->getName().'</option>';
		}
		echo '</select><br/><button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/formulaire-'.$selected.'.html\'">'.\Library\LanguagesManager::get('modify_form').'</button>';
		if(isset($actionOnLoad)){
			echo $actionOnLoad;
		}
	}else{
		echo \Library\LanguagesManager::get('no_form_choice').'<br/><button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/insert-form.html\'">'.\Library\LanguagesManager::get('create_form').'</button>';
	}
?>