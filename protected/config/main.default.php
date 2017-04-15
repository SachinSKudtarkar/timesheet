<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Nodal Design Document',
    'defaultController' => 'nddTempInput/create',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.controllers.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'cisco',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'commUtility' => array('class' => 'CommonUtility'),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=cisco_ndd',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info, trace',
                ),
                // uncomment the following to show log messages on web pages            
                array(
                    'class' => 'CWebLogRoute',
                ),
//                array(
//                    'class' => 'CDbLogRoute',
//                    'levels' => 'error, warning',
//                    'logTableName' => 'tbl_system_logs',
//                    'autoCreateLogTable' => true,
//                    'enabled' => true
//                ),
            ),
        ),
        'yexcel' => array(
            'class' => 'ext.yexcel.Yexcel'
        ),
        // PDF Extension
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                    'defaultParams' => array(// More info: http://mpdf1.com/manual/index.php?tid=184
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
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'dashboard_options' => array(
          '1'=>'Request Id',
          '2'=>'SAP Id',
          '3'=>'Circle',  
        ),
    ),
);
