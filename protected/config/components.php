<?php

return array(
    // Yii user components
    'user' => array(
        // enable cookie-based authentication
        'allowAutoLogin' => true,
        'loginUrl' => array('login')
    ),
    'request' => array(
        'enableCsrfValidation' => false,
        'enableCookieValidation' => true,
        'class' => 'application.components.EHttpRequest',
        'noCsrfValidationRoutes' => array('service/wreader', 'PortalSurvey/SubmitSurvey', 'granitewebservice/wreader',),
        'csrfCookie' => array(
            'httpOnly' => true,
            'secure' => true,
        ),
    ),
    // bootstap Component
    'bootstrap' => array(
        'class' => 'bootstrap.components.Bootstrap',
    ),
    'yiiwheels' => array(
        'class' => 'yiiwheels.YiiWheels',
    ),
    // Url Settings 
    'urlManager' => array(
        'urlFormat' => 'path',
        'caseSensitive' => false,
        'showScriptName' => false,
        'rules' => array(
            // Basic rules
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ),
    ),
    'zip' => array(
        'class' => 'ext.zip.EZip',
    ),
    'commUtility' => array('class' => 'CommonUtility'),
    /*  'cache'=>array( 
      'class'=>'system.caching.CDbCache',
      'connectionID'=>'db',
      'autoCreateCacheTable'=>true,
      'cacheTableName'=>'yiicache',
      ), */
    // Database connectivity with mysql
    'db' => require(dirname(__FILE__) . '/database.php'),
    'dbinfi' => require(dirname(__FILE__) . '/timesheetDB.php'),
    // Session management
//    'session' => array(
//        'class' => 'CDbHttpSession',
//        'connectionID' => 'db',
//        'sessionTableName' => 'tbl_session',
//        // 1 days timeout session
//        'timeout' => (86400 * 1),
//        'cookieParams' => array(
//            'httponly' => true,
//            'secure' => true,
//        ),
//    ),
    'session' => array(
        'class' => 'CHttpSession',
        'savePath' => dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'runtime',
        'autoStart' => true,
        'timeout' => 86400,
        ),
    // YII error handler
    'errorHandler' => array(
        // use 'site/error' action to display errors
        'errorAction' => 'site/error',
    ),
    // Application Log Managemer settings
    'log' => array(
        'class' => 'CLogRouter',
        'routes' => array(
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning, info',
                //'categories' => array(),// all categories
                'logFile' => 'system.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning',
                'categories' => 'system.db.CDbCommand',
                'logFile' => 'db.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'info',
                'categories' => 'ndd_generation',
                'logFile' => 'ndd.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'info',
                'categories' => 'reint_rollback',
                'logFile' => 'reinteRollBackHost.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning, info',
                'categories' => 'port_migration',
                'logFile' => 'port_migration.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning, info',
                'categories' => 'ip_region_change',
                'logFile' => 'ip_region_change.log',
            ),
        // uncomment the following to show log messages on web pages
//          array(
//                'class' => 'CWebLogRoute',
//            ),
        /*  array(
          'class'=>'CLogEmailToSystem',
          'levels'=>'error',
          'emails'=>'anandrathi@benchmarkitsolutions.com',
          ), */
        ),
    ),
    'ePdf' => array(
        'class' => 'ext.yii-pdf.EYiiPdf',
        'params' => array(
            'mpdf' => array(
                'librarySourcePath' => 'application.vendors.mpdf.*',
                'constants' => array(
                    '_MPDF_TEMP_PATH' => dirname(__DIR__) . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR,
                ),
                'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                'defaultParams' => array(// More info: http://mpdf1.com/manual/index.php?tid=184
                    'ignore_invalid_utf8' => true,
//                    'mode' => '', //  This parameter specifies the mode of the new document.
                    'format' => 'A4', // format A4, A5, ...
//                    'default_font_size' => 0, // Sets the default document font size in points (pt)
//                    'default_font' => '', // Sets the default font-family for the new document.
//                    'mgl' => 15, // margin_left. Sets the page margins for the new document.
//                    'mgr' => 15, // margin_right
//                    'mgt' => 16, // margin_top
//                    'mgb' => 16, // margin_bottom
//                    'mgh' => 9, // margin_header
//                    'mgf' => 9, // margin_footer
//                    'orientation' => 'P', // landscape or portrait orientation
                )
            ),
        ),
    ),
);
?>
