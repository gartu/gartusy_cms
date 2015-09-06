<?php
echo '<h2>'.\Library\LanguagesManager::get('title_modify_contact_form').'</h2>
		<button class="deleteButton" onclick="
			if(confirm(\''.\Library\LanguagesManager::get('delete_confirm').'\')){
			self.location.href=\'delete-form-'.$id.'.html\'}">
			'.\Library\LanguagesManager::get('delete_button').'
		</button>
	 '.$form->generateHeader().$form->generate().'<br/>'.$form->generateFooter();
?>