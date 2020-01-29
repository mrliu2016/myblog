<?php

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
        'session' => [
            'class' => 'yii\web\DbSession', //session 操作对象
            'db' => 'db',   //指定数据库操作组件是上面的组件db
            'sessionTable' => 'yii_session' //session 数据库表名称
        ]
    ],
    'timeZone' => 'Asia/Shanghai',
];
