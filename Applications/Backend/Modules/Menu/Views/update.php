<?php
	echo '<div class="adm_menu">
			<button type="button" class="deleteButton" onclick="
				if(confirm(\''.\Library\LanguagesManager::get('delete_confirm').'\')){
				self.location.href=\'delete-menu-'.$id.'.html\'}">
				'.\Library\LanguagesManager::get('delete_button').'
			</button>
			<h2>'.\Library\LanguagesManager::get('text_page_information').'</h2>
	 		<button onclick="self.location.href=\'insert-submenu-'.$id.'.html\'">'.\Library\LanguagesManager::get('add_submenu').'</button><br/>
		  	'.$menuForm->generateHeader().$menuForm->generate().'
		</div>
		<hr class="separator"/>
		<div class="adm_content">
			'.$content.'
		</div>'
		.$menuForm->generateFooter();






?>