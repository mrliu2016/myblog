<?php

$params = array();
ll(var_export($_SERVER['HTTP_HOST'],true),'requestHost.log');
//判断是否是纯净版
if($_SERVER['HTTP_HOST'] == 'api.3tlive.3ttech.cn'){
    $params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/params.php')
    );
}
else{
    $params = array_merge(
        require(__DIR__ . '/../../common/config/paramsPure.php'),
        require(__DIR__ . '/params.php')
    );
}

return [
    'id' => 'app-staff',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\api\controllers',
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
