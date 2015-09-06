<?php
	foreach ($usersList as $user) {
		echo '<a style="text-decoration:none" href="/admin/'.\Library\LanguagesManager::getLanguage().'/contenu-utilisateur-'.$user->getId().'.html"><span>'.\Library\LanguagesManager::get('login').' : '.$user->getLogin().'&ensp;
			  '.\Library\LanguagesManager::get('first_name').' : '.$user->getName().'&ensp;
			  '.\Library\LanguagesManager::get('surname').' : '.$user->getSurname().'</span></a><br/>';
	}
?>