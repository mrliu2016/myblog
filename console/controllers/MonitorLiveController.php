<?php

namespace app\console\controllers;

use app\common\components\RedisClient;
use app\common\components\RongCloud;
use app\common\models\Gift;
use app\common\models\Order;
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
                $video = Video::queryById($item['streamId'], true);
                if (!empty($video)) {
                    //更新观众人数
                    $wsIp = LiveService::getWsIp($item['roomId']);
                    $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $item['roomId'];
                    $viewerNum = $redis->hLen($keyWSRoomUser);
                    $viewerNum = ($viewerNum <= Constants::NUM_WS_ROOM_USER) ? $viewerNum : LiveService::roomMemberNum(null, $item['roomId']);
                    if ($viewerNum > $video->viewerNum) {
                        $video->viewerNum = $viewerNum;
                    }
                    //更新直播结束时间
                    Video::updateEndTime($video);
                }
            }

            while ($item = $redis->rpop('dev:LiveHeartbeat:android')) {
                $item = json_decode(base64_decode($item), true);
                $video = Video::queryById($item['streamId'], true);
                if (!empty($video)) {
                    //更新观众人数
//                    $wsIp = LiveService::getWsIp($item['roomId']);
//                    $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $item['roomId'];
//                    $viewerNum = $redis->hLen($keyWSRoomUser);
//                    $viewerNum = ($viewerNum <= Constants::NUM_WS_ROOM_USER) ? $viewerNum : LiveService::roomMemberNum(null, $item['roomId']);
//                    if ($viewerNum > $video->viewerNum) {
//                        $video->viewerNum = $viewerNum;
//                    }
                    //更新直播结束时间
                    Video::updateEndTime($video);
                }
            }
        } catch (\Exception $ex) {
            ll($ex->getMessage(), __FUNCTION__ . '.log');
        }
    }

    /**
     * 监控礼物队列
     *
     *
     * 'giftId' => $giftId,
     * 'userId' => $userId,
     * 'userIdTo' => $userIdTo,
     * 'num' => $num,
     * 'price' => $price
     * 'roomId' => $roomId
     *
     */
    public function actionMonitorGift()
    {
        try {
            $redis = RedisClient::getInstance();
            while ($order = $redis->rpop(Constants::QUEUE_WS_GIFT_ORDER)) {
                $itemList = json_decode(base64_decode($order), true);
                // 支出
                $userModel = User::queryById($itemList['userId'], true);
                $priceReal = $itemList['price'] * $itemList['num'];
                if (!empty($userModel)) {
                    $userModel->balance -= $priceReal;
                    $userModel->expenditure += $priceReal;
                    $userModel->save();
                }
                Order::create($itemList['roomId'], $itemList['giftId'], $itemList['userId'],
                    $itemList['userIdTo'], $itemList['price'], $itemList['num']);
                // 收入
                $toUserModel = User::queryById($itemList['userIdTo'], true);
                if (!empty($toUserModel)) {
                    $toUserModel->income += $priceReal;
                    $toUserModel->save();
                }
            }
        } catch (\Exception $exception) {
            ll($exception->getMessage(), 'send_gift_' . date('Y-m-d') . '.log');
        }
    }
}