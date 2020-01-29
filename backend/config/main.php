<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'app-staff',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\backend\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'CLiUN8xg9RSjHM3cVf2KXmt789642k3MAwaZu6dD47vhPGYBFJ',
        ],
        'errorHandler' => [
            'errorAction' => 'index/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'index',
];
