<?php 
$engine = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/engine';
include_once $engine . '/start.php';
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
date_default_timezone_set('Asia/Kolkata');
require dirname(dirname(dirname(__FILE__))) . "/classes/IzapVideo.php";
require dirname(dirname(dirname(__FILE__))) . "/classes/izapConvert.php";
