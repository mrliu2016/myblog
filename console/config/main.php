<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'timeZone' => 'Asia/Shanghai',
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\console\controllers',
    'params' => $params,
];
