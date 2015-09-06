

<?php

$i = 0;

echo '<div class="news_part"><div id="deco_top"></div><a href="/en/liste-news.html"><h1>'.\Library\LanguagesManager::get('newsNview').'</h1></a>';

if(count($listeNews) != 0){
	
	$idLast = $listeNews[count($listeNews)-1]->getId();
	foreach ($listeNews as $news) {
		if(!$news->getVisible()){
			continue;
		}
		$tmp = explode('-', $news->getModifiedDate());
		$month = date('F', mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

		echo '<div class="news"><a href="/'.\Library\LanguagesManager::getLanguage().'/contenu-news-'.$news->getId().'.html"><h2>'.$news->getTitle().'</h2></a>';

		$picture = $news->getPicture();
		if(!is_null($picture)){
			echo '<a href="'.$imgPath.$picture->getUrl().'" data-lightbox="'.$picture->getUrl().'" title="'.$picture->getName().'" class="img"><img src="'.$thumbPath.$picture->getUrl().'" alt="'.$picture->getName().'" /></a>';
		}

		echo '<a href="/'.\Library\LanguagesManager::getLanguage().'/contenu-news-'.$news->getId().'.html">'.\Library\Utils::texte_resume_html($news->getContent(), 120).'</a></div>';

	}
}

?>
<div id="deco_bottom"></div></div>
<hr class="clear"/>
