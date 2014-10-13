<?php 
$engine = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/engine';
include_once $engine . '/start.php';
require dirname(dirname(dirname(__FILE__))) . "/classes/IzapVideo.php";
error_reporting(E_ALL | E_STRICT);
