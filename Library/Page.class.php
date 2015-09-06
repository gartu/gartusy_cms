<?php

namespace Library;

/*	Classe définissant une page, avec ses différente variables à passer à la vue
 *	ainsi qu'avec la capacité de générer la page y relative, hérite de ApplicationComponent
 */
class Page extends ApplicationComponent {

	protected  $contentFile;
	protected  $vars;


	/**
	 * Méthode permettant d'ajouter une variable à notre page, celle-ci sera reprise dans notre vue
	 * @access public
	 * @param string $name;	nom de la variable 
	 * @param mixed $value;	valeur à attribuer à la variable
	 * @return void
	 */
	public function addVar($name, $value) {
		if (!is_string($name) || is_numeric($name) || empty($name)) {
			throw new \InvalidArgumentException('La nom de la variable doit être une chaine de caractères');
		}
		$this->vars[$name] = $value;
	}


	/**
	 * Permet de récupérer la vue de la page
	 * @access public
	 * @return string 	renvoie le contenu de la vue
	 */
	public function getGeneratedPage() {
		if (!file_exists($this->contentFile)) {
			throw new \RuntimeException('Le fichier de vue spécifié n\'existe pas');
		}

		$currentUser = $this->app->getCurrentUser();
		
		$content = $this->getGeneratedContentPage();

		// on récupère les menus correspondants, ceux publique, privé ou backend
		$menus = new \Library\Menus($this->app);
		$menu = $menus->getUserMenu();
		
		if (!empty($this->vars) && isset($this->vars['imgEdit'])) {
			$imgEdit = $this->vars['imgEdit'];
		}

		$requestURI = $this->app->getHttpRequest()->getRequestURI();

		ob_start();
			require ROOT.DS.'Applications'.DS.$this->app->getName().DS.'Templates'.DS.'layout.php';
		return ob_get_clean();
	}


	/**
	 * Permet de récupérer la vue interne de la page (du module)
	 * @access public
	 * @return string renvoie le contenu de la vue du module de la page
	 */
	public function getGeneratedContentPage() {

		// on créé les variables propre à notre page et utilisées dans la vue, puis on génére celle-ci
		// et on ensuite on la place en contexte dans le layout de l'application
		if (!empty($this->vars)) {
			extract($this->vars);	
		}

		ob_start();
			require $this->contentFile;
		return ob_get_clean();
	}

	/**
	 * Définit le fichier contenu la vue du contenu
	 * @access public
	 * @param string $contentFile;	nom du fichier représentant la vue
	 * @return void
	 */
	public function setContentFile($contentFile) {
		if (!is_string($contentFile) || empty($contentFile)) {
			throw new \InvalidArgumentException('Le fichier spécifié pour la vue n\'existe pas');
		}
		$this->contentFile = $contentFile;
	}


}
?>