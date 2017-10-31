<?php

$dev = [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'mysql:host=rm-2zez85v6e1ctm49aao.mysql.rds.aliyuncs.com;dbname=disseminate',
    'username' => 'phpuser',
    'password' => 'MyNewPass4!',
    'charset'  => 'utf8',
];

$online = [
    'class'       => 'yii\db\Connection',
    'dsn'         => 'mysql:host=rm-2zeq4rg223it7lofzo.mysql.rds.aliyuncs.com;port=3306;dbname=disseminate',
    'username'    => 'phpuser',
    'password'    => 'MyNewPass4!',
    'emulatePrepare' => false,
    'charset'     => 'utf8',
    'slaveConfig' => [
        'username' => 'phpuser',
        'password' => 'MyNewPass4!',
    ],
    'slaves'      => [
        [
            'dsn' => 'mysql:host=rm-2zeq4rg223it7lofzo.mysql.rds.aliyuncs.com;port=3306;dbname=disseminate',
            'charset' => 'utf8',
        ],
    ],
];

return YII_ENV_PRE || YII_ENV_ONLINE ? $online : $dev;
