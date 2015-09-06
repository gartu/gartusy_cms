<?php

$i = 0;
	


if($news->getVisible()){
	$tmp = explode('-', $news->getModifiedDate());
	$month = date('F', mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

	echo '<div class="news_content"><div class="news"><div class="date">'.\Library\LanguagesManager::get(strtolower($month)).' '.$tmp[0].'</div><h1>'.$news->getTitle().'</h1>';

	$picture = $news->getPicture();
	if(!is_null($picture)){
		echo '<a href="'.$imgPath.$picture->getUrl().'" data-lightbox="'.$picture->getUrl().'" title="'.$picture->getName().'" class="img"><img src="'.$thumbPath.$picture->getUrl().'" alt="'.$picture->getName().'" /></a>';
	}

	echo $news->getContent().'</div></div>';
	if(!empty($newsPart)){
		echo $newsPart;
	}
}


?>
