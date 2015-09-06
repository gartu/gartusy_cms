<?php	
	echo '<h2>'.\Library\LanguagesManager::get('title_manage_news').'</h2>';
	if(count($newsList) == 0){
		echo \Library\LanguagesManager::get('empty_content');
	}else{
		foreach ($newsList as $news) {
			//$content = substr($news->getContent(), 0, 160);
			echo '<a href="news-'.$news->getId().'.html"><div style="float:left;margin-left:10px;padding:10px;width:200px"><h3>'.$news->getTitle().'</h3>'.\Library\Utils::texte_resume_html($news->getContent(), 80).'</div></a>';
		}
	}

?>