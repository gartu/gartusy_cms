<?php
	echo '<h2>'.\Library\LanguagesManager::get('title_manage_files').'</h2>';

if($htmlForm != ''){
	echo $htmlForm;
}else{
	echo \Library\LanguagesManager::get('no_files');
}

?>