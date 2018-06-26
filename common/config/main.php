<?php

//$db = array();
////判断是否是纯净版
//if($_SERVER['HTTP_HOST']=='3tlive.3ttech.cn'){
//    $db = require(__DIR__ . '/db.php');
//}
//else{
//    $db = require(__DIR__ . '/dbPure.php');
//}
$db = require(__DIR__ . '/db.php');

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => $db,
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@runtime/logs/error.log',
                ]
            ],
        ],
        'oss' => [
            'class' => 'app\common\components\OSS',
        ],
    ],
    'timeZone' => 'Asia/Shanghai',
];
