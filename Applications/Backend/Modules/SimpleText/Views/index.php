<?php
	
	foreach ($textList as $text) {
		$content = substr($text->getContent(), 0, 160);
		echo '<a href="manage-text-'.$text->getId().'.html"><div style="float:left;margin-left:10px;padding:10px;width:200px">'.$content.'</div></a>';
	}

?>