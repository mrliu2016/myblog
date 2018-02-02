<?php


global $_xhprof;

if ($_xhprof['enable']) {
    $xhprof_data = xhprof_disable();
    $timeofaction = microtime_float() - $timeofaction;
    //本地文件记录
    //userBehaviorLog($timeofaction);
    // save raw data for this profiler run using default
    // implementation of iXHProfRuns.
    try {
        $xhprof_runs = new XHProfRuns_Default();
        // save the run under a namespace "xhprof_foo"
        $xhprof_data['rt'] = $timeofaction;
        $xhprof_data['uid'] = empty(Yii::$app->controller->user) ? '0' : Yii::$app->controller->user->userId;
        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
    } catch (Exception $ecption) {
        file_put_contents(Yii::$app->getRuntimePath() . "/logs/app.log", $ecption->getMessage() . "\n", FILE_APPEND);
    }
}
//本地用户行为日志
function userBehaviorLog($timeofaction)
{
    global $_xhprof;
    $_userid = empty(Yii::$app->controller->user) ? '0' : Yii::$app->controller->user->userId;
    $_url = Yii::$app->request->url;
    $_url = ltrim($_url, "/");
    $_par = parse_url($_url);
    $params = explode("/", $_par["path"]);
    $controllerName = empty($params[0]) ? '' : $params[0];
    $actionName = empty($params[1]) ? '' : $params[1];
    if (isset($_par['query'])) {
        $data = $_userid . "," . $controllerName . "," . $actionName . "," . $timeofaction . "," . $_par["query"] . "\n";
    } else {
        $data = $_userid . "," . $controllerName . "," . $actionName . "," . $timeofaction . "\n";
    }
    $filename = $_xhprof['dot_tempdir'] . "/user_behavior_" . date("Y-m-d") . ".txt";
    file_put_contents($filename, $data, FILE_APPEND);
}

?>
