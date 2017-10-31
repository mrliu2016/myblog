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
    ],
    'timeZone' => 'Asia/Shanghai',
];
