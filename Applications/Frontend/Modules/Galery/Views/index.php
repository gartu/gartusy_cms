<?php

echo '<div class="galery"><h1>'.$galery->getTitle().'</h1>'.$galery->getDescription();

$pictures = $galery->getPictures();
foreach ($pictures as $picture) {
	$pictureName = $picture->getId().'.'.$picture->getFormat();
	echo '<div class="fixed_thumbnail"><div class="round_img"><a href="'.$imgPath.$pictureName.'" data-lightbox="Galery-'.str_replace(' ', '-', $galery->getTitle()).'" title="'.$picture->getName().'&#13;'.$picture->getDescription().'">
		  <img src="'.$thumbPath.$pictureName.'" alt="'.$picture->getName().'" /></a></div></div>';
}

echo '</div>';
?>