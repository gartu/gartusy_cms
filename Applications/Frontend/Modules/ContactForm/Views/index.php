<?php
$form->setSubmit(\Library\LanguagesManager::get('send'));

echo '<div class="contact_form_content">';

if(isset($hasBeenSend)){
	echo '<br/>&nbsp;&nbsp;&nbsp;'.\Library\LanguagesManager::get('contact_sent').'<br/><br/>';
}elseif(isset($wrongCaptcha)){
	echo '<br/>&nbsp;&nbsp;&nbsp;'.\Library\LanguagesManager::get('wrong_captcha').'<br/><br/>';
}
echo '<div class="form"><h1>'.$contactForm->getName().'</h1>'.$contactForm->getDescription().$form->generateHeader().$form->generate().'<br/>';
// gestion du captcha
echo '&nbsp;<img id="captcha" src="/Securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
	  <input type="text" name="captcha_code" size="10" maxlength="6" />'.$form->generateSubmit();

echo $form->generateFooter().'</div></div>';


if(!empty($newsPart)){
	echo $newsPart;
}

?>