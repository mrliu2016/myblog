<?php

//$params = array();
////判断是否是纯净版
//if($_SERVER['HTTP_HOST']=='3tlive.3ttech.cn'){
//    $params = array_merge(
//        require(__DIR__ . '/../../common/config/params.php'),
//        require(__DIR__ . '/params.php')
//    );
//}
//else{
//    $params = array_merge(
//        require(__DIR__ . '/../../common/config/paramsPure.php'),
//        require(__DIR__ . '/params.php')
//    );
//}

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'app-staff',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\manager\controllers',
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
        'session'=>[//设置session
            'timeout'=> 604800,//失效时间
//            'autoStart'=> false,
//            'sessionname'=> '3ttech',
//            'cookieMode'=>'only',
//            'savePath'=>'/path/to/new/directory',
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'index/login',//默认控制器
];
