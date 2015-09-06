<?php
echo '<h2>'.\Library\LanguagesManager::get('title_modify_galery').'</h2>
		<button class="deleteButton" onclick="
			if(confirm(\''.\Library\LanguagesManager::get('delete_confirm').'\')){
			self.location.href=\'delete-galery-'.$id.'.html\'}">
			'.\Library\LanguagesManager::get('delete_button').'
		</button>
	 '.$form->generateHeader().$form->generate().'<br/>'.$form->generateFooter();
?>