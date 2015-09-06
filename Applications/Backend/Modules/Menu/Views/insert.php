<?php
echo '<h2>'.\Library\LanguagesManager::get('title_add_menu').'</h2>
	 '.$menuForm->generateHeader().$menuForm->generate().'<br/>'.$menuForm->generateSubmit().$menuForm->generateFooter();
?>