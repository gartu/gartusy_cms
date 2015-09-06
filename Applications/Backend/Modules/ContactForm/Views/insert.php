<?php
echo '<h2>'.\Library\LanguagesManager::get('title_add_contact_form').'</h2>
	 '.$form->generateHeader().$form->generate().'<br/>'.$form->generateSubmit().$form->generateFooter();
?>