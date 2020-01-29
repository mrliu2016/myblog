<?php
header("Content-Type: text/html;charset=utf-8");
if (strpos($_SERVER['REQUEST_URI'], '/server/i18n/site-en.json') !== false) {
    die;
}
error_reporting(E_ALL);
if ($_SERVER['REQUEST_URI'] == '/favicon.ico') {
    die;
}

require(__DIR__ . '/../../common/config/init.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php')
);

//require(__DIR__."/../../common/extensions/xhprof_lib/xhprof_header.php");
(new yii\web\Application($config))->run();
//require(__DIR__."/../../common/extensions/xhprof_lib/xhprof_footer.php");
