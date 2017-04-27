<?php

// Path for editable extensions
Yii::setPathOfAlias('editable', dirname(__FILE__) . '/../extensions/x-editable');            

// This is the main Web application configuration. Any writable
return array(
    'mail' => array(
        'class' => 'ext.mail.YiiMail',
        'transportType' => 'smtp',
    /*    'transportOptions' => array(
            'host' => '52.32.70.121',
            'port_secure' => true,
            'enc_tls' => true,
            'username' => 'ubuntu',
            'password' => 'Node@123',
            'port' => 25,
        ),*/
        'viewPath' => 'application.views.mail',
        'logging' => true,
        'dryRun' => false),
    // bootstap Allies
    'defaultController' => 'daycomment/index',
    'aliases' => array(
        'bootstrap' => realpath(__DIR__ . '/../extensions/yii-bootstrap'), // change this if necessary
        'yiiwheels' => realpath(__DIR__ . '/../extensions/yiiwheels'),
    //'autosave' => realpath(__DIR__ . '/../extensions/autosave'),
    ),
    // Base path of dictionary
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    // Application Name
    'name' => 'TimeCard Admin Panel',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*', 
        'application.components.*',
        'application.components.SmsGateway.*',
        'application.components.Gearman.*',
        'application.components.Gearman.Abstract.*',
        'application.components.Gearman.Clients.*',
        'application.components.Gearman.Workers.*',
        'application.controllers.*', 
        // for bootstap heloer
        //'bootstrap.helpers.TbHtml',
        'bootstrap.widgets.TbActiveForm',
        'ext.bootstrap-theme.widgets.*',
        'ext.bootstrap-theme.helpers.*',
        'ext.bootstrap-theme.behaviors.*',
        'ext.mail.*',
    // Cronjob modules model's,            
    //'application.modules.cronjob.models.*',
    // X-editable extenson,
    //'editable.*',
    // Autosave
    //'autosave.components.*',
    // 'ext.jui.*',
    ),
    'behaviors' => array(
        'onBeginRequest' => array(
            'class' => 'application.components.RequireDuoLogin'
        )
    ),
    // Assign module & its settings on config/modules.php
    // Application Modules
    'modules' => require(dirname(__FILE__) . '/modules.php'),
    // Assign Component & its settings on config/components.php
    // application components
    'components' => require(dirname(__FILE__) . '/components.php'),   
    // Assign all parameters in config/params.php
    // application-level parameters that can be accessed
    'params' => require(dirname(__FILE__) . '/params.php'),
    // If any database exception get redirect to current module    
    //'onException' =>require(dirname(__FILE__).'/exception.php'),
    // Application default theme
    'theme' => 'cisco',
        // Session management in database
//    'urlManager'=>array(
 //   'urlFormat'=>'path',
 //   'showScriptName'=>false,
//     'caseSensitive'=>false,        
//),
);
