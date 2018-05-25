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
        echo 'receive message:' . json_encode($message);
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || empty($param["nickName"]) || empty($param["message"])
        ) {
            return;
        }
        $redis = RedisClient::getInstance();
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        $nickName = $param["nickName"];
        $avatar = $param["avatar"];
        $message = $param["message"];
        $keyWords = $redis->get("WSKeyWord");
        if ($keyWords === false) {
            $keyWords = KeyWord::queryAllKeyWords();
            $redis->set("WSKeyWord", base64_encode(json_encode($keyWords)));
            $redis->expire("WSKeyWord", 3600 * 24);
        } else {
            $keyWords = json_decode(base64_decode($keyWords), true);
        }
        $keyWords = array_combine($keyWords, array_fill(0, count($keyWords), '*'));
        $message = strtr($message, $keyWords);
        //fly 1 弹幕 0 普通的左下角
        $fly = isset($param["fly"]) ? (int)$param["fly"] : 1;
        $respondMessage = [
            'messageType' => Constants::MESSAGE_TYPE_BARRAGE_RES,
            'code' => Constants::CODE_SUCCESS,
            'message' => $message,
            'data' => [
                'roomId' => $roomId,
                'userId' => $userId,
                'nickName' => $nickName,
                'avatar' => $avatar,
                'fly' => $fly,
            ]
        ];
        //广播房间全体成员
        $roomAll = LiveService::fdListByRoomId($server, $roomId);
        static::broadcast($server, $roomAll, $respondMessage, $roomId);
    }

    //送礼物
    public static function giftRequest($server, $frame, $message)
    {
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
        ll($balance, __FUNCTION__ . '.log');
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
        $respondMessage['data'] = $data;
        $roomAll = LiveService::fdListByRoomId($server, $roomId);
        static::broadcast($server, $roomAll, $respondMessage, $roomId);
//        foreach ($roomAll as $fd) {
//            try {
//                if (!$server->exist($fd)) {
//                    //fd连接不存在或尚未完成握手，返回false
//                    self::fdClose(null, $fd);
//                } else {
//                    $server->push($fd, json_encode($respondMessage));
//                }
////                $server->push($fd, json_encode($respondMessage));
//            } catch (ErrorException $ex) {
//
//            }
//        }
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
            ll(var_export(array_merge($respondMessage, array("fd" => $frame->fd)), true), 'webSocketMessage.log');
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
            ll(var_export(array_merge($respondMessage, array("fd" => $frame->fd)), true), 'webSocketMessage.log');
            $redis->hdel(Constants::WSCLOSE, $userId);
        }
        static::latestHeartbeat($frame->fd, $userId, $roomId, $param["isMaster"]);
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
        $params = $message['data'];
        //用户进入房间
        self::join($frame->fd, $params["userId"], $params["roomId"], $params["role"],
            $params["avatar"], $params["nickName"], $params["level"], $params['balance']);
        $roomMemberNum = LiveService::roomMemberNum($params['roomId']);
        $userList = array_values(LiveService::getUserInfoListByRoomId($params['roomId']));
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
        $fdList = LiveService::fdListByRoomId($server, $params['roomId']);
        static::broadcast($server, $fdList, $messageAll, $params['roomId']);
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
            //处理用户离开房间数据
            if ($params['isMaster']) {
                $count = LiveService::roomMemberNum($params['roomId']);
            } else {
                $count = LiveService::roomMemberNum($params['roomId']) - 1;
            }
            self::leave($isExceptionExit ? $fd : $frame->fd, $params['roomId']);
//            static::fdClose($server, $isExceptionExit ? $fd : $frame->fd);
            self::clearLMList($params);
            $messageAll['data']['userList'] = array_values(LiveService::getUserInfoListByRoomId($params['roomId']));
            $messageAll['data']['count'] = $count;
            static::broadcast($server, $fdList, $messageAll, $params['roomId']);
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
        $params = $message['data'];
        if (!empty($params)) {
            if (self::isManager($params['roomId'], $frame->fd)) {
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
        $params = $message['data'];
        $ip = self::getWsIp($params['roomId']);
        $redis = RedisClient::getInstance();
        // 判断adminUserId是否有权限踢人
//        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $params['roomId'];
//        $adminUserId = $redis->hget($keyWSRoomFD, $frame->fd);
        if (self::isManager($params['roomId'], $frame->fd)) {
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
                $fdList = LiveService::fdListByRoomId($server, $params['roomId']);
                static::broadcast($server, $fdList, $messageAll, $params['roomId']);
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
            self::clearLMList($params);
        }
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
            try {
                $server->push(intval($userInfo['fd']), json_encode($responseMessage));
            } catch (ErrorException $ex) {
                ll($ex->getMessage(), __FUNCTION__ . '.log');
            }
        }
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
    }

    public static function responseLMList($server, $frame, $message)
    {
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
        $responseMessage = [
            'messageType' => "close",
        ];
        $local_ip = IPUtils::get_local_ip();
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
            $keyLatestHeartbeat = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
            $latestHeartbeat = $redis->hget($keyLatestHeartbeat, $userId);
            if (!empty($latestHeartbeat)) {
                $latestHeartbeat = explode('_', $latestHeartbeat);
                $keyWSRoomUser = Constants::WS_ROOM_USER . $roomId;
                $userInfo = json_decode($redis->hget($keyWSRoomUser, $userId), true);
                switch ($latestHeartbeat[1]) {
                    case 0: // 观众
                        self::leave($fd, $roomId);
                        break;
                    case 1: // 主播
                        if ((time() - $latestHeartbeat[2]) <= Constants::WS_HEARTBEAT_IDLE_TIME) {
                            self::leave($fd, $roomId);
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
                    static::leaveRoom($server, null, $message, $fd, true);
                    $redis->hdel($keyLatestHeartbeat, $userId);
                }
            } else {
                self::leave($fd, $roomId);
            }

            $responseMessage['data'] = [
                'data' => [
                    'roomId' => $roomId,
                    'userId' => $userId,
                    'roleId' => $roleId
                ]
            ];
        }
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

    public static function openConnection($fd)
    {
        $redis = RedisClient::getInstance();
        if (!$redis->exists(Constants::WS_CONNECTION)) {
            $redis->hset(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 1);
        } else {
            $redis->hIncrby(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 1);
        }
        $redis->expire(Constants::WS_CONNECTION, Constants::WS_DEFAULT_EXPIRE);
        ll("{$fd} connection open，连接数：" . $redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION), 'webSocketMessage.log');
    }

    /**
     * 更新链接 webSocket 数量
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
}