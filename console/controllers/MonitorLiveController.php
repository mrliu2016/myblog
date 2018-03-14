<?php

namespace app\console\controllers;

use app\common\components\RedisClient;
use app\common\models\User;
use app\common\models\Video;
use app\common\services\Constants;
use app\common\services\LiveService;
use yii\base\ErrorException;
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

    /**
     * 消费-心跳队列
     */
    public function actionHeartbeat()
    {
        $redis = RedisClient::getInstance();
        try {
            while ($item = $redis->rpop(Constants::QUEUE_WS_HEARTBEAT)) {
                $item = json_decode(base64_decode($item), true);
                $video = Video::findLastRecord($item['userId'], $item['roomId']);
                if (empty($video)) {
                    //直播开始
//                Video::create($userId, $roomId);
                } else {
                    //更新观众人数
                    $wsIp = LiveService::getWsIp($item['roomId']);
                    $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $item['roomId'];
                    $viewerNum = $redis->hLen($keyWSRoomUser);
                    if ($viewerNum > $video->viewerNum) {
                        $video->viewerNum = $viewerNum;
                    }
                    //更新直播结束时间
                    Video::updateEndTime($video);
                }
                //更新用户直播时间
                User::updateLiveTime($item['userId']);
            }
        } catch (\Exception $ex) {
            ll($ex->getMessage(), __FUNCTION__ . '.log');
        }
    }
}