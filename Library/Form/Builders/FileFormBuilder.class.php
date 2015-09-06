<?php

namespace Library\Form\Builders;

class FileFormBuilder extends \Library\Form\Builders\FormBuilder {

	protected $urlFile;

	/**
	 * constructeur d'un formulaire pour un fichier
	 * @access public
	 * @param Entity $entity l'entité sur la base de laquelle le formulaire va être créer
	 * @param String $urlFile le chemin aux fichiers
	 * @return void
	 */
	public function __construct(\Library\Entity $entity, $urlFile) {
		parent::__construct($entity);
		$this->urlFile = $urlFile;
	}

	/**
	 * Permet de créer le formulaire propre aux news
	 * @access public
	 * @return void
	 */
	public function build() {
		
		$file = $this->form->getEntity();
	
		if($file->getName() == ''){

			$this->form->add(new \Library\Form\Elements\FileField(array(
				'label'    => \Library\LanguagesManager::get('add_file'),
				'name'	   => 'upload',
				'onChange' => 'setEnctype(\'multipart/form-data\');saveData();'
				)));

		}else{

			$this->form->add(new \Library\Form\Elements\StringField(array(
				'label'		 => \Library\LanguagesManager::get('file'),
				'name'		 => 'shortName',
				'value'		 => implode('.', explode('.', $file->getURL(), -1)),
				'validators' => array(new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'))
				)));

			$this->form->add(new \Library\Form\Elements\HiddenField(array(
				'name'	=> 'name'
				)));
		}
		
	}


}
?>