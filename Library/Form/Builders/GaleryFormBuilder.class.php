<?php

namespace Library\Form\Builders;

class GaleryFormBuilder extends \Library\Form\Builders\FormBuilder {

	protected $urlImage;

	/**
	 * constructeur d'un formulaire pour la création de galeries
	 * @access public
	 * @param Entity $entity l'entité sur la base de laquelle le formulaire va être créer
	 * @param String $urlImage l'emplacement ou les images sont stockées
	 * @return void
	 */
	public function __construct(\Library\Entity $entity, $urlImage) {
		parent::__construct($entity);
		$this->urlImage = $urlImage;
	}

	/**
	 * Permet de créer le formulaire propre aux galeries
	 * @access public
	 * @param String les paramètres des champs, receptionné via url
	 * @return void
	 */
	public function build($strParams = null) {

		$this->form->add(new \Library\Form\Elements\StringField(array(
			'label' 	=> \Library\LanguagesManager::get('title'),
			'name'		=> 'title',
			'maxLength' => 90,
			'classes'	=> 'alignment',
			'value'		=> $this->form->getEntity()->getTitle(),
			'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le titre est trop long (max 90 caractères)', 90), 
								 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un titre.'))
			)));

		$this->form->add(new \Library\Form\Elements\TextField(array(
			'label' 	=> \Library\LanguagesManager::get('description'),
			'name'		=> 'description',
			'rows'		=> 6,
			'cols'		=> 72,
			'parameter' => '-editor',
			'value'		=> $this->form->getEntity()->getDescription()
			)));

		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'id'	=> 'id',
			'name'	=> 'id',
			'value'	=> $this->form->getEntity()->getId()
			)));


		$picturesList = $this->form->getEntity()->getPictures();
		$i = 0;
		if(count($picturesList) !== 0){
			// fonctionne de pair avec la fonction javascript 'pictureSuppression(id)'
			do{

				$this->form->add(new \Library\Form\Elements\Image(array(
					'name'	=> 'image'.$i,
					'clear' => 1,
					'src'	=> '../../../../'.$this->urlImage.$picturesList[$i]->getUrl()
					)));

				$this->form->add(new \Library\Form\Elements\HiddenField(array(
					'id'	=> 'pictureId'.$i,
					'name'	=> 'pictureId'.$i,
					'value'	=> $picturesList[$i]->getId()
					)));

				$this->form->add(new \Library\Form\Elements\StringField(array(
					'label' 	=> \Library\LanguagesManager::get('name'),
					'name'		=> 'pictureName'.$i,
					'maxLength' => 35,
					'classes'	=> 'alignment',
					'value'		=> $picturesList[$i]->getName(),
					'validators'=> array(new \Library\Form\Validators\MaxLengthValidator('Le nom est trop long (max 35 caractères)', 35), 
										 new \Library\Form\Validators\NotNullValidator('Il vous faut entrer un nom.'))
					)));

				$this->form->add(new \Library\Form\Elements\TextField(array(
					'label' 	=> \Library\LanguagesManager::get('description'),
					'name'		=> 'pictureDescription'.$i,
					'rows'		=> 4,
					'cols'		=> 18,
					'clear' 	=> 1,
					'classes'	=> 'alignment',
					'value'		=> $picturesList[$i]->getDescription()
					)));

				$this->form->add(new \Library\Form\Elements\HiddenField(array(
					'id'	=> 'pictureFormat'.$i,
					'name'	=> 'pictureFormat'.$i,
					'value'	=> $picturesList[$i]->getFormat()
					)));

				$this->form->add(new \Library\Form\Elements\HiddenField(array(
					'id'	=> 'suppression'.$i,
					'name'	=> 'suppression'.$i,
					'value'	=> 0
					)));

				$this->form->add(new \Library\Form\Elements\Button(array(
					'label'   => \Library\LanguagesManager::get('remove_picture'),
					'name'	  => 'remove',
					'clear'   => 1,
					'onClick' => 'pictureSuppression('.$i.', "'.\Library\LanguagesManager::get('supp_picture_confirmation').'")'
					)));

			}while(++$i < count($picturesList));
		}else{
			$this->form->add(new \Library\Form\Elements\Text(array(
				'label' => \Library\LanguagesManager::get('no_picture_in_galery'),
				'name'	=> 'text'.$i,
				'clear' => 1
			)));
			
		}


		$this->form->add(new \Library\Form\Elements\HiddenField(array(
			'id'	=> 'numberPicture',
			'name'	=> 'numberPicture',
			'value'	=> $i
			)));

		$this->form->add(new \Library\Form\Elements\FileField(array(
			'label'    => \Library\LanguagesManager::get('add_picture'),
			'name'	   => 'upload',
			'onChange' => 'setEnctype(\'multipart/form-data\');saveData();'
			)));

	}


}
?>