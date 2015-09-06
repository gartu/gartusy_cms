<?php
// contenu récupéré de http://www.tinymce.com/forum/viewtopic.php?id=31312
// complete path for images
$directory = opendir(ROOT.DS.'Files'.DS);    

// path left to get images from my webpage perspective :)
$path = DS.'Files'.DS;

$list = array();
while($entry = readdir($directory)) {
    if($entry != '.' && $entry != '..') {
        $split = explode('.', $entry);
        $count = count($split);

        if($split[$count - 1] == 'gif' || $split[$count - 1] == 'jpg' || $split[$count - 1] == 'jpeg' || $split[$count - 1] == 'png' || $split[$count - 1] == 'bmp' ||
           $split[$count - 1] == 'GIF' || $split[$count - 1] == 'JPG' || $split[$count - 1] == 'JPEG' || $split[$count - 1] == 'PNG' || $split[$count - 1] == 'BMP') {
            
            $item = array(
                'title' => implode('.', explode('.', $entry, -1)), 
                'value' => $path.$entry);
            
            $list[] = $item;
        }
    }
}

echo json_encode($list);
?>