<?php
echo '<h2>'.\Library\LanguagesManager::get('title_add_submenu').'</h2>
'.$submenuForm->generateHeader().$submenuForm->generate().'<br/>'.$submenuForm->generateSubmit().$submenuForm->generateFooter();
?>