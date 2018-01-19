<?php

namespace app\console\controllers;

use app\common\models\Video;
use yii\console\Controller;

class MonitorLiveController extends Controller
{
    /**
     * 监控直播无心跳，更改直播状态
     */
    public function actionMonitorLive()
    {
        Video::monitorLive();
    }
}