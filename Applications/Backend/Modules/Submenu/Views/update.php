<?php
	echo $submenuForm->generateHeader().'
		<div class="adm_menu">
		<button type="button" class="deleteButton" onclick="
			if(confirm(\''.\Library\LanguagesManager::get('delete_confirm').'\')){
			self.location.href=\'delete-submenu-'.$id.'.html\'}">
			'.\Library\LanguagesManager::get('delete_button').'
		</button>
			<h2>'.\Library\LanguagesManager::get('text_page_information').'</h2>
		  	'.$submenuForm->generate().'
		</div>
		<hr class="separator"/>
		<div class="adm_content">
			'.$content.'
		</div>'
		.$submenuForm->generateFooter();






?>