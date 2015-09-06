<?php

require 'constFile.php';
require ROOT.DS.'Library'.DS.'autoload.php';

$app = new Applications\Frontend\Frontend;
$app->run();

?>