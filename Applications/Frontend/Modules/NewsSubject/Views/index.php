<?php
	// l'entete permettant de choisie la catégorie de news à afficher
	echo '<div class="category_menu">';
	foreach ($categories as $category) {
		echo '<a href="/'.\Library\LanguagesManager::getLanguage().'/liste-news-category-'.$category->getId().'.html"><div class="category';
		if($category->getId() == $selectedCategory){
			echo '_selected';
		}
		echo '">'.$category->getName().'</div></a>';
	}
	echo '</div>';

	if(count($newsList) != 0){
		echo '<div class="news_category_content">';
		
		$idLast = $newsList[count($newsList)-1]->getId();
		foreach ($newsList as $news) {
			$tmp = explode('-', $news->getModifiedDate());
			$month = date('F', mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

			echo '<div class="news';
			if($news->getId() != $idLast){
				echo ' middle_news';
			}

			echo '"><div class="date">'.\Library\LanguagesManager::get(strtolower($month)).' '.$tmp[0].'</div><h2>'.$news->getTitle().'</h2>';

			$picture = $news->getPicture();
			if(!is_null($picture)){
				echo '<a href="'.$imgPath.$picture->getUrl().'" data-lightbox="'.$picture->getUrl().'" title="'.$picture->getName().'" class="img"><img src="'.$thumbPath.$picture->getUrl().'" alt="'.$picture->getName().'" /></a>';
			}
			echo $news->getContent().'<hr class="clear"/></div>';
		}

		echo '<div class="last_news_left"></div><div class="last_news_right"></div></div><div class="pages">';
		for($i = 0;$i < $numberPage; $i++){
			echo '<a href="/'.\Library\LanguagesManager::getLanguage().'/liste-news-category-'.$selectedCategory.'-'.$i.'.html"';
			if($i == $page){
				echo ' class="current"';
			}
			echo '>'.($i + 1).'</a>';
		}
		echo '</div>';
	}

	
?>