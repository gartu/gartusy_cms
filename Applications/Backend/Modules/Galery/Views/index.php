<?php
	echo '<h2>'.\Library\LanguagesManager::get('title_manage_galery').'</h2>';
	
	if(!empty($galeries)){
		echo \Library\LanguagesManager::get('galery_list').' : 
			<select id="options">';
		foreach ($galeries as $galery) {
			echo '<option value="'.$galery->getId().'">'.$galery->getTitle().'</option>';
		}
		echo '</select>
		<br/>
		<button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/galery-\'+document.getElementById(\'options\').value+\'.html\'">'.\Library\LanguagesManager::get('modify_galery').'</button>';
		if(isset($actionOnLoad)){
			echo $actionOnLoad;
		}
	}else{
		echo \Library\LanguagesManager::get('no_galery_choice').'<br/><button type="button" onclick="self.location.href=\'/admin/'.\Library\LanguagesManager::getLanguage().'/insert-galery.html\'">'.\Library\LanguagesManager::get('create_galery').'</button>';
	}
?>