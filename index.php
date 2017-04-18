<?php
ini_set('display_errors',0); 
error_reporting(0);
@set_time_limit(36000);
@ini_set('memory_limit','2048M');
//if(in_array($_SERVER['REMOTE_ADDR'], array('10.137.4.22'))) {

//} 
//die('Site under maintenance!!!!');
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
ini_set ( 'soap.wsdl_cache_enable' , 0 );
ini_set ( 'soap.wsdl_cache_ttl' , 0 );

// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config = dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

// Run application
$manage_url_path = dirname(__FILE__).'/protected/components/ManageUrl.php';

require_once($manage_url_path);

//object of ManageUrl
$create_web_application = new ManageUrl($config);

// Run web application by extended class CWebApplication
$create_web_application->run();


/*$app = Yii::createWebApplication($config);
Yii::setPathOfAlias('webroot',dirname($_SERVER['SCRIPT_FILENAME']));
$app->run();*/

/*Yii::createWebApplication($config)->run();
echo "coming";
exit;*/
?>
