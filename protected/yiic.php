<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
@set_time_limit(36000);
@ini_set('memory_limit','2048M');
// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../yii/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);
