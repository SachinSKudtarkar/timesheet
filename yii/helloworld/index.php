<?php


// include Yii bootstrap 
file
require_once(dirname(__FILE__).'/../../framework/yii.php');



// create a Web application instance and run

ini_set('display_errors',1);
error_reporting(E_ALL);


Yii::createWebApplication()->run();