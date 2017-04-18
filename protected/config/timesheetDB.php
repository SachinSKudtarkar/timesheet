<?php



return array(
/*    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=infinitydb.ca6bs9zz3jop.us-east-2.rds.amazonaws.com;dbname=db_rjilautots_dev',
            'username'         => 'rjiltsdev',
            'password'         => 'DEvTe@m@2017',
            'class'            => 'CDbConnection'          // DO NOT FORGET THIS!
        ),
    ),*/
    //unix_socket=/var/lib/mysql/mysql.sock;

    'connectionString' => 'mysql:host=192.168.10.17;dbname=db_rjilautots_dev;',
        'emulatePrepare' => true,
		'pdoClass' => 'NestedPDO',
        'username' => 'cisco',
        'password' => 'cisco2014$$',
        'charset' => 'utf8',
        'enableParamLogging' => true,
        'attributes'=>array(
            PDO::MYSQL_ATTR_LOCAL_INFILE => true
        ),
);