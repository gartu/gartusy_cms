<?php

namespace Library\Form\Builders;

class NewsFormBuilder extends \Library\Form\Builders\FormBuilder {

	protected $newsSubjectManager;
	protected $urlImage;

	/**
	 * constructeur d'un formulaire pour les news
	 * @access public
	 * @param Entity $entity l'entité sur la base de laquelle le formulaire va être créer
	 * @param Manager $newsSubjectManager la manager des sujets de news afin de récupérer la liste de ceux-ci
	 * @param String $urlImage le chemin aux images
	 * @return void
	 */
	public function __construct(\Library\Entity $entity, \Library\Manager $newsSubjectManager, $urlImage) {
		parent::__construct($entity);
		$this->newsSubjectManager = $newsSubjectManager;
		$this->urlImage = $urlImage;
	}

	/**
	 * Permet de créer le formulaire propre aux news
	 * @access public
	 * @return void
	 */
	public function build() {
		
		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('title'),
			'name'		=> 'title',
			'maxLength' => 100,
			'classes'	=> 'alignment',
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le titre est trop long (max 100 caractères)', 100), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'))
			)));

		// notre formulaire contient la liste des catégorie, on doit donc les récupérer via notre manager
		$newsSubjectList = $this->newsSubjectManager->getList();
		foreach ($newsSubjectList as $newsSubject) {
			$newsSubjectArray[$newsSubject->getName()] = $newsSubject->getId();
		}

		$selectData = array(
				'label' 	=> \Library\LanguagesManager::get('news_subject'),
				'name' 		=> 'newsSubjectId',
				'classes'	=> 'alignment',
				'options'	=> $newsSubjectArray,
				'clear'		=> 1,
				'validators' => array(new \Library\Form\Validators\NotNullValidator('Le choix d\'un sujet est obligatoire.'))
			);
		// On place la valeur par défaut si celle-ci a déjà été selectionnée
		if (!is_null($this->form->getEntity()->getSubjectId())){
			$selectData['value'] = $this->form->getEntity()->getSubjectId();
		}

		// on créé notre champs de selection multiple
		$this->form->add(new \Library\Form\Elements\SelectField($selectData));


		$this->form->add(new \Library\Form\Elements\CheckboxField(array(
			'label' 	=> \Library\LanguagesManager::get('visibility'),
			'name'		=> 'visible',
			'value'		=> (is_null($this->form->getEntity()->getVisible()) ? '1' : $this->form->getEntity()->getVisible())
		)));


		$this->form->add(new \Library\Form\Elements\TextField(array(
			'label' 	=> \Library\LanguagesManager::get('content'),
			'name'		=> 'content',
			'rows'		=> 20,
			'alone'		=> true,
			'parameter' => '-editor',
			'validators'=> array(new \Library\Form\Validators\NotNullValidator('Il vous faut entrer du contenu.'))
			)));


		$picture = $this->form->getEntity()->getPicture();
		if(is_null($picture)){

			$this->form->add(new \Library\Form\Elements\FileField(array(
				'label'    => \Library\LanguagesManager::get('add_picture'),
				'name'	   => 'upload',
				'onChange' => 'setEnctype(\'multipart/form-data\');saveData();'
				)));

		}else{

			$this->form->add(new \Library\Form\Elements\Image(array(
				'name'	=> 'picture',
				'clear' => 1,
				'src'	=> '../../../../'.$this->urlImage.$picture->getUrl()
				)));

			$this->form->add(new \Library\Form\Elements\HiddenField(array(
				'id'	=> 'pictureId',
				'name'	=> 'pictureId',
				'value'	=> $picture->getId()
				)));

			$this->form->add(new \Library\Form\Elements\HiddenField(array(
				'id'	=> 'pictureFormat',
				'name'	=> 'pictureFormat',
				'value'	=> $picture->getFormat()
				)));

			$this->form->add(new \Library\Form\Elements\HiddenField(array(
				'id'	=> 'suppression',
				'name'	=> 'suppression',
				'value'	=> 0
				)));

			$this->form->add(new \Library\Form\Elements\Button(array(
				'label'   => \Library\LanguagesManager::get('remove_picture'),
				'name'	  => 'remove',
				'clear'   => 1,
				'onClick' => 'pictureSuppression("", "'.\Library\LanguagesManager::get('supp_picture_confirmation').'")'
				)));

		}

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'name'	=> 'id',
			'value'	=> $this->form->getEntity()->getId()
			)));

	}


}
?>