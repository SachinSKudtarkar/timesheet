<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$_consoleConfig = array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'RJILAuto Console Application',
    // preloading 'log' component
    //'preload'=>array('log'),
    // application components
    'import' => array(
        'application.extensions.ipvalidator.IPValidator',
        'application.commands.*',
        'application.models.*',
        'application.models.NddGenerators.*',
        'application.components.*',
        'application.components.SmsGateway.*',
        'application.components.Gearman.*',
        'application.components.Gearman.Abstract.*',
        'application.components.Gearman.Clients.*',
        'application.components.Gearman.Workers.*',
        'application.controllers.*',
        'application.components.PlanVsBuiltVerification.*',
        'application.components.NDD.*',
        'application.components.NDD.CSS.*',
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
);

#Configure request component
$_requestConfig = array(
    'request' => array(
        'hostInfo' => 'http://10.137.32.109/release',
        'baseUrl' => '',
        'scriptUrl' => '',
    )
);

$_consoleConfig['components'] = array_merge($_consoleConfig['components'], $_requestConfig);

return $_consoleConfig;