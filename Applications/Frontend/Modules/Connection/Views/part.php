<?php
echo '<h2>'.\Library\LanguagesManager::get('connection').'</h2>
<form action="connexion.html" method="post">
	<label for="pseudo">'.\Library\LanguagesManager::get('login').'<br/></label>
	<input type="text" id="pseudo" name="login" placeholder="'. 
		(isset($connection) || !empty($connection)) ? $connection : \Library\LanguagesManager::get('login');
	 .'"/>
	 <br/>
	<label for="pass">'.\Library\LanguagesManager::get('pwd').'<br/></label><input type="password" id="pass" name="password" placeholder="'.\Library\LanguagesManager::get('pwd').'"/><br/>
	<input type="submit" value="'.\Library\LanguagesManager::get('connection').'"/>
</form>';
?>
