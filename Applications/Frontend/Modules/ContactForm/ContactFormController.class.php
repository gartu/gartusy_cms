<?php

namespace Applications\Frontend\Modules\ContactForm;

class ContactFormController extends \Library\BackController {


	/**
	 * @access public
	 * @param HTTPRequest $request 
	 * @return void
	 */
	public function executeIndex(\Library\HTTPRequest $request) {

		$manager = $this->managers->getManagerOf('ContactForm');

		$contactForm = $manager->getById($request->getGetData('id'));


		if ($request->method() === 'POST'){

			// on doit mettre les données reçu de post dans le $contactForm
			$form = $contactForm->build();

			foreach ($form->getElements() as $element) {
				if (!is_null($_POST[$element->getName()])){
					$element->setValue($_POST[$element->getName()]);
				}
			}

			$securimage = new \Securimage\Securimage();

			// si le formulaire est valide alors on l'envoie
			if ($form->isValid()){
				if($securimage->check($_POST['captcha_code']) != false){
					$this->sendMail($contactForm->getReceiver(), $contactForm->getName(), $_POST);
					$this->page->addVar('hasBeenSend', true);
				}else{
					$this->page->addvar('wrongCaptcha', true);
				}
			}
		}else{
			$form = $contactForm->build();
		}

		// on a créé la partie persistante 'news' placée avec les parties de texte
		$newsController = $this->app->getController('/'.\Library\LanguagesManager::getLanguage().'/liste-news-rapide.html');

		$newsController->execute();
		$this->page->addVar('newsPart', $newsController->getPage()->getGeneratedContentPage());
		
		$this->page->addVar('form', $form);
		$this->page->addVar('contactForm', $contactForm);
	}


	/**
	 * permet d'envoyer un e-mail à une adresse donnée
	 * @access protected
	 * @param String $mail l'email de la personne à contacter
	 * @param String $subject le sujet de l'email
	 * @param array $data les éléments à transmettre à la personne 
	 * @return bool si le mail a bien été envoyé
	 */
	protected function sendMail($mail, $subject, $data){
		// j'ai écrit ça un jour et ça marchait.. je suis surement un petit peu supersticieux.. :
		// placer une exception pour les serveur microsoft ? à faire si nécessaire ...
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){
			$nl = "\r\n";
		}else{
			$nl = "\r\n";
		}
		
		// aussi repris, aussi supersticieux.. c'est vraiment le bordel, pourquoi alternance de ' et " ?
		$message = '';
		foreach ($data as $key => $value) {
			$message .= $key.' : '.$nl.$value.$nl.$nl;
		}

		$header  = 'From: "'.$this->app->getConfig()->getParam('contactForm_fromText').'"'.$this->app->getConfig()->getParam('contactForm_fromMail')."\n";
		$header .= 'Reply-To: '.$this->app->getConfig()->getParam('contactForm_reply')."\n"; 
		$header .= 'Content-Type: text/html; charset="iso-8859-1"'."\n"; 
	  	$header .= 'Content-Transfer-Encoding: 8bit'; 
		
		// et merde.. encore une supersticion à ne pas toucher .. -_- c'est vraiment dég
		$ok = mail($mail, $subject, $message, $header);
		return $ok;
	}

}
?>
