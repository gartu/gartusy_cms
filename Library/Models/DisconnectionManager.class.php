<?php

namespace Library\Models;

class DisconnectionManager extends \Library\Manager {


	/**
	 * @access public
	 * @param CurrentUser $currentUser l'utilisateur courrant
	 * @return void
	 */
	public function dropConnection($currentUser) {

		// on supprime toutes les sessions
		$currentUser->cleanAllAttributes();
	}


}
?>