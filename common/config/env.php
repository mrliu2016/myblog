<?php

$env = getenv('APPLICATION_ENV');
$env = empty($env) ? 'local' : $env;

defined('YII_ENV')        or define('YII_ENV',        $env);
defined('YII_DEBUG')      or define('YII_DEBUG',      $env == 'local' || $env == 'dev');
defined('YII_ENV_LOCAL')  or define('YII_ENV_LOCAL',  $env == 'local');
defined('YII_ENV_DEV')    or define('YII_ENV_DEV',    $env == 'dev');
defined('YII_ENV_PRE')    or define('YII_ENV_PRE',    $env == 'pre');
defined('YII_ENV_ONLINE') or define('YII_ENV_ONLINE', $env == 'online');
