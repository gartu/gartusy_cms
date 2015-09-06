<?php

namespace Library\Models;

class ContactFormManager extends \Library\Manager {


	/**
	 * @access public
	 * @param int $first le premier élément à récupérer
	 * @param int $limit le nombre d'élément à récupérer
	 * @return array la liste des formulaire dant la tranche
	 */
	public function getList($first = -1, $limit = -1, $language = LANGUAGE) {
		
		$sql = 'SELECT id, name, description, defaultReceiver, receiver
				FROM v_form
				WHERE language = :language';

		if ((is_numeric($first) && is_numeric($limit)) && ($first != -1 || $limit != -1)) {
			$sql .= ' LIMIT '.(int)$limit.' OFFSET '.(int)$first;
		}

		$request = $this->dao->prepare($sql);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\ContactForm');

		$formList = $request->fetchAll();
		return $formList;
	}



	/**
	 * permet de récupérer la liste des champs selon l'id du formulaire y relatif
	 * @access public
	 * @return array la liste des formulaire dant la tranche de type \Library\Entities\FieldType.class
	 */
	public function getFieldList($formId, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT fieldId AS id, fieldName AS name, fieldDescription AS description, fieldHelp AS help, metric, required
										FROM v_form_has_field
										WHERE language = :language AND formId = :id
										ORDER BY metric');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Field');

		$fieldList = $request->fetchAll();

		// on récupère les types des champs
		$request = $this->dao->prepare('SELECT fieldTypeId AS id, fieldType AS type, fieldTypeName AS name
										FROM v_form_has_field
										WHERE language = :language AND formId = :id
										ORDER BY metric');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\FieldType');

		$fieldTypeList = $request->fetchAll();

		$i = 0;
		foreach ($fieldList as $field) {
			$field->setFieldType($fieldTypeList[$i]->getType());
			$field->setFieldTypeName($fieldTypeList[$i]->getName());
			$field->setFieldTypeId($fieldTypeList[$i]->getId());
			$i += 1;
		}

		return $fieldList;
	}


	/**
	 * permet de récupérer la liste des différents types de champs
	 * @access public
	 * @return array la liste des formulaire dant la tranche de type \Library\Entities\FieldType.class
	 */
	public function getFieldTypeList($language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, type
										FROM v_field 
										WHERE language = :language');
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\FieldType');

		$fieldTypeList = $request->fetchAll();
		return $fieldTypeList;
	}

	/**
	 * permet de récupérer un type de champs selon son id
	 * @access public
	 * @param $id l'id du type de champs demandé
	 * @return FieldType le type de champs correpondant à l'id (\Library\Entities\FieldType.class)
	 */
	public function getFieldTypeById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, type
										FROM v_field 
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\FieldType');

		$fieldType = $request->fetch();
		return $fieldType;
	}

	/**
	 * Récupère une formulaire de contact selon son ID
	 * @access public
	 * @param int $id l'id du formulaire à récupérer
	 * @return ContactForm le formulaire de contact correspondant à l'id
	 */
	public function getById($id, $language = LANGUAGE) {
		$request = $this->dao->prepare('SELECT id, name, description, defaultReceiver, receiver
										FROM v_form
										WHERE id = :id AND language = :language');
		$request->bindValue(':id', $id, \PDO::PARAM_INT);
		$request->bindValue(':language', $language, \PDO::PARAM_STR);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\ContactForm');

		$form = $request->fetch();

		// on ajoute ensuite les champs qui composent le formulaire
		$form->setFields($this->getFieldList($id));

		return $form;
	}


	/**
	 * Récupère la liste des id des champs du formulaire
	 * @access public
	 * @param Interger id l'id du formulaire dont l'on désire récupèrer les id de champs
	 * @return array la liste des id des champs du formulaire
	 */
	public function getFieldsIds($formId) {
		$request = $this->dao->prepare('SELECT id
										FROM form_has_field
										WHERE form_id = :id');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->execute();
		$tmp = $request->fetchAll();
		$res = array();
		// on ne renvoie qu'un tableau des id
		foreach ($tmp as $elem) {
			$res[] = $elem[0];
		}
		return $res;
	}

	/**
	 * supprime la représentation d'un formulaire en base de données
	 * @access public
	 * @param int $formId l'id du formulaire à supprimer
	 */
	public function delete($formId) {

		$request = $this->dao->prepare('SELECT id 
										FROM form_has_field
										WHERE form_id = :id');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->execute();
		$fieldIds = $request->fetchAll();

		// on supprime tous les champs du formulaire
		foreach ($fieldIds as $fieldId) {
			$request = $this->dao->prepare('DELETE
											FROM form_has_field_has_language
											WHERE form_has_field_id = :id');
			$request->bindValue(':id', $fieldId[0], \PDO::PARAM_INT);
			$request->execute();
		}
		$request = $this->dao->prepare('DELETE
										FROM form_has_field
										WHERE form_id = :id');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->execute();

		// on supprime le formulaire à proprement parler
		$request = $this->dao->prepare('DELETE
										FROM form_has_language 
										WHERE form_id = :id');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->execute();

		$request = $this->dao->prepare('DELETE
										FROM form
										WHERE id = :id');
		$request->bindValue(':id', $formId, \PDO::PARAM_INT);
		$request->execute();
	}


	/**
	 * Enregistre un formulaire de contact 
	 * @access public
	 * @param ContactForm contactForm le fomulaire de contact à enregistrer
	 * @return void
	 */
	public function saveContactForm($contactForm, $language = LANGUAGE) {
		if (!$contactForm->isNew()) {
			$formId = $contactForm->getId();

			$request = $this->dao->prepare('UPDATE form_has_language 
											SET name = :name, description = :description, receiver = :receiver 
											WHERE form_id = :formId AND language_abbreviation = :language');
			$request->bindValue(':name', $contactForm->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $contactForm->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':formId', $contactForm->getId(), \PDO::PARAM_INT);
			$request->bindValue(':receiver', $contactForm->getReceiver(), \PDO::PARAM_STR);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->execute();

			$toKeepId = array();
			foreach ($contactForm->getElements() as $field) {
				$fieldId = $field->getId();

				// pour les champs déjà présent précédemment on fait la mise à jour
				if(!is_null($fieldId)){
					// on doit faire un tri, si existe alors on update, sinon on delete
					$request = $this->dao->prepare('UPDATE form_has_field 
													SET field_id = :fieldTypeId, required = :required, metric = :metric 
													WHERE id = :fieldId');
					$request->bindValue(':fieldId', $fieldId, \PDO::PARAM_INT);
					$request->bindValue(':metric', $field->getMetric(), \PDO::PARAM_INT);
					$request->bindValue(':required', $field->getRequired(), \PDO::PARAM_BOOL);
					$request->bindValue(':fieldTypeId', $field->getFieldTypeId(), \PDO::PARAM_INT);
					$request->execute();

					$request = $this->dao->prepare('UPDATE form_has_field_has_language 
													SET name = :name, description = :description 
													WHERE form_has_field_id = :fieldId AND language_abbreviation = :language');
					$request->bindValue(':fieldId', $fieldId, \PDO::PARAM_INT);
					$request->bindValue(':language', $language, \PDO::PARAM_STR);
					$request->bindValue(':description', $field->getDescription(), \PDO::PARAM_STR);
					$request->bindValue(':name', $field->getName(), \PDO::PARAM_STR);
					$request->execute();
					$toKeepId[] = $fieldId;
				}
			}

			// on a fait les mises à jours des champs, on doit donc procéder à des suppression
			foreach (array_diff($this->getFieldsIds($formId), $toKeepId) as $idToDelete) {

				$request = $this->dao->prepare('DELETE FROM form_has_field_has_language 
												WHERE form_has_field_id = :id');
				$request->bindValue(':id', $idToDelete, \PDO::PARAM_INT);
				$request->execute();

				$request = $this->dao->prepare('DELETE FROM form_has_field
												WHERE id = :id');
				$request->bindValue(':id', $idToDelete, \PDO::PARAM_INT);
				$request->execute();
			}

		// on ajoute le formulaire
		}else{
			$request = $this->dao->prepare('INSERT INTO form (default_receiver) 
											VALUES (:receiver)');
			$request->bindValue(':receiver', $contactForm->getReceiver(), \PDO::PARAM_STR);
			$request->execute();
			$formId = $this->getLastInsertId();
			$this->lockId();

			$request = $this->dao->prepare('INSERT INTO form_has_language (form_id, language_abbreviation, name, description, receiver) 
											VALUES (:formId, :language, :name, :description, :receiver)');
			$request->bindValue(':formId', $formId, \PDO::PARAM_INT);
			$request->bindValue(':language', $language, \PDO::PARAM_STR);
			$request->bindValue(':name', $contactForm->getName(), \PDO::PARAM_STR);
			$request->bindValue(':description', $contactForm->getDescription(), \PDO::PARAM_STR);
			$request->bindValue(':receiver', $contactForm->getReceiver(), \PDO::PARAM_STR);
			$request->execute();

			// on ajoute le contenu "vide" dans les autres langues
			foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
				if($value != $language){
					$request = $this->dao->prepare('INSERT INTO form_has_language (form_id, language_abbreviation, name, description) 
													VALUES (:formId, :language, :name, :description)');
					$request->bindValue(':formId', $formId, \PDO::PARAM_INT);
					$request->bindValue(':name', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
					$request->bindValue(':description', '', \PDO::PARAM_STR);
					$request->bindValue(':language', $value, \PDO::PARAM_STR);
					$request->execute();
				}
			}

		}

		// on ajoute les champs qui n'existaient pas encore
		foreach ($contactForm->getElements() as $field) {
			if(is_null($field->getId())){
				$request = $this->dao->prepare('INSERT INTO form_has_field (form_id, field_id, required, metric) 
												VALUES (:formId, :fieldId, :required, :metric)');
				$request->bindValue(':formId', $formId, \PDO::PARAM_INT);
				$request->bindValue(':fieldId', $field->getFieldTypeId(), \PDO::PARAM_INT);
				$request->bindValue(':metric', $field->getMetric(), \PDO::PARAM_INT);
				$request->bindValue(':required', $field->getRequired(), \PDO::PARAM_BOOL);
				$request->execute();

				$formHasFieldId = $this->getLastInsertId();
				$request = $this->dao->prepare('INSERT INTO form_has_field_has_language (form_has_field_id, language_abbreviation, name, description) 
												VALUES (:formHasFieldId, :language, :name, :description)');
				$request->bindValue(':formHasFieldId', $formHasFieldId, \PDO::PARAM_INT);
				$request->bindValue(':language', $language, \PDO::PARAM_STR);
				$request->bindValue(':name', $field->getName(), \PDO::PARAM_STR);
				$request->bindValue(':description', $field->getDescription(), \PDO::PARAM_STR);
				$request->execute();

				// on ajoute le contenu "vide" dans les autres langues
				foreach (\Library\LanguagesManager::getLanguages() as $key => $value) {
					if($value != $language){
						$request = $this->dao->prepare('INSERT INTO form_has_field_has_language (form_has_field_id, language_abbreviation, name, description) 
														VALUES (:formHasFieldId, :language, :name, :description)');
						$request->bindValue(':formHasFieldId', $formHasFieldId, \PDO::PARAM_INT);
						$request->bindValue(':name', \Library\LanguagesManager::get('no_content_for_language', $key), \PDO::PARAM_STR);
						$request->bindValue(':description', '', \PDO::PARAM_STR);
						$request->bindValue(':language', $value, \PDO::PARAM_STR);
						$request->execute();
					}
				}
			}
		}
	}

}
?>