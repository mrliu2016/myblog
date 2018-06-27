<?php

namespace app\common\services;

use app\common\components\RedisClient;
use Yii;

class StatisticsService
{
    /**
     * 获取 web socket 连接
     * @return int
     */
    public static function getConnectionNum()
    {
        $redis = RedisClient::getInstance();
        return intval($redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION));
    }

    public static function processLiveRoom($result)
    {
        $redis = RedisClient::getInstance();
        $ip = Yii::$app->params['wsServer'][Constants::CODE_SUCCESS]['ip'];
        foreach ($result as $key => $value) {
            $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $value['roomId'];
            $num = $redis->hGetAll($keyWSRoomFD);
            $result[$key]['num'] = !empty($num) ? $num : Constants::CODE_SUCCESS;
        }
        return $result;
    }
}