<?php

namespace app\common\services;

use app\common\components\RedisClient;
use app\common\models\User;
use app\common\models\Video;
use Yii;
use yii\base\ErrorException;

class LiveService
{
    //弹幕（关键字过滤、数据不保存）
    public static function barrageRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || empty($param["nickName"]) || empty($param["avatar"]) || empty($param["message"])
        ) {
            return;
        }
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        $nickName = $param["nickName"];
        $avatar = $param["avatar"];
        $message = $param["message"];
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_BARRAGE_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = $message;
        $data = array(
            'roomId' => $roomId,
            'userId' => $userId,
            'nickName' => $nickName,
            'avatar' => $avatar,
        );
        $respondMessage['data'] = $data;
        //广播房间全体成员
        $roomAll = $server->connections;
        foreach ($roomAll as $fd) {
            $server->push($fd, json_encode($respondMessage));
        }
    }

    //服务器信息
    public static function serverInfoRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $param = $message['data'];
        if (empty($param["roomId"])
        ) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_SERVER_INFO_RES;
            $respondMessage['code'] = Constants::CODE_FAILED;
            $respondMessage['message'] = 'parameter error';
            $respondMessage['data'] = array();
            $server->push($frame->fd, json_encode($respondMessage));
            return;
        }
        $roomId = $param["roomId"];
        $index = $roomId % 2;
        $respondMessage = array();
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_SERVER_INFO_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = '';
        $data = array(
            'cdn' => Yii::$app->params['cdn'],
            'roomServer' => Yii::$app->params['wsServer'][$index]
        );
        $respondMessage['data'] = $data;
        $server->push($frame->fd, json_encode($respondMessage));
    }

    //送礼物
    public static function giftRequest($server, $frame, $message)
    {
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || empty($param["userIdTo"]) || empty($param["giftId"]) || empty($param["price"])
            || empty($param["num"]) || empty($param["nickName"]) || empty($param["avatar"]) || empty($param["level"])
        ) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
            $respondMessage['code'] = Constants::CODE_FAILED;
            $respondMessage['message'] = 'parameter error';
            $respondMessage['data'] = array();
            $server->push($frame->fd, json_encode($respondMessage));
            return;
        }
        $redis = RedisClient::getInstance();
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        $userIdTo = $param["userIdTo"];
        $giftId = $param["giftId"];
        $price = $param["price"];
        $num = $param["num"];
        $nickName = $param["nickName"];
        $avatar = $param["avatar"];
        $level = $param["level"];
        $balance = $redis->hget('WSUserBalance', $userId);
        if ($balance === false) {
            $user = User::queryById($userId);
            $balance = $user['balance'];
        }
        $priceReal = $price * $num;
        $balance = $balance - $priceReal;
        if ($balance < 0) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
            $respondMessage['code'] = Constants::CODE_FAILED;
            $respondMessage['message'] = '余额不足';
            $respondMessage['data'] = array();
            $server->push($frame->fd, json_encode($respondMessage));
            return;
        }
        //更新余额
        $redis->hset('WSUserBalance', $userId, $balance);
        //购买礼物队列
        $order = array(
            'giftId' => $giftId,
            'userId' => $userId,
            'userIdTo' => $userIdTo,
            'num' => $num,
            'price' => $price
        );
        $redis->lpush('WSGiftOrder', base64_encode(json_encode($order)));
        //单人广播
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = '';
        $data = array(
            'roomId' => $roomId,
            'userId' => $userId,
            'userIdTo' => $userIdTo,
            'giftId' => $giftId,
            'price' => $price,
            'num' => $num,
            'balance' => $balance,
        );
        $respondMessage['data'] = $data;
        $server->push($frame->fd, json_encode($respondMessage));
        unset($respondMessage);
        //广播房间全体成员
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_NOTIFY_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = '';
        $data = array(
            'roomId' => $roomId,
            'userId' => $userId,
            'nickName' => $nickName,
            'avatar' => $avatar,
            'level' => $level,
            'userIdTo' => $userIdTo,
            'giftId' => $giftId,
            'num' => $num,
        );
        $respondMessage['data'] = $data;
        $roomAll = $server->connections;
        foreach ($roomAll as $fd) {
            $server->push($fd, json_encode($respondMessage));
        }
    }

    //心跳
    public static function heartbeatRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || !isset($param["isMaster"])
        ) {
            return;
        }
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        $isMaster = $param["isMaster"]; //1主播 0粉丝
        if ($isMaster == 1) {
            $video = Video::findLastRecord($userId, $roomId);
            if (empty($video)) {
                //直播开始
                Video::create($userId, $roomId);
            } else {
                //更新直播结束时间
                Video::updateEndTime($video);
            }
            //更新用户直播时间
            User::updateLiveTime($userId);
        }
    }

    /**
     * 进入房间 含机器人
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function joinRoomAndAI($server, $frame, $message)
    {
        $params = $message['data'];
        $user = User::queryById($params["userId"]);
        if (!isset($params["roomId"]) || !isset($params["userId"])) {
            return [
                'messageType' => Constants::MESSAGE_TYPE_JOIN_RES,
                'code' => Constants::CODE_FAILED,
                'message' => Yii::$app->params['civilization'],
                'data' => []
            ];
        }
        //用户进入房间
        LiveService::roomJoin($frame->fd, $params["userId"], $params["roomId"], $params["role"], $params["avatar"], $params["nickName"], $params["level"]);
        $resMessage = [
            'messageType' => Constants::MESSAGE_TYPE_JOIN_RES,
            'code' => Constants::CODE_SUCCESS,
            'message' => Constants::WS_NOTICE,
            'data' => [
                'roomId' => $params['roomId'],
                'avatar' => $params['avatar'],
                'nickName' => $params['nickName'],
                'level' => intval($user['level']),
                'income' => floatval($user['balance'] / Constants::CENT),
                'userList' => LiveService::getUserInfoListByRoomId($params['roomId'])
            ],
        ];
        $server->push($frame->fd, json_encode($resMessage));


        $messageAll = [
            'messageType' => Constants::MESSAGE_TYPE_JOIN_NOTIFY_RES,
            'code' => Constants::CODE_SUCCESS,
            'message' => '',
            'data' => [
                'roomId' => $params['roomId'],
                'userId' => $params['userId'],
                'avatar' => $params['avatar'],
                'nickName' => $params['nickName'],
                'level' => intval($user['level']),
                'count' => LiveService::roomMemberNum($params['roomId'])
            ],
        ];
        $fdList = LiveService::fdListByRoomId($params['roomId']);
        foreach ($fdList as $fd) {
            try {
//                echo $fd . '---' . "/r/n";
                $server->push($fd, json_encode($messageAll));
            } catch (ErrorException $ex) {

            }
        }
    }

    //获取webSocket服务ip
    public static function getWsIp($roomId)
    {
        $index = $roomId % 2;
        $roomServer = Yii::$app->params['wsServer'][$index];
        return $roomServer['ip'];
    }

    //返回房间内用户信息
    public static function getUserInfoListByRoomId($roomId)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomUser = 'WSRoomUser_' . $ip . '_' . $roomId;
        $redis = RedisClient::getInstance();
        $result = $redis->hGetAll($keyWSRoomUser);
        if (empty($result)) return [];
        return array_values($result);
    }

    //加入房间
    public static function roomJoin($fd, $userId, $roomId, $role, $avatar, $nickName, $level)
    {
        //服务器fd映射关系，异常退出用
        $ip = self::getWsIp($roomId);
        $keyWSRoomLocation = 'WSRoomLocation_' . $ip;
        $redis = RedisClient::getInstance();
        $redis->hset($keyWSRoomLocation, $fd, $roomId . '_' . $userId . '_' . $role);

        //房间的fd列表，群发消息用
        $keyWSRoomFD = 'WSRoomFD_' . $ip . '_' . $roomId;
        $keyWSRoomFDTimeout = 48 * 60 * 60;
        $redis->hset($keyWSRoomFD, $fd, $userId);
        $redis->expire($keyWSRoomFD, $keyWSRoomFDTimeout);

        //房间用户头像保存100个
        $keyWSRoomUser = 'WSRoomUser_' . $ip . '_' . $roomId;
        $num = $redis->hLen($keyWSRoomUser);
        if ($num < Constants::NUM_WS_ROOM_USER) {
            $keyWSRoomUserTimeout = 48 * 60 * 60;
            $userInfo['userId'] = $userId;
            $userInfo['nickName'] = $nickName;
            $userInfo['avatar'] = $avatar;
            $userInfo['level'] = $level;
            $redis->hset($keyWSRoomUser, $userId, json_encode($userInfo));
            $redis->expire($keyWSRoomUser, $keyWSRoomUserTimeout);
        }

        $keyWSRoom = 'WSRoom_' . $roomId;
        $redis->incr($keyWSRoom);
    }

    //房间fd列表
    public static function fdListByRoomId($roomId)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomFD = 'WSRoomFD_' . $ip . '_' . $roomId;
        $redis = RedisClient::getInstance();
        $result = $redis->hGetAll($keyWSRoomFD);
        if (empty($result)) return [];
        return array_keys($result);
    }

    //房间成员数量
    public static function roomMemberNum($roomId)
    {
        $keyWSRoom = 'WSRoom_' . $roomId;
        $redis = RedisClient::getInstance();
        $num = $redis->get($keyWSRoom);
        return intval($num);
    }

    public static function leaveRoom($server, $frame, $message)
    {
        $params = $message['data'];
        if (!empty($params)) {
            $messageAll = [
                'messageType' => Constants::MESSAGE_TYPE_LEAVE_RES,
                'code' => Constants::CODE_SUCCESS,
                'message' => '',
                'data' => [
                    'roomId' => $params['roomId'],
                    'isMaster' => $params['isMaster'],
                    'userId' => $params['userId'],
                    'avatar' => $params['avatar'],
                    'nickName' => $params['nickName'],
                    'level' => $params['level'],
                    'count' => LiveService::roomMemberNum($params['roomId'])
                ],
            ];
            //处理用户离开房间数据
            self::leave($frame->fd, $params['roomId']);
            $fdList = LiveService::fdListByRoomId($params['roomId']);
            foreach ($fdList as $fd) {
                try {
                    $server->push($fd, json_encode($messageAll));
                } catch (ErrorException $ex) {

                }
            }
        }
    }

    //处理离开房间
    private static function leave($fdId, $roomId)
    {
        $ip = self::getWsIp($roomId);
        $redis = RedisClient::getInstance();
        $keyWSRoomLocation = 'WSRoomLocation_' . $ip;
        $info = $redis->hget($keyWSRoomLocation, $fdId);
        if (!empty($info)) {
            //删除服务器fd 映射关系
            $redis->hdel($keyWSRoomLocation, $fdId);
            //删除房间用户
            $keyWSRoomFD = 'WSRoomFD_' . $ip . '_' . $roomId;
            $userId = $redis->hget($keyWSRoomFD, $fdId);
            if (!empty($userId)) {
                $redis->hdel($keyWSRoomFD, $fdId);
                //删除房间用户头像
                $keyWSRoomUser = 'WSRoomUser_' . $ip . '_' . $roomId;
                $redis->hdel($keyWSRoomUser, $userId);
            }
        }
        //房间人数-1
        $keyWSRoom = 'WSRoom_' . $roomId;
        $redis->decr($keyWSRoom);
    }
}