<?php

namespace app\common\services;

use app\common\components\IPUtils;
use app\common\components\RedisClient;
use app\common\models\Gift;
use app\common\models\KeyWord;
use app\common\models\Order;
use app\common\models\User;
use app\common\models\Video;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;

class LiveService
{
    //弹幕（关键字过滤、数据不保存）
    public static function barrageRequest($server, $frame, $message)
    {
        try {
            $startTime = microtime(true);
            $param = $message['data'];

            $redis = RedisClient::getInstance();
            static::runtimeConsumeTime($startTime, microtime(true), '【RedisClient::getInstance()】运行时长：');

            $tmpStartTime = microtime(true);
            $keyWords = [];
            if ($redis->exists(Constants::WS_WS_KEYWORD)) {
                $keyWords = json_decode(base64_decode($redis->get(Constants::WS_WS_KEYWORD)), true);
            }
            $keyWords = array_combine($keyWords, array_fill(0, count($keyWords), '*'));
            static::runtimeConsumeTime($tmpStartTime, microtime(true), '【keyWords】运行时长：');

            $respondMessage = [
                'messageType' => Constants::MESSAGE_TYPE_BARRAGE_RES,
                'code' => Constants::CODE_SUCCESS,
                'message' => strtr($param["message"], $keyWords),
                'data' => [
                    'roomId' => $param["roomId"],
                    'userId' => $param["userId"],
                    'nickName' => $param["nickName"],
                    'avatar' => $param["avatar"],
                    'fly' => isset($param["fly"]) ? (int)$param["fly"] : 1 // fly 1 弹幕 0 普通的左下角
                ]
            ];
            //广播房间全体成员
            $tmpStartTime = microtime(true);
            $roomAll = LiveService::fdListByRoomId($server, $param["roomId"]);
            static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::fdListByRoomId】运行时长：');

            $tmpStartTime = microtime(true);
            static::broadcast($server, $roomAll, $respondMessage, $param["roomId"]);
            static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::broadcast】运行时长：');

            static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::barrageRequest】运行时长：');
        } catch (\Exception $exception) {
            if (YII_DEBUG) {
                static::webSocketLog(
                    $exception->getMessage(),
                    __FUNCTION__ . '.log',
                    true
                );
            }
        }
    }

    //送礼物
    public static function giftRequest($server, $frame, $message)
    {
        $startTime = microtime(true);
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || empty($param["userIdTo"]) || empty($param["giftId"]) || empty($param["price"])
            || empty($param["num"]) || empty($param["nickName"]) || !isset($param["level"])
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
        $giftName = $param["giftName"];
        $giftImg = $param["giftImg"];
        $price = $param["price"];
        $num = $param["num"];
        $nickName = $param["nickName"];
        $avatar = $param["avatar"];
        $level = $param["level"];
        $balance = $redis->hget(Constants::WS_USER_BALANCE, $userId);
//        if ($balance == false) {
//            $user = User::queryById($userId);
//            if (!empty($user)) {
//                $balance = $user['balance'];
//            } else {
//                //@todo 随机用户默认余额100元-测试
//                $balance = 100000;
//            }
//        }
        $priceReal = $price * $num; // 去掉分，webSocket不涉及业务，交易类结算已最小单位透传
//        $priceReal = $price * $num * Constants::CENT;
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
        $redis->hset(Constants::WS_USER_BALANCE, $userId, $balance);
        //购买礼物队列
        $order = array(
            'giftId' => $giftId,
            'userId' => $userId,
            'userIdTo' => $userIdTo,
            'num' => $num,
            'price' => $price
        );
        $redis->lpush(Constants::QUEUE_WS_GIFT_ORDER, base64_encode(json_encode($order)));
        $redis->expire(Constants::QUEUE_WS_GIFT_ORDER, Constants::DEFAULT_EXPIRES);
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
            'balance' => !empty($balance) ? $balance : 0, // 去掉分，webSocket不涉及业务，交易类结算已最小单位透传
//            'balance' => !empty($balance) ? $balance / Constants::CENT : 0,
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
            'giftName' => $giftName,
            'giftImg' => $giftImg,
            'price' => $price,
            'num' => $num,
        );
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::giftRequest】运行时长：');

        $respondMessage['data'] = $data;
        $tmpStartTime = microtime(true);
        $roomAll = LiveService::fdListByRoomId($server, $roomId);
        static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::fdListByRoomId】运行时长：');

        $tmpStartTime = microtime(true);
        static::broadcast($server, $roomAll, $respondMessage, $roomId);
        static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::fdListByRoomId】运行时长：');

        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::giftRequest】运行时长：');
    }

    //心跳
    public static function heartbeatRequest($server, $frame, $message)
    {
        $startTime = microtime(true);

        $param = $message['data'];
        $redis = RedisClient::getInstance();
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        if ($param["isMaster"] == 1) { //1主播 0粉丝
            $redis->lpush(Constants::QUEUE_WS_HEARTBEAT,
                base64_encode(json_encode(['userId' => $userId, 'roomId' => $roomId])));
            $redis->expire(Constants::QUEUE_WS_HEARTBEAT, Constants::DEFAULT_EXPIRES);
            $server->push($frame->fd, json_encode(['userId' => $userId, 'roomId' => $roomId]));
        }
        $Warning = $redis->hget(Constants::WSWARNING, $userId);
        if ($Warning !== false) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_HEARTBEAT_RES;
            $respondMessage['code'] = Constants::CODE_WARNING;
            $respondMessage['message'] = $Warning;
            $respondMessage['data'] = array(
                'roomId' => $roomId,
                'userId' => $userId,
                'isMaster' => $param["isMaster"]
            );
            $server->push($frame->fd, json_encode($respondMessage));
            $redis->hdel(Constants::WSWARNING, $userId);
        }
        $close = $redis->hget(Constants::WSCLOSE, $userId);
        if ($close !== false) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_HEARTBEAT_RES;
            $respondMessage['code'] = Constants::CODE_CLOSE;
            $respondMessage['message'] = $close;
            $respondMessage['data'] = array(
                'roomId' => $roomId,
                'userId' => $userId,
                'isMaster' => $param["isMaster"]
            );
            $server->push($frame->fd, json_encode($respondMessage));
            $redis->hdel(Constants::WSCLOSE, $userId);
        }
        static::latestHeartbeat($frame->fd, $userId, $roomId, $param["isMaster"]);
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::heartbeatRequest】运行时长：');

//        ll('房间号：' . $roomId . '，在线人数：' . LiveService::roomMemberNum($roomId), 'webSocketMessage.log');
//        ll("连接数：" . $redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION), 'webSocketMessage.log');
    }

    /**
     * 记录最新一次心跳时间
     *
     * @param $fd
     * @param $userId
     * @param $roomId
     * @param $isMaster
     */
    public static function latestHeartbeat($fd, $userId, $roomId, $isMaster)
    {
        $redis = RedisClient::getInstance();
        $key = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
        $redis->hset($key, $userId, $fd . '_' . $isMaster . '_' . time());
        $redis->expire($key, Constants::WS_DEFAULT_EXPIRE);
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
        $startTime = microtime(true);
        $params = $message['data'];
        //用户进入房间
        static::join($frame->fd, $params["userId"], $params["roomId"], $params["role"],
            $params["avatar"], $params["nickName"], $params["level"], $params['balance']);
        static::runtimeConsumeTime($startTime, microtime(true), '【static::join】运行时长：');

        $tmpStartTime = microtime(true);
        $roomMemberNum = LiveService::roomMemberNum($params['roomId']);
        static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::roomMemberNum】运行时长：');

        $tmpStartTime = microtime(true);
        $userList = array_values(LiveService::getUserInfoListByRoomId($params['roomId']));
        static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::getUserInfoListByRoomId】运行时长：');

        $resMessage = [
            'messageType' => Constants::MESSAGE_TYPE_JOIN_RES,
            'code' => Constants::CODE_SUCCESS,
            'message' => Constants::WS_NOTICE,
            'data' => [
                'roomId' => $params['roomId'],
                'userId' => $params["masterUserId"],
                'avatar' => $params["masterAvatar"],
                'nickName' => $params["masterNickName"],
                'level' => intval($params["masterLevel"]),
                'income' => intval($params['balance']),
                'count' => $roomMemberNum,
                'userList' => $userList
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
                'level' => intval($params['level']),
                'count' => $roomMemberNum,
                'userList' => $userList
            ],
        ];

        $tmpStartTime = microtime(true);
        $fdList = LiveService::fdListByRoomId($server, $params['roomId']);
        static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::fdListByRoomId】运行时长：');

        $tmpStartTime = microtime(true);
        static::broadcast($server, $fdList, $messageAll, $params['roomId']);
        static::runtimeConsumeTime($tmpStartTime, microtime(true), '【LiveService::broadcast】运行时长：');

        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::' . __FUNCTION__ . '】运行时长：');
    }

    //获取webSocket服务ip
    public static function getWsIp($roomId)
    {
        $roomId = ctype_digit($roomId) ? $roomId : ord($roomId);
        $index = $roomId % count(Yii::$app->params['wsServer']);
        $roomServer = Yii::$app->params['wsServer'][$index];
        return $roomServer['ip'];
    }

//    /**
//     * 设置房间、WS服务器
//     *
//     * @param $roomServer
//     * @param $frame
//     * @param $params
//     */
//    public static function setWSRoomLocationServer($roomServer, $frame, $params)
//    {
//        RedisClient::getInstance()->hset(
//            Constants::WS_ROOM_LOCATION . $roomServer,
//            $frame->fd,
//            $params['roomId'] . '_' . $params['userId'] . '_' . $params['isMaster']
//        );
//    }

//    /**
//     * 设置房间号、WSIP、FD、用户信息
//     * @param $roomServer
//     * @param $frame
//     * @param $params
//     */
//    public static function setWSRoomFD($roomServer, $frame, $params)
//    {
//        RedisClient::getInstance()->hset(
//            Constants::WS_ROOM_FD . $roomServer . $params['roomId'],
//            $frame->fd,
//            $params['userId']
//        );
//    }

//    /**
//     * 设置房间、WSIP、用户
//     * @param $roomServer
//     * @param $frame
//     * @param $params
//     * @param $user
//     */
//    public static function setWSRoomUser($roomServer, $frame, $params, $user)
//    {
//        $data = [
//            'userId' => intval($user['id']),
//            'nickName' => $user['nickName'],
//            'avatar' => $user['avatar'],
//            'level' => $user['level']
//        ];
//        RedisClient::getInstance()->hset(
//            Constants::WS_ROOM_USER . $roomServer . $params['roomId'],
//            $params['userId'],
//            json_encode($data)
//        );
//    }

//    /**
//     * @param $roomServer
//     * @param $frame
//     * @param $params
//     */
//    public static function setWSRoom($roomServer, $frame, $params)
//    {
//        RedisClient::getInstance()->incr(Constants::WS_ROOM_USER_COUNT . $params['roomId']);
//    }

//    /**
//     * 获取房间用户列表
//     *
//     * @param $roomServer
//     * @param $roomId
//     * @return array
//     */
//    public static function getRoomUserList($roomId)
//    {
//        $result = [];
//        $userList = RedisClient::getInstance()->hVals(Constants::WS_ROOM_USER . self::getWsIp($roomId) . $roomId);
//        if (!empty($userList)) {
//            foreach ($userList as $key => $value) {
//                $result[$key] = json_decode($value, true);
//            }
//        }
//        return $result;
//    }

    //返回房间内用户信息
    public static function getUserInfoListByRoomId($roomId)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $roomId;
        $redis = RedisClient::getInstance();
        $result = $redis->hGetAll($keyWSRoomUser);
        if (empty($result)) return [];
        foreach ($result as $key => $value) {
            $result[$key] = json_decode($value, true);
        }
        return $result;
    }

    //加入房间
    private static function join($fd, $userId, $roomId, $role, $avatar, $nickName, $level, $balance)
    {
        //服务器fd映射关系，异常退出用
        $ip = self::getWsIp($roomId);
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $ip;
        $redis = RedisClient::getInstance();
        $redis->hset($keyWSRoomLocation, $fd, $roomId . '_' . $userId . '_' . $role);
        $redis->expire($keyWSRoomLocation, Constants::DEFAULT_EXPIRES);

        //房间的fd列表，群发消息用
        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $roomId;
        $keyWSRoomFDTimeout = 48 * 60 * 60;
        $redis->hset($keyWSRoomFD, $fd, $userId);
        $redis->expire($keyWSRoomFD, $keyWSRoomFDTimeout);

        //房间用户头像保存100个
        $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $roomId;
        $num = $redis->hLen($keyWSRoomUser);
        if ($num < Constants::NUM_WS_ROOM_USER) {
            $keyWSRoomUserTimeout = 48 * 60 * 60;
            $userInfo['userId'] = $userId;
            $userInfo['nickName'] = $nickName;
            $userInfo['avatar'] = $avatar;
            $userInfo['level'] = $level;
            $userInfo['fd'] = $fd;
            $userInfo['role'] = $role;
            $redis->hset($keyWSRoomUser, $userId, json_encode($userInfo));
            $redis->expire($keyWSRoomUser, $keyWSRoomUserTimeout);
        }

        // 用户余额
        $redis->hset(Constants::WS_USER_BALANCE, $userId, $balance);
        $redis->expire(Constants::WS_USER_BALANCE, Constants::DEFAULT_EXPIRES);
    }

    //房间fd列表
    public static function fdListByRoomId($server, $roomId)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $roomId;
        $redis = RedisClient::getInstance();
        $result = $redis->hGetAll($keyWSRoomFD);
        if (empty($result)) return [];
//        foreach ($result as $fd => $userId) {
//            if (!$server->exist($fd)) {
//                //fd连接不存在或尚未完成握手，返回false
//                self::fdClose(null, $fd);
//                unset($result[$fd]);
//            }
//        }
        return array_keys($result);
    }

    //房间成员数量
    public static function roomMemberNum($roomId)
    {
        $wsIp = self::getWsIp($roomId);
        $keyWSRoomFD = Constants::WS_ROOM_FD . $wsIp . '_' . $roomId;
        $num = RedisClient::getInstance()->hLen($keyWSRoomFD);
        return intval($num);
    }

    public static function leaveRoom($server, $frame, $message, $fd = 0, $isExceptionExit = false)
    {
        $startTime = microtime(true);

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
                    'level' => $params['level']
                ],
            ];
            $fdList = LiveService::fdListByRoomId($server, $params['roomId']);
            static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::fdListByRoomId】运行时长：');
            //处理用户离开房间数据
            $tmp = microtime(true);
            if ($params['isMaster']) {
                $count = LiveService::roomMemberNum($params['roomId']);
            } else {
                $count = LiveService::roomMemberNum($params['roomId']) - 1;
            }
            static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::roomMemberNum】运行时长：');

            $tmp = microtime(true);
            self::leave($isExceptionExit ? $fd : $frame->fd, $params['roomId']);
            static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::leave】运行时长：');

            $tmp = microtime(true);
            self::clearLMList($params);
            static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::clearLMList】运行时长：');

            $tmp = microtime(true);
            $messageAll['data']['userList'] = array_values(LiveService::getUserInfoListByRoomId($params['roomId']));
            static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::getUserInfoListByRoomId】运行时长：');

            $tmp = microtime(true);
            $messageAll['data']['count'] = $count;
            static::broadcast($server, $fdList, $messageAll, $params['roomId']);
            static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::broadcast】运行时长：');

            static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::leaveRoom】运行时长：');
        }
    }

    //处理离开房间
    private static function leave($fdId, $roomId)
    {
        $ip = self::getWsIp($roomId);
        $redis = RedisClient::getInstance();
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $ip;
        $info = $redis->hget($keyWSRoomLocation, $fdId);
        if (!empty($info)) {
            //删除服务器fd 映射关系
            $redis->hdel($keyWSRoomLocation, $fdId);
            //删除房间用户
            $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $roomId;
            $userId = $redis->hget($keyWSRoomFD, $fdId);
            if (!empty($userId)) {
                $redis->hdel($keyWSRoomFD, $fdId);
                //删除房间用户头像
                $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $roomId;
                $redis->hdel($keyWSRoomUser, $userId);
            }
            // 清除心跳
            $keyLatestHeartbeat = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
            $redis->hdel($keyLatestHeartbeat, $userId);
        }
        static::updateConnection();
    }

    //获取用户余额
    public static function getWSUserBalance($userId)
    {
        $redis = RedisClient::getInstance();
        $balance = $redis->hget('WSUserBalance', $userId);
        if ($balance === false) {
            $user = User::queryById($userId);
            $balance = $user['balance'];
            $redis->hset('WSUserBalance', $userId, $balance);
            $redis->expire('WSUserBalance', 3600 * 24);
        }
        return $balance;
    }

    //禁言
    public static function gag($server, $frame, $message)
    {
        $startTime = microtime(true);
        $params = $message['data'];
        if (!empty($params)) {
            if (self::isManager($params['roomId'], $frame->fd)) {
                static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::isManager】运行时长：');

                $messageAll = [
                    'messageType' => Constants::MESSAGE_TYPE_GAG_RES,
                    'userId' => $params['userId'],
                    'avatar' => $params['avatar'],
                    'nickName' => $params['nickName'],
                    'level' => $params['level'],
                    'expiry' => $params['expiry'],
                ];
                $server->push($frame->fd, json_encode($messageAll));
            } else {
                $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GAG_RES;
                $respondMessage['code'] = Constants::CODE_FAILED;
                $respondMessage['message'] = 'permission deny';
                $respondMessage['data'] = [];
                $server->push($frame->fd, json_encode($respondMessage));
            }
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::gag】运行时长：');
    }

    /**
     * 踢人
     *
     * @param $server
     * @param $frame
     * @param $message
     *
     */
    public static function kickUser($server, $frame, $message)
    {
        $startTime = microtime(true);
        $params = $message['data'];
        $ip = self::getWsIp($params['roomId']);
        $redis = RedisClient::getInstance();
        // 判断adminUserId是否有权限踢人
//        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $params['roomId'];
//        $adminUserId = $redis->hget($keyWSRoomFD, $frame->fd);
        if (self::isManager($params['roomId'], $frame->fd)) {
            static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::isManager】运行时长：');

            $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $params['roomId'];
            $user = $redis->hget($keyWSRoomUser, $params['userId']);
            if (!empty($user)) {
//                $user = json_decode($user, true);
                $messageAll = [
                    'messageType' => Constants::MESSAGE_TYPE_KICK_RES,
                    'code' => Constants::CODE_SUCCESS,
                    'message' => '',
                    'data' => [
                        'roomId' => $params['roomId'],
                        'userId' => $params['userId'],
                        'expiry' => $params['expiry'],
                    ],
                ];

                $tmp = microtime(true);
                $fdList = LiveService::fdListByRoomId($server, $params['roomId']);
                static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::fdListByRoomId】运行时长：');

                $tmp = microtime(true);
                static::broadcast($server, $fdList, $messageAll, $params['roomId']);
                static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::broadcast】运行时长：');
//                foreach ($fdList as $fd) {
//                    try {
//                        if (!$server->exist($fd)) {
//                            //fd连接不存在或尚未完成握手，返回false
//                            self::fdClose(null, $fd);
//                        } else {
//                            $server->push($fd, json_encode($messageAll));
//                        }
////                        $server->push($fd, json_encode($messageAll));
//                    } catch (ErrorException $ex) {
//                        var_dump($ex);
//                    }
//                }
            } else {
                $respondMessage['messageType'] = Constants::MESSAGE_TYPE_KICK_RES;
                $respondMessage['code'] = Constants::CODE_FAILED;
                $respondMessage['message'] = 'permission deny';
                $respondMessage['data'] = [];
                $server->push($frame->fd, json_encode($respondMessage));
            }
            $tmp = microtime(true);
            self::clearLMList($params);
            static::runtimeConsumeTime($tmp, microtime(true), '【LiveService::clearLMList】运行时长：');
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::kickUser】运行时长：');
    }

    /*
     * 是否有房间管理员权限
     * roomId_userId_role
     * */
    private static function isManager($roomId, $fd)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $ip;
        $redis = RedisClient::getInstance();
        $userInfo = $redis->hget($keyWSRoomLocation, $fd);
        if (empty($userInfo)) return false;
        $userInfo = explode('_', $userInfo);
        //role 0观众1主播
        if (empty($userInfo[2])) return false;
        return true;
    }

    //服务器信息
    public static function serverInfo($param)
    {
        if (empty($param["roomId"])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $roomId = ctype_digit($param["roomId"]) ? $param["roomId"] : ord($param["roomId"]);
        $index = $roomId % count(Yii::$app->params['wsServer']);
        $wsServer = Yii::$app->params['wsServer'][$index];
        $data = array(
            'roomServer' => [
                'host' => $wsServer["domain"],
                'port' => Constants::WEB_SOCKET_PORT,
            ],
            'roomServer-wss' => [
                'host' => $wsServer["domain"],
                'port' => Constants::WEB_SOCKET_PORT_SSL,
            ]
        );
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }

    /**
     * 连麦请求
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function requestLM($server, $frame, $message)
    {
        $startTime = microtime(true);
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $redis = RedisClient::getInstance();
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($redis->hget($keyWSRoomUser, $messageInfo['adminUserId']), true);
        if (!empty($userInfo) && $userInfo['role']) {
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_LM_RES,
                'data' => [
                    'userId' => $messageInfo['userId'],
                    'nickName' => $messageInfo['nickName']
                ]
            ];
            $server->push(intval($userInfo['fd']), json_encode($responseMessage));
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::requestLM】运行时长：');
    }

    /**
     * 推送连麦用户列表
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function requestLMList($server, $frame, $message)
    {
        $startTime = microtime(true);
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $redis = RedisClient::getInstance();
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($redis->hget($keyWSRoomUser, $messageInfo['adminUserId']), true);
        if (!empty($userInfo) && $userInfo['role']) {
            $lmUser = [
                'userId' => $messageInfo['userId'],
                'nickName' => $messageInfo['nickName'],
                'avatar' => $messageInfo['avatar'],
                'introduction' => $messageInfo['introduction'],
                'type' => 1 // 申请连麦
            ];
            $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . '_' . $messageInfo['roomId'];
            $redis->hset($keyWSRoomUserLMList, $messageInfo['userId'], json_encode($lmUser));
            $redis->expire($keyWSRoomUserLMList, Constants::DEFAULT_EXPIRES);
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_LM_LIST_RES,
                'data' => [
                    'userList' => array_values(LiveService::getUserLMListByRoomId($messageInfo['roomId']))
                ]
            ];

            static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::getUserLMListByRoomId】运行时长：');

            try {
                $server->push(intval($userInfo['fd']), json_encode($responseMessage));
            } catch (ErrorException $ex) {
                ll($ex->getMessage(), __FUNCTION__ . '.log');
            }
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::requestLMList】运行时长：');
    }

    /**
     * 获取连麦用户列表
     *
     * @param $roomId
     * @return array
     */
    public static function getUserLMListByRoomId($roomId)
    {
        $wsIp = self::getWsIp($roomId);
        $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . '_' . $roomId;
        $redis = RedisClient::getInstance();
        $result = $redis->hGetAll($keyWSRoomUserLMList);
        if (empty($result)) return [];
        foreach ($result as $key => $value) {
            $result[$key] = json_decode($value, true);
        }
        return $result;
    }

    /**
     * 连麦响应
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function responseLM($server, $frame, $message)
    {
        $startTime = microtime(true);
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $redis = RedisClient::getInstance();
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($redis->hget($keyWSRoomUser, $messageInfo['userId']), true);
        if (!empty($userInfo)) {
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_LM_RES,
                'data' => [
                    'userId' => $messageInfo['userId'],
                    'type' => $messageInfo['type'] // 0：拒绝，1：同意
                ]
            ];
            $server->push(intval($userInfo['fd']), json_encode($responseMessage));
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::responseLM】运行时长：');
    }

    public static function responseLMList($server, $frame, $message)
    {
        $startTime = microtime(true);
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $redis = RedisClient::getInstance();
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($redis->hget($keyWSRoomUser, $messageInfo['userId']), true);
        if (!empty($userInfo)) {
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_LM_AGREE_RES,
                'data' => [
                    'userId' => $messageInfo['userId'],
                    'type' => $messageInfo['type'] // 0：拒绝，1：同意
                ]
            ];
            if ($messageInfo['type'] == 1) {
                $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . '_' . $messageInfo['roomId'];
                $lmUserInfo = json_decode($redis->hget($keyWSRoomUserLMList, $messageInfo['userId']), true);
                $lmUserInfo['type'] = 2; // 2：同意连麦
                $redis->hset($keyWSRoomUserLMList, $messageInfo['userId'], json_encode($lmUserInfo));
            }
            $server->push(intval($userInfo['fd']), json_encode($responseMessage));
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::responseLMList】运行时长：');
    }

    /**
     * 清空连麦用户列表
     *
     * @param $params
     */
    public static function clearLMList($params)
    {
        $wsIp = self::getWsIp($params['roomId']);
        $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . '_' . $params['roomId'];
        $redis = RedisClient::getInstance();
        if (isset($params['isMaster'])) {
            $redis->expire($keyWSRoomUserLMList, 0);
        } else {
            $redis->hdel($keyWSRoomUserLMList, $params['userId']);
        }
    }

    /**
     * socket 异常断开
     * @param $server
     * @param $frame
     */
    public static function fdClose($server, $fd)
    {
        $startTime = microtime(true);
        $responseMessage = [
            'messageType' => "close",
        ];
        $local_ip = IPUtils::get_local_ip();
        static::runtimeConsumeTime($startTime, microtime(true), '【IPUtils::get_local_ip】运行时长：');

        $redis = RedisClient::getInstance();
        //服务器fd映射关系，异常退出用
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $local_ip;
        $info = $redis->hget($keyWSRoomLocation, $fd);
        if (!empty($info)) {
            $info = explode("_", $info);
            $roomId = $info[0];
            $userId = $info[1];
            $roleId = $info[2];
            // 房间用户信息
            $keyWSRoomUser = Constants::WS_ROOM_USER . $local_ip . '_' . $roomId;
            $redis->hdel($keyWSRoomUser, $userId);
            //房间的fd列表
            $keyWSRoomFD = Constants::WS_ROOM_FD . $local_ip . '_' . $roomId;
            $redis->hdel($keyWSRoomFD, $fd);
            //删除fd数据
            $redis->hdel($keyWSRoomLocation, $fd);

            // 心跳
            $tmp = microtime(true);

            $keyLatestHeartbeat = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
            $latestHeartbeat = $redis->hget($keyLatestHeartbeat, $userId);
            if (!empty($latestHeartbeat)) {
                $latestHeartbeat = explode('_', $latestHeartbeat);
                $keyWSRoomUser = Constants::WS_ROOM_USER . $roomId;
                $userInfo = json_decode($redis->hget($keyWSRoomUser, $userId), true);
                switch ($latestHeartbeat[1]) {
                    case 0: // 观众
                        self::leave($fd, $roomId);
                        static::runtimeConsumeTime($tmp, microtime(true), '【static::leave】运行时长：');
                        break;
                    case 1: // 主播
                        if ((time() - $latestHeartbeat[2]) <= Constants::WS_HEARTBEAT_IDLE_TIME) {
                            self::leave($fd, $roomId);
                            static::runtimeConsumeTime($tmp, microtime(true), '【static::leave】运行时长：');
                        }
                        break;
                }
                if ((time() - $latestHeartbeat[2]) > Constants::WS_HEARTBEAT_IDLE_TIME) {
                    $message['data'] = [
                        'roomId' => $roomId,
                        'isMaster' => $roleId,
                        'userId' => $userId,
                        'avatar' => $userInfo['avatar'],
                        'nickName' => $userInfo['nickName'],
                        'level' => $userInfo['level']
                    ];
                    $tmp = microtime(true);
                    static::leaveRoom($server, null, $message, $fd, true);
                    $redis->hdel($keyLatestHeartbeat, $userId);
                    static::runtimeConsumeTime($tmp, microtime(true), '【static::leaveRoom】运行时长：');
                }
            } else {
                self::leave($fd, $roomId);
                static::runtimeConsumeTime($tmp, microtime(true), '【static::leave_2】运行时长：');
            }

            $responseMessage['data'] = [
                'data' => [
                    'roomId' => $roomId,
                    'userId' => $userId,
                    'roleId' => $roleId
                ]
            ];
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【static::fdClose】运行时长：');
        ll(var_export(array_merge($responseMessage, array("fd" => $fd)), true), 'webSocketMessage.log');
    }

    /**
     * 断开连麦
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function closeCall($server, $frame, $message)
    {
        $startTime = microtime(true);
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $redis = RedisClient::getInstance();
        // 主播
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($redis->hget($keyWSRoomUser, $messageInfo['adminUserId']), true);
        if (!empty($userInfo)) {
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_CLOSE_CALL_RES,
                'data' => [
                    'userId' => $messageInfo['userId'],
                    'type' => 3 // 3：断开连麦
                ]
            ];
            $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . '_' . $messageInfo['roomId'];
            $redis->hdel($keyWSRoomUserLMList, $messageInfo['userId']);
            $server->push($frame->fd, json_encode($responseMessage));
        }
        static::runtimeConsumeTime($startTime, microtime(true), '【LiveService::closeCall】运行时长：');
    }

    /**
     * 广播消息，并清除不存在的fd
     *
     * @param $server
     * @param $fdList
     * @param $respondMessage
     * @param $roomId
     * @return bool
     */
    private static function broadcast($server, $fdList, $respondMessage, $roomId)
    {
        if (empty($fdList)) return false;
        foreach ($fdList as $fd) {
            try {
                if (!$server->exist($fd)) {
                    //fd连接不存在或尚未完成握手，返回false
//                    self::fdClose(null, $fd);
                    self::leave($fd, $roomId);
                } else {
                    $server->push($fd, json_encode($respondMessage));
                }
                ll(var_export(array_merge($respondMessage, array("fd" => $fd)), true), 'webSocketMessage.log');
            } catch (\Exception $ex) {
                ll(var_export(array_merge(['codeMessage' => $ex->getMessage()], array("fd" => $fd)), true), 'webSocketMessage.log');
            }
        }
        return true;
    }

    /**
     * 统计 webSocket 打开连接数
     * @param $fd
     */
    public static function openConnection($fd)
    {
        $redis = RedisClient::getInstance();
        if (!$redis->exists(Constants::WS_CONNECTION)) {
            $redis->hset(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 1);
        } else {
            $redis->hIncrby(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 1);
        }
        $redis->expire(Constants::WS_CONNECTION, Constants::WS_DEFAULT_EXPIRE);
        static::webSocketLog(
            "{$fd} connection open，连接数：" . $redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION),
            'webSocketMessage.log',
            true
        );
    }

    /**
     * 更新 webSocket 打开连接数
     */
    public static function updateConnection()
    {
        $redis = RedisClient::getInstance();
        $number = $redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION);
        if ($number > 0) {
            $redis->hIncrby(Constants::WS_CONNECTION, Constants::WS_CONNECTION, -1);
        } elseif ($number == 0) {
            $redis->hset(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 0);
        } else {
            $redis->hdel(Constants::WS_CONNECTION, Constants::WS_CONNECTION);
        }

    }

    /**
     * 秒数转换为时间
     *
     * @param $times
     * @return string
     */
    private static function _secToTime($times)
    {
        $result = '';
        if ($times > 0) {
            $hour = sprintf('%02s', floor($times / 3600));
            $minute = sprintf('%02s', floor(($times - 3600 * $hour) / 60));;
            $second = sprintf('%02s', floor((($times - 3600 * $hour) - 60 * $minute) % 60));
            if (!empty($hour) && ($hour != '00')) {
                $result .= $hour . ':';
            }
            if (!empty($minute)) {
                $result .= $minute . ':';
            }
            if (!empty($second)) {
                $result .= $second;
            }
        }
        return !empty($result) ? $result : '00:00';
    }

    //对查询出的举报信息排序
    public static function reportSort($list)
    {

        $len = count($list);
        //该层循环控制 需要冒泡的轮数
        for ($i = 1; $i < $len; $i++) { //该层循环用来控制每轮 冒出一个数 需要比较的次数
            for ($k = 0; $k < $len - $i; $k++) {
                if ($list[$k]['id'] > $list[$k + 1]['id']) {
                    $tmp = $list[$k + 1];
                    $list[$k + 1] = $list[$k];
                    $list[$k] = $tmp;
                }
            }
        }
        return $list;
    }

    /**
     * 记录 webSocket 日志
     *
     * @param $message
     * @param $fileName
     * @param bool $isRecord
     */
    public static function webSocketLog($message, $fileName, $isRecord = false)
    {
        if ($isRecord) {
            ll($message, $fileName);
        }
    }

    /**
     * 记录函数运行时长
     *
     * @param $startTime
     * @param $endTime
     * @param $message
     */
    private static function runtimeConsumeTime($startTime, $endTime, $message)
    {
        $duration = $endTime - $startTime;
        ll($message . $duration, 'runtime_consume_time.log');
    }

    /**
     * 分配服务器节点
     *
     * $nodes = array('192.168.5.201', '192.168.5.102', '192.168.5.111', '192.168.5.112', '192.168.5.113')
     *
     * @param $userId
     * @param $nodes
     * @return mixed
     */
    private static function distributeServerNode($userId, $nodes)
    {
        $buckets = []; // 节点的hash字典
        /**
         * 生成节点字典 —— 使节点分布在单位区间[0,1)的圆上
         */
        foreach ($nodes as $key) {
            // 每个节点的复制的个数
            for ($index = 1; $index <= Constants::WS_NODE_REPLICAS; $index++) {
                $crc = crc32($key . '.' . $index) / pow(2, 32); // CRC値
                $buckets[] = array('index' => $crc, 'node' => $key);
            }
        }
        sort($buckets); // 根据索引进行排序

        /**
         * 对每个 userId 进行hash计算，找到其在圆上的位置，然后在该位置开始依顺时针方向找到第一个服务节点
         */
        $flag = false;
        $crc = crc32($userId) / pow(2, 32); // 计算 userId 的hash值
        for ($index = 0; $index < count($buckets); $index++) {
            if ($buckets[$index]['index'] > $crc) {
                /*
                 * 因为已经对buckets进行了排序
                 * 所以第一个index大于key的hash值的节点即是要找的节点
                 */
                return $buckets[$index]['node'];
                break;

            }
        }
        // 未找到，则使用 buckets 中的第一个服务节点
        if (!$flag) {
            return $buckets[0]['node'];
        }
    }
}