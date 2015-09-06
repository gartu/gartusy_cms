<?php
$i = 0;

if(count($listeNews) != 0){
	
	$idLast = $listeNews[count($listeNews)-1]->getId();
	foreach ($listeNews as $news) {
		if(!$news->getVisible()){
			continue;
		}
		$tmp = explode('-', $news->getModifiedDate());
		$month = date('F', mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

		echo '<div class="news';
		if($news->getId() != $idLast){
			echo ' middle_news';
		}
		echo '"><div class="date">'.\Library\LanguagesManager::get(strtolower($month)).' '.$tmp[0].'</div><h2>'.$news->getTitle().'</h2>';

		$picture = $news->getPicture();
		if(!is_null($picture)){
			echo '<a href="'.$imgPath.$picture->getUrl().'" data-lightbox="'.$picture->getUrl().'" title="'.$picture->getName().'" name="'.$picture->getName().'" class="img"><img src="'.$thumbPath.$picture->getUrl().'" /></a>';
		}
		echo $news->getContent().'</div>';
	}
}

?>

<hr class="clear"/>
