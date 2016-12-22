<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
    	'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=rent',
            'username' => 'root',
            'password' => 'jsedefhiohOP*@Y&Er3p;o2n;',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'aliases' => [
        'yii/getid3' => '@frontend/runtime/tmp-extensions/yii2-rbac/src',
    ],
    'timeZone' => 'Asia/Shanghai',
];
