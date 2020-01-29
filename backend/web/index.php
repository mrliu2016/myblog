<?php
ini_set('date.timezone','Asia/Shanghai');
header("Content-Type: text/html;charset=utf-8");
if (strpos($_SERVER['REQUEST_URI'], '/server/i18n/site-en.json') !== false) {
    die;
}

if ($_SERVER['REQUEST_URI'] == '/favicon.ico') {
    die;
}

require(__DIR__ . '/../../common/config/init.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php')
);

(new yii\web\Application($config))->run();
