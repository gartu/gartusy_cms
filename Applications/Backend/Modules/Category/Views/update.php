<?php
echo '<h2>'.\Library\LanguagesManager::get('title_modify_category').'</h2>
		<form action="" method="post">
			'.$form.'<br/>
			<input type="submit" value="'.\Library\LanguagesManager::get('modify').'" />
		</form>';
?>