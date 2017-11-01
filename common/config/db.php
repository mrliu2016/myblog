<?php

$dev = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=drds7e7z8arsc9y0public.drds.aliyuncs.com;dbname=live_dev',
    'username' => 'live_dev',
    'password' => 'MyNewPass4',
    'emulatePrepare' => true,
    'charset' => 'utf8',
];

$online = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=drds7e7z8arsc9y0public.drds.aliyuncs.com;dbname=live',
    'username' => 'live',
    'password' => 'MyNewPass4',
    'emulatePrepare' => false,
    'charset' => 'utf8',
    'slaveConfig' => [
        'username' => 'live',
        'password' => 'MyNewPass4',
    ],
    'slaves' => [
        [
            'dsn' => 'mysql:host=drds7e7z8arsc9y0public.drds.aliyuncs.com;dbname=live',
            'charset' => 'utf8',
        ],
    ],
];

return YII_ENV_PRE || YII_ENV_ONLINE ? $online : $dev;
