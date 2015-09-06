<?php
	echo '<p>
	'.\Library\LanguagesManager::get('login').' 	 : '.$user->getLogin().'<br/>
	'.\Library\LanguagesManager::get('first_name').' : '.$user->getName().'<br/>
	'.\Library\LanguagesManager::get('surname').' 	 : '.$user->getSurname().'</p>'

?>