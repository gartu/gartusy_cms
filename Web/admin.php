<?php

require 'constFile.php';
require ROOT.DS.'Library'.DS.'autoload.php';

$app = new Applications\Backend\Backend;
$app->run();

?>