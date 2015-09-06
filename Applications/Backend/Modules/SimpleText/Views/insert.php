<?php
echo '<h2>'.\Library\LanguagesManager::get('title_add_text').'</h2>
			'.$form->generateHeader().$form->generate().'<br/>
			'.$form->generateSubmit().$form->generateFooter();
?>