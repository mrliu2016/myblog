<?php

$XHPROF_ROOT = __DIR__ . '/../';
include_once $XHPROF_ROOT . "/xhprof_lib/config.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

if ($_xhprof['enable']) {
//    if (mt_rand(1, 20) == 1) {
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        $xhprof_on = true;
//    }
    $timeofaction = microtime_float();
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
?>
