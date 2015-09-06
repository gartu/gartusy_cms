<?php
	echo '<h2>'.\Library\LanguagesManager::get('title_manage_contact_form').'</h2>';
	
	if(!empty($forms)){
		echo \Library\LanguagesManager::get('form_list').' : 
			<select id="options">';
		foreach ($forms as $form) {
			echo '<option value="'.$form->getId().'">'.$form->getName().'</option>';
		}
		echo '</select>
		<br/>
		<button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/formulaire-\'+document.getElementById(\'options\').value+\'.html\'">'.\Library\LanguagesManager::get('modify_form').'</button>';
		if(isset($actionOnLoad)){
			echo $actionOnLoad;
		}
	}else{
		echo \Library\LanguagesManager::get('no_form_choice').'<br/><button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/insert-form.html\'">'.\Library\LanguagesManager::get('create_form').'</button>';
	}
?>