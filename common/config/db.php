<?php

$dev = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=10.66.179.137;dbname=tianxiang_2_0_live',
    'username' => 'root',
    'password' => 'Pkq!%#2018%$',
    'emulatePrepare' => true,
    'charset' => 'utf8',
];

$online = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=10.66.179.137;dbname=tianxiang_2_0',
    'username' => 'root',
    'password' => 'Pkq!%#2018%$',
    'emulatePrepare' => false,
    'charset' => 'utf8',
    'slaveConfig' => [
        'username' => 'root',
        'password' => 'Pkq!%#2018%$',
    ],
    'slaves' => [
        [
            'dsn' => 'mysql:host=10.66.179.137;dbname=tianxiang_2_0',
            'charset' => 'utf8',
        ],
    ],
];


return YII_ENV_PRE || YII_ENV_ONLINE ? $online : $dev;
