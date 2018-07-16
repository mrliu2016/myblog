<?php

namespace app\common\services;

use app\common\components\IPUtils;
use app\common\components\RedisClient;
use app\common\extensions\OSS\Result\BodyResult;
use Yii;

class LiveService
{
    //弹幕（关键字过滤、数据不保存）
    public static function barrageRequest($server, $frame, $message)
    {
        try {
            $param = $message['data'];
            $gagKey = Constants::WS_GAG . static::getWsIp($message['data']['roomId']) . '_' . $message['data']['roomId'];
            if ($server->redis->hget($gagKey, $message['data']['userId'])) {
                static::pushGagMessage($frame->fd, $server, $frame, $message, false);
                return true;
            }
            $keyWords = [];
            if ($server->redis->exists(Constants::WS_BANNED_WORD)) {
                $keyWords = json_decode(base64_decode($server->redis->get(Constants::WS_BANNED_WORD)), true);
            }
            $keyWords = array_combine($keyWords, array_fill(0, count($keyWords), '*'));

            $respondMessage = [
                'messageType' => Constants::MESSAGE_TYPE_BARRAGE_RES,
                'code' => Constants::CODE_SUCCESS,
                'message' => strtr($param["message"], $keyWords),
                'data' => [
                    'roomId' => $param["roomId"],
                    'userId' => $param["userId"],
                    'nickName' => $param["nickName"],
                    'avatar' => $param["avatar"],
                    'fly' => isset($param["fly"]) ? (int)$param["fly"] : 1 // 1 弹幕 0 普通
                ]
            ];
            $taskId = $server->task($respondMessage);
        } catch (\Exception $exception) {
            static::webSocketLog($exception->getMessage(), __FUNCTION__ . '.log', true);
        }
    }

    //送礼物
    public static function giftRequest($server, $frame, $message)
    {
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || empty($param["userIdTo"]) || empty($param["giftId"]) || empty($param["price"])
            || empty($param["num"]) || empty($param["nickName"]) || !isset($param["level"]) || !isset($param["isFire"])
        ) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
            $respondMessage['code'] = Constants::CODE_FAILED;
            $respondMessage['message'] = 'parameter error';
            $respondMessage['data'] = array();
            $server->push($frame->fd, json_encode($respondMessage));
            return;
        }
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
        $isFire = $param["isFire"];
        $balance = $server->redis->hget(Constants::WS_USER_BALANCE, $userId);
        $balance = $balance - $price * $num;
        if ($balance < 0) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
            $respondMessage['code'] = Constants::CODE_FAILED;
            $respondMessage['message'] = '余额不足';
            $respondMessage['data'] = array();
            $server->push($frame->fd, json_encode($respondMessage));
            return;
        }
        //更新余额
        $server->redis->hset(Constants::WS_USER_BALANCE, $userId, $balance);
        //购买礼物队列
        $order = array(
            'giftId' => $giftId,
            'userId' => $userId,
            'userIdTo' => $userIdTo,
            'num' => $num,
            'price' => $price,
            'roomId' => $roomId
        );
        $server->redis->lpush(Constants::QUEUE_WS_GIFT_ORDER, base64_encode(json_encode($order)));
        $server->redis->expire(Constants::QUEUE_WS_GIFT_ORDER, Constants::DEFAULT_EXPIRES);
        //单人广播
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = '';
        $respondMessage['data'] = [
            'roomId' => $roomId,
            'userId' => $userId,
            'userIdTo' => $userIdTo,
            'giftId' => $giftId,
            'price' => $price,
            'num' => $num,
            'balance' => !empty($balance) ? $balance : 0, // 去掉分，webSocket不涉及业务，交易类结算以最小单位透传
            'income' => static::computeUnit(static::masterIncome($server, $userIdTo, $roomId)),
        ];
        $server->push($frame->fd, json_encode($respondMessage));
        static::sendGiftVirtualCurrency($server, $userId, $userIdTo, $roomId, $price * $num);
        unset($respondMessage);
        //广播房间全体成员
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_NOTIFY_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = '';
        $respondMessage['data'] = [
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
            'income' => static::computeUnit(static::masterIncome($server, $userIdTo, $roomId)),
            'isFire' => $isFire
        ];
        $taskId = $server->task($respondMessage);
    }

    /**
     * 送礼、接收虚拟货币
     *
     * @param $server
     * @param $userId
     * @param $masterUserId
     * @param $roomId
     * @param $virtualCurrency
     */
    private static function sendGiftVirtualCurrency($server, $userId, $masterUserId, $roomId, $virtualCurrency)
    {
        $wsIp = static::getWsIp($roomId);
        $key = Constants::WS_SEND_GIFT_VIRTUAL_CURRENCY . $wsIp . ':' . $roomId;
        $server->redis->hIncrby($key, $userId, intval($virtualCurrency)); // 用户送礼虚拟货币
        $server->redis->expire($key, Constants::WS_DEFAULT_EXPIRE);

//        $key = Constants::WS_ROOM_USER . $wsIp . '_' . $roomId;
//        $userInfo = json_decode($redis->hget($key, $userId), true);
//        if (!empty($userInfo)) {
//            $userInfo['virtualCurrency'] += $virtualCurrency;
//            $redis->hset($key, $userId, json_encode($userInfo));
//        }

        // 主播-总收益
        $key = Constants::WS_INCOME . $wsIp . ':' . $roomId;
        $server->redis->hIncrby($key, $masterUserId, intval($virtualCurrency)); // 主播接收礼物虚拟货币
        $server->redis->expire($key, Constants::WS_DEFAULT_EXPIRE);

        // 主播-本场直播收益
        $key = Constants::WS_MASTER_CURRENT_INCOME . $wsIp . ':' . $roomId;
        $server->redis->hIncrby($key, $masterUserId, intval($virtualCurrency)); // 主播接收礼物虚拟货币
        $server->redis->expire($key, Constants::WS_DEFAULT_EXPIRE);
    }

    /**
     * 心跳
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function heartbeatRequest($server, $frame, $message)
    {
        $param = $message['data'];
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        if ($param['isMaster'] == Constants::WS_ROLE_MASTER) {
            $server->redis->lpush(Constants::QUEUE_WS_HEARTBEAT,
                base64_encode(json_encode(['userId' => $userId, 'roomId' => $roomId, 'streamId' => $param['streamId']])));
            $server->redis->expire(Constants::QUEUE_WS_HEARTBEAT, Constants::DEFAULT_EXPIRES);
        }
        static::latestHeartbeat($server, $frame->fd, $userId, $roomId, $param['isMaster']);
    }

    /**
     * 记录最新一次心跳时间
     *
     * @param $server
     * @param $fd
     * @param $userId
     * @param $roomId
     * @param $isMaster
     */
    public static function latestHeartbeat($server, $fd, $userId, $roomId, $isMaster)
    {
        $key = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
        $server->redis->hset($key, $userId, $fd . '_' . $isMaster . '_' . time());
        $server->redis->expire($key, Constants::WS_DEFAULT_EXPIRE);
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
        static::join($server, $frame->fd, $params["userId"], $params["roomId"], $params["role"],
            $params["avatar"], $params["nickName"], $params["level"], $params['balance'], isset($params['income']) ? $params['income'] : 0);
        $userList = array_values(LiveService::getUserInfoListByRoomId($server, $params['roomId'], 'virtualCurrency', true));
        $roomMemberNum = static::computeUnit(count($userList) <= Constants::NUM_WS_ROOM_USER ? count($userList) : LiveService::roomMemberNum($server, $params['roomId']));

        $resMessage = [
            'messageType' => Constants::MESSAGE_TYPE_JOIN_RES,
            'code' => Constants::CODE_SUCCESS,
            'message' => Constants::WS_NOTICE,
            'fd' => $frame->fd,
            'data' => [
                'roomId' => $params['roomId'],
                'userId' => $params["masterUserId"],
                'avatar' => $params["masterAvatar"],
                'nickName' => $params["masterNickName"],
                'level' => intval($params["masterLevel"]),
                'income' => static::computeUnit(static::masterIncome($server, $params['masterUserId'], $params['roomId'])),
                'count' => $roomMemberNum,
                'userList' => $userList,
                'balance' => $params['balance']
            ],
        ];
//        $taskId = $server->task($resMessage);
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
                'userList' => $userList,
                'income' => static::computeUnit(static::masterIncome($server, $params['masterUserId'], $params['roomId']))
            ]
        ];
        $taskId = $server->task($messageAll);
    }

    //获取webSocket服务ip
    public static function getWsIp($roomId)
    {
        $roomId = ctype_digit($roomId) ? $roomId : ord($roomId);
        $index = $roomId % count(Yii::$app->params['wsServer']);
        $roomServer = Yii::$app->params['wsServer'][$index];
        return $roomServer['ip'];
    }

    //返回房间内用户信息
    public static function getUserInfoListByRoomId($server, $roomId, $order = 'userId', $isSort = false)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $roomId;
        $result = $server->redis->hGetAll($keyWSRoomUser);
        $itemList = [];
        if (empty($result)) return [];
        foreach ($result as $key => $value) {
            $tmpItem = json_decode($value, true);
            switch ($order) {
                case 'virtualCurrency':
                    $orderKey = !empty($tmpItem['virtualCurrency']) ? $tmpItem['virtualCurrency'] : rand(100, 999);
                    break;
                default:
                    $orderKey = $key;
                    break;
            }
            if (isset($tmpItem['virtualCurrency'])) {
                unset($tmpItem['virtualCurrency']);
            }
            if ($tmpItem['role'] != Constants::WS_ROLE_MASTER) {
                $itemList[$orderKey] = $tmpItem;
            }
        }
        if ($isSort) {
            krsort($itemList);
        }
        return $itemList;
    }

    //加入房间
    private static function join($server, $fd, $userId, $roomId, $role, $avatar, $nickName, $level, $balance, $income)
    {
        //服务器fd映射关系，异常退出用
        $ip = self::getWsIp($roomId);
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $ip;
        $server->redis->hset($keyWSRoomLocation, $fd, $roomId . '_' . $userId . '_' . $role);
        $server->redis->expire($keyWSRoomLocation, Constants::DEFAULT_EXPIRES);

        //房间的fd列表，群发消息用
        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $roomId;
        $keyWSRoomFDTimeout = 48 * 60 * 60;
        $server->redis->hset($keyWSRoomFD, $fd, $userId);
        $server->redis->expire($keyWSRoomFD, $keyWSRoomFDTimeout);

        //房间用户头像保存100个
        $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $roomId;
        $num = $server->redis->hLen($keyWSRoomUser);
        if ($num < Constants::NUM_WS_ROOM_USER) {
            $keyWSRoomUserTimeout = 48 * 60 * 60;
            $userInfo['userId'] = $userId;
            $userInfo['nickName'] = $nickName;
            $userInfo['avatar'] = $avatar;
            $userInfo['level'] = $level;
            $userInfo['fd'] = $fd;
            $userInfo['role'] = $role;
            $userInfo['virtualCurrency'] = intval($server->redis->hget(Constants::WS_SEND_GIFT_VIRTUAL_CURRENCY . $ip . ':' . $roomId, $userId));
            $userInfo['startTime'] = time();
            $server->redis->hset($keyWSRoomUser, $userId, json_encode($userInfo));
            $server->redis->expire($keyWSRoomUser, $keyWSRoomUserTimeout);
        }

        // 用户余额
        $server->redis->hset(Constants::WS_USER_BALANCE, $userId, $balance);
        $server->redis->expire(Constants::WS_USER_BALANCE, Constants::DEFAULT_EXPIRES);

        // 主播总收益
        switch ($role) {
            case Constants::WS_ROLE_MASTER:
                $key = Constants::WS_INCOME . $ip . ':' . $roomId;
                $server->redis->hset($key, $userId, intval($income));
                $server->redis->expire($key, Constants::DEFAULT_EXPIRES);
                break;
        }
    }

    /**
     * 主播收益
     *
     * @param $server
     * @param $masterUserId
     * @param $roomId
     * @return string
     */
    private static function masterIncome($server, $masterUserId, $roomId)
    {
        $ip = self::getWsIp($roomId);
        $key = Constants::WS_INCOME . $ip . ':' . $roomId;
        if ($server->redis->exists($key)) {
            return $server->redis->hget($key, $masterUserId);
        }
        return '0';
    }

    //房间fd列表
    public static function fdListByRoomId($server, $roomId)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $roomId;
        $result = $server->redis->hGetAll($keyWSRoomFD);
        if (empty($result)) return [];
        return array_keys($result);
    }

    //房间成员数量
    public static function roomMemberNum($server = null, $roomId = null)
    {
        $wsIp = self::getWsIp($roomId);
        $keyWSRoomFD = Constants::WS_ROOM_FD . $wsIp . '_' . $roomId;
        if (empty($server)) {
            $num = RedisClient::getInstance()->hLen($keyWSRoomFD);
        } else {
            $num = $server->redis->hLen($keyWSRoomFD);
        }
        return intval($num);
    }

    /**
     * 退出房间
     *
     * @param $server
     * @param $frame
     * @param $message
     * @param int $fd
     * @param bool $isExceptionExit
     */
    public static function quitRoom($server, $frame, $message, $fd = 0, $isExceptionExit = false)
    {
        $params = $message['data'];
        if (!empty($params)) {
            $messageAll = [
                'messageType' => Constants::MESSAGE_TYPE_QUIT_RES,
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
            $messageAll['data']['income'] = static::isManager($server, $params['roomId'], $isExceptionExit ? $fd : $frame->fd) ? static::masterCurrentIncome($server, $params['roomId'], $params['userId']) : '0';
            $messageAll['data']['duration'] = static::isManager($server, $params['roomId'], $isExceptionExit ? $fd : $frame->fd) ? static::computeDuration($server, $params['roomId'], $params['userId']) : '00:00';
            self::leave($server, $isExceptionExit ? $fd : $frame->fd, $params['roomId']);
            self::clearLMList($server, $params);
            $userList = LiveService::getUserInfoListByRoomId($server, $params['roomId'], 'virtualCurrency', true);
            $messageAll['data']['userList'] = array_values($userList);
            $messageAll['data']['count'] = static::computeUnit(count($userList) <= Constants::NUM_WS_ROOM_USER ? count($userList) : LiveService::roomMemberNum($server, $params['roomId']));
            static::broadcast($server, $fdList, $messageAll, $params['roomId']);
        }
    }

    //处理离开房间
    private static function leave($server, $fdId, $roomId)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $ip;
        $isManager = static::isManager($server, $roomId, $fdId);
        //删除服务器fd 映射关系
        $server->redis->hdel($keyWSRoomLocation, $fdId);
        //删除房间用户
        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $roomId;
        $userId = $server->redis->hget($keyWSRoomFD, $fdId);
        if (!empty($userId)) {
            $server->redis->hdel($keyWSRoomFD, $fdId);
            //删除房间用户头像
            $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $roomId;
            $server->redis->hdel($keyWSRoomUser, $userId);
        }
        // 删除心跳
        $keyLatestHeartbeat = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
        $server->redis->hdel($keyLatestHeartbeat, $userId);
        // 退出用户是否为主播
        if ($isManager) {
            $server->redis->del(Constants::WS_GAG . $ip . '_' . $roomId); // 禁言
//                $server->redis->hdel(Constants::WS_INCOME . $ip . ':' . $roomId, $info['userId']); // 主播接收礼物虚拟货币

            // 主播-本场直播收益
//                $key = Constants::WS_MASTER_CURRENT_INCOME . $wsIp . ':' . $roomId;
        }
        // 删除收益
        $server->redis->hdel(Constants::WS_SEND_GIFT_VIRTUAL_CURRENCY . $ip . ':' . $roomId, $userId);
        static::updateConnection($server);
    }

    //禁言
    public static function gag($server, $frame, $message)
    {
        $params = $message['data'];
        $ip = static::getWsIp($params['roomId']);
        $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $message['data']['roomId'];
        $gagUserInfo = json_decode($server->redis->hget($keyWSRoomUser, $message['data']['userId']), true);
        if (!empty($gagUserInfo)) {
            if (self::isManager($server, $message['data']['roomId'], $frame->fd)) {
                static::pushGagMessage($gagUserInfo['fd'], $server, $frame, $message);
                // 禁言
                $gagKey = Constants::WS_GAG . $ip . '_' . $message['data']['roomId'];
                $server->redis->hset($gagKey, $message['data']['userId'], true);
                $server->redis->expire($gagKey, Constants::DEFAULT_EXPIRES);
            } else {
                $respondMessage = [
                    'messageType' => Constants::MESSAGE_TYPE_GAG_RES,
                    'code' => Constants::CODE_FAILED,
                    'message' => '禁止操作',
                    'data' => []
                ];
                $server->push($frame->fd, json_encode($respondMessage));
                ll($respondMessage, 'webSocketMessage.log');
            }
        }
    }

    /**
     * 推送禁言消息
     *
     * @param $fd
     * @param $server
     * @param $frame
     * @param $message
     * @param bool $isBroadcast
     */
    private static function pushGagMessage($fd, $server, $frame, $message, $isBroadcast = true)
    {
        $respondMessage = [
            'messageType' => Constants::MESSAGE_TYPE_GAG_RES,
            'code' => Constants::CODE_FAILED,
            'message' => '你已被主播禁言',
            'data' => [
                'userId' => $message['data']['userId'],
//                'adminUserId' => $message['data']['adminUserId'],
                'roomId' => $message['data']['roomId'],
                'nickName' => $message['data']['nickName']
            ]
        ];
//        //广播房间全体成员
        if ($isBroadcast) {
            $taskId = $server->task($respondMessage);
        } else {
            $server->push(intval($fd), json_encode($respondMessage));
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
        // 判断adminUserId是否有权限踢人
//        $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $params['roomId'];
//        $adminUserId = $redis->hget($keyWSRoomFD, $frame->fd);
        if (self::isManager($server, $params['roomId'], $frame->fd)) {
            $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $params['roomId'];
            $user = $server->redis->hget($keyWSRoomUser, $params['userId']);
            if (!empty($user)) {
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
                $taskId = $server->task($messageAll);
            } else {
                $respondMessage['messageType'] = Constants::MESSAGE_TYPE_KICK_RES;
                $respondMessage['code'] = Constants::CODE_FAILED;
                $respondMessage['message'] = 'permission deny';
                $respondMessage['data'] = [];
                $server->push($frame->fd, json_encode($respondMessage));
            }
            self::clearLMList($server, $params);
        }
    }

    /*
     * 是否有房间管理员权限
     * roomId_userId_role
     * */
    private static function isManager($server, $roomId, $fd)
    {
        $ip = self::getWsIp($roomId);
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $ip;
        $userInfo = $server->redis->hget($keyWSRoomLocation, $fd);
        if (empty($userInfo)) return false;
        $userInfo = explode('_', $userInfo);
        //role 0观众1主播
        if (empty($userInfo[2])) return false;
        return true;
    }

    //服务器信息
    public static function serverInfo($param)
    {
        $roomId = ctype_digit($param["roomId"]) ? $param["roomId"] : ord($param["roomId"]);
        $index = $roomId % count(Yii::$app->params['wsServer']);
        $wsServer = Yii::$app->params['wsServer'][$index];
//        $domain = static::distributeServerNode($param['userId'], Yii::$app->params['wsServer']);
        return [
            'roomServer' => [
                'host' => $wsServer['domain'],
                'port' => Constants::WEB_SOCKET_PORT,
            ],
            'roomServer-wss' => [
                'host' => $wsServer['domain'],
                'port' => Constants::WEB_SOCKET_PORT_SSL,
            ]
        ];
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
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($server->redis->hget($keyWSRoomUser, $messageInfo['adminUserId']), true);
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
     * 申请连麦列表
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function requestLMList($server, $frame, $message)
    {
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $adminUserInfo = json_decode($server->redis->hget($keyWSRoomUser, $messageInfo['adminUserId']), true);
        if (!empty($adminUserInfo)
            && $adminUserInfo['role'] == Constants::WS_ROLE_MASTER) {
            $lmUser = [
                'userId' => $messageInfo['userId'],
                'nickName' => $messageInfo['nickName'],
                'avatar' => $messageInfo['avatar'],
                'roomId' => $messageInfo['roomId'],
                'fd' => intval($frame->fd),
                'type' => Constants::LM_APPLY,
                'lmType' => $messageInfo['lmType'], // video:视频，audio:音频
                'mute' => Constants::LM_CLOSE_MUTE,
                'isMaster' => Constants::WS_ROLE_AUDIENCE
            ];
            $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $messageInfo['roomId'];
            $server->redis->hset($keyWSRoomUserLMList, $messageInfo['userId'], json_encode($lmUser));
            $server->redis->expire($keyWSRoomUserLMList, Constants::DEFAULT_EXPIRES);

            $lmUserList = LiveService::getUserLMListByRoomId($server, $messageInfo['roomId']);
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_LM_LIST_RES,
                'fd' => intval($adminUserInfo['fd']),
                'data' => [
                    'userId' => $messageInfo['userId'],
                    'nickName' => $messageInfo['nickName'],
                    'avatar' => $messageInfo['avatar'],
                    'roomId' => $messageInfo['roomId'],
                    'type' => Constants::LM_APPLY,
                    'count' => count($lmUserList),
                    'lmType' => $messageInfo['lmType'], // video:视频，audio:音频
                    'mute' => Constants::LM_CLOSE_MUTE,
                    'isMaster' => Constants::WS_ROLE_AUDIENCE
                ]
            ];
            $server->task($responseMessage);
//            $server->push(intval($adminUserInfo['fd']), json_encode($responseMessage));
        }
    }

    /**
     * 获取连麦用户列表
     *
     * @param $server
     * @param $roomId
     * @return array
     */
    public static function getUserLMListByRoomId($server, $roomId)
    {
        $wsIp = self::getWsIp($roomId);
        $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $roomId;
        $result = $server->redis->hGetAll($keyWSRoomUserLMList);
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
        $keyWSRoomUser = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($server->redis->hget($keyWSRoomUser, $messageInfo['userId']), true);
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

    /**
     * 连麦同意、拒绝请求
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function responseLMList($server, $frame, $message)
    {
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);

        $masterUserKey = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $masterUserInfo = json_decode($server->redis->hget($masterUserKey, $messageInfo['adminUserId']), true);
        if (!empty($masterUserInfo) && $masterUserInfo['role'] == Constants::WS_ROLE_MASTER) {

            $key = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $messageInfo['roomId'];
            $userInfo = json_decode($server->redis->hget($key, $messageInfo['userId']), true);

            if ($server->exist($userInfo['fd'])) {//在线
                $userInfo['type'] = intval($messageInfo['type']);
                $server->redis->hset($key, $messageInfo['userId'], json_encode($userInfo));
                $responseMessage = [
                    'messageType' => Constants::MESSAGE_TYPE_LM_AGREE_OR_REFUSE_RES,
                    'data' => [
                        'adminUserId' => $messageInfo['adminUserId'],
                        'userId' => $messageInfo['userId'],
                        'roomId' => $messageInfo['roomId'],
                        'type' => intval($messageInfo['type']), // 2：同意,3：拒绝
                        'nickName' => $userInfo['nickName'],
                        'avatar' => $userInfo['avatar'],
                        'lmType' => $userInfo['lmType'], // video:视频，audio:音频
                        'mute' => $userInfo['mute']
                    ]
                ];
                $server->task($responseMessage);
//                $server->push(intval($userInfo['fd']), json_encode($responseMessage));
            } else {//离线
                $server->redis->hdel($key, $messageInfo['userId']);//将用户信息从列表中删除
                $responseMessage = [
                    'messageType' => Constants::MESSAGE_TYPE_LM_AGREE_OR_REFUSE_RES,
                    'data' => [
                        'adminUserId' => $messageInfo['adminUserId'],
                        'userId' => $userInfo['userId'],
                        'roomId' => $userInfo['roomId'],
                        'type' => Constants::LM_USER_OFFLINE, // 6:离线
                        'nickName' => $userInfo['nickName'],
                        'avatar' => $userInfo['avatar'],
                        'lmType' => $userInfo['lmType'], // video:视频，audio:音频
                        'mute' => $userInfo['mute']
                    ]
                ];
                $server->task($responseMessage);
//                $server->push(intval($masterUserInfo['fd']), json_encode($responseMessage));
            }
        }
    }

    /**
     * 更新连麦用户列表
     *
     * @param $messageInfo
     * @param $userInfo
     */
    private static function updateLMUserList($server, $messageInfo, $userInfo)
    {
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $key = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $messageInfo['roomId'];
        switch ($messageInfo['type']) {
            case Constants::LM_TYPE_AGREE:
                $userInfo['type'] = intval($messageInfo['type']);
                $server->redis->hset($key, $messageInfo['userId'], json_encode($userInfo));
                break;
            case Constants::LM_TYPE_REFUSE:
                $server->redis->hdel($key, $messageInfo['userId']);
                break;
            default:
                break;
        }
    }

    /**
     * 清空连麦用户列表
     *
     * @param $server
     * @param $params
     */
    public static function clearLMList($server, $params)
    {
        $wsIp = self::getWsIp($params['roomId']);
        $keyWSRoomUserLMList = Constants::WS_ROOM_USER_LM_LIST . $wsIp . '_' . $params['roomId'];
        if (isset($params['isMaster'])) {
            $server->redis->expire($keyWSRoomUserLMList, 0);
        } else {
            $server->redis->hdel($keyWSRoomUserLMList, $params['userId']);
        }
    }

    /**
     * socket 异常断开
     *
     * @param $server
     * @param $fd
     */
    public static function fdClose($server, $fd)
    {
        $responseMessage = [
            'messageType' => "close",
        ];
        $local_ip = IPUtils::get_local_ip();
        //服务器fd映射关系，异常退出用
        $keyWSRoomLocation = Constants::WS_ROOM_LOCATION . $local_ip;
        $info = $server->redis->hget($keyWSRoomLocation, $fd);
        if (!empty($info)) {
            $info = explode("_", $info);
            $roomId = $info[0];
            $userId = $info[1];
            $roleId = $info[2];
            // 房间用户信息
//            $keyWSRoomUser = Constants::WS_ROOM_USER . $local_ip . '_' . $roomId;
//            $redis->hdel($keyWSRoomUser, $userId);
            //房间的fd列表
//            $keyWSRoomFD = Constants::WS_ROOM_FD . $local_ip . '_' . $roomId;
//            $redis->hdel($keyWSRoomFD, $fd);
            //删除fd数据
//            $redis->hdel($keyWSRoomLocation, $fd);

            // 心跳
            static::keepUserRoomMap($server, $fd, $userId, $roomId, $roleId, $local_ip);

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
     * 切换网络情况下，心跳在时间阈值内，清除 fd 对应数据
     *
     * 主播超出心跳阈值，清除多有映射关系
     *
     * @param $server
     * @param $fd
     * @param $userId
     * @param $roomId
     * @param $roleId
     * @param $local_ip
     */
    private static function keepUserRoomMap($server, $fd, $userId, $roomId, $roleId, $local_ip)
    {
        $keyLatestHeartbeat = Constants::WS_LATEST_HEARTBEAT_TIME . ':' . $roomId;
        $latestHeartbeat = $server->redis->hget($keyLatestHeartbeat, $userId);
        if (!empty($latestHeartbeat)) {
            $latestHeartbeat = explode('_', $latestHeartbeat);
            $userInfo = json_decode($server->redis->hget(Constants::WS_ROOM_USER . $roomId, $userId), true);

            switch ($latestHeartbeat[1]) {
                case 0: // 观众
                    static::leave($server, $fd, $roomId);
                    break;
                case 1: // 主播
                    if ((time() - $latestHeartbeat[2]) <= Constants::WS_HEARTBEAT_IDLE_TIME) {
                        static::leave($server, $fd, $roomId);
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
                static::quitRoom($server, null, $message, $fd, true);
            }
        } else {
            static::leave($server, $fd, $roomId);
        }
        if (static::isLMUser($server, null, ['userId' => $userId, 'roomId' => $roomId, 'role' => $roleId])) {
            $masterUserInfo = static::getMasterUserinfo($server, $roomId);
            $responseMessage = [
                'data' => [
                    'adminUserId' => $masterUserInfo['userId'],
                    'userId' => $userId,
                    'roomId' => $roomId,
                    'role' => $roleId
                ]
            ];
            static::forwardingCloseCallLMPushMaster($server, null, $responseMessage, Constants::MESSAGE_TYPE_CLOSE_CALL_RES);
        }
    }

    /**
     * 主播断开连麦
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function closeCall($server, $frame, $message)
    {
        if (static::isManager($server, $message['data']['roomId'], $frame->fd)) {
            static::forwardingCloseCallLM($server, $frame, $message, Constants::MESSAGE_TYPE_CLOSE_CALL_RES);
        }
    }

    /**
     * 副播断开连麦
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function secondaryCloseCall($server, $frame, $message)
    {
        static::forwardingCloseCallLM($server, $frame, $message, Constants::MESSAGE_TYPE_CLOSE_CALL_RES);
//        static::forwardingCloseCallLMPushMaster($server, $frame, $message, Constants::MESSAGE_TYPE_CLOSE_CALL_RES);

    }

    /**
     * 转发断开连麦
     *
     * @param $server
     * @param $frame
     * @param $message
     * @param string $messageType
     */
    private static function forwardingCloseCallLM($server, $frame, $message, $messageType = Constants::MESSAGE_TYPE_CLOSE_CALL_RES)
    {
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $key = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $messageInfo['roomId'];
        $userInfo = json_decode($server->redis->hget($key, $messageInfo['userId']), true);
        if (!empty($userInfo)) {
            $responseMessage = [
                'messageType' => $messageType,
                'data' => [
                    'adminUserId' => $messageInfo['adminUserId'],
                    'roomId' => $messageInfo['roomId'],
                    'userId' => $messageInfo['userId'],
                    'type' => Constants::LM_TYPE_CLOSE, // 4：断开连麦
                    'lmType' => $userInfo['lmType']
                ]
            ];
            $server->task($responseMessage);
//            $server->push(intval($userInfo['fd']), json_encode($responseMessage));
            $server->redis->hdel($key, $messageInfo['userId']);
        }
    }

    /**
     * 副播断开连麦-推送消息给主播
     *
     * @param $server
     * @param $frame
     * @param $message
     * @param $messageType
     */
    private static function forwardingCloseCallLMPushMaster($server, $frame, $message, $messageType)
    {
        $messageInfo = $message['data'];
        $wsIp = self::getWsIp($messageInfo['roomId']);
        $key = Constants::WS_ROOM_USER . $wsIp . '_' . $messageInfo['roomId'];
        $userInfo = json_decode($server->redis->hget($key, $messageInfo['adminUserId']), true);
        if (!empty($userInfo) && static::isManager($server, $messageInfo['roomId'], intval($userInfo['fd']))) {
            $responseMessage = [
                'messageType' => $messageType,
                'data' => [
                    'adminUserId' => $messageInfo['adminUserId'],
                    'roomId' => $messageInfo['roomId'],
                    'userId' => $messageInfo['userId'],
                    'type' => Constants::LM_TYPE_CLOSE // 4：断开连麦
                ]
            ];
            $server->push(intval($userInfo['fd']), json_encode($responseMessage));
        }
    }

    /**
     * 是否连麦用户
     *
     * @param $server
     * @param $frame
     * @param $params
     * @return bool
     */
    private static function isLMUser($server, $frame, $params)
    {
        $wsIp = self::getWsIp($params['roomId']);
        $key = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $params['roomId'];
        if ($server->redis->hexists($key, $params['userId'])) {
            return true;
        }
        return false;
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
        $respondMessage = json_encode($respondMessage);
        foreach ($fdList as $fd) {
            try {
                if (!$server->exist($fd)) {
                    self::leave($server, $fd, $roomId);
                } else {
                    $server->push($fd, $respondMessage);
                }
            } catch (\Exception $ex) {
                ll(var_export(array_merge(['codeMessage' => $ex->getMessage()], array("fd" => $fd)), true), 'webSocketMessage.log');
            }
        }
        return true;
    }

    /**
     * 统计 webSocket 打开连接数
     *
     * @param $server
     * @param $fd
     */
    public static function openConnection($server, $fd)
    {
        if (!$server->redis->exists(Constants::WS_CONNECTION)) {
            $server->redis->hset(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 1);
        } else {
            $server->redis->hIncrby(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 1);
        }
        $server->redis->expire(Constants::WS_CONNECTION, Constants::WS_DEFAULT_EXPIRE);
        static::webSocketLog(
            "{$fd} connection open，连接数：" . $server->redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION),
            'webSocketMessage.log',
            true
        );
    }

    /**
     * 更新 webSocket 打开连接数
     *
     * @param $server
     */
    public static function updateConnection($server)
    {
        $number = $server->redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION);
        if ($number > 0) {
            $server->redis->hIncrby(Constants::WS_CONNECTION, Constants::WS_CONNECTION, -1);
        } elseif ($number == 0) {
            $server->redis->hset(Constants::WS_CONNECTION, Constants::WS_CONNECTION, 0);
        } else {
            $server->redis->hdel(Constants::WS_CONNECTION, Constants::WS_CONNECTION);
        }
        static::webSocketLog(
            "连接数：" . $server->redis->hget(Constants::WS_CONNECTION, Constants::WS_CONNECTION),
            'webSocketMessage.log',
            true
        );
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
        if ($isRecord && YII_DEBUG) {
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
        foreach ($nodes as $key => $value) {
            // 每个节点的复制的个数
            for ($index = 1; $index <= Constants::WS_NODE_REPLICAS; $index++) {
                $crc = crc32($value['ip'] . '.' . $index) / pow(2, 32); // CRC値
                $buckets[] = array('index' => $crc, 'node' => $value['domain']);
            }
        }
        sort($buckets); // 根据索引进行排序

        /**
         * 对每个 userId 进行hash计算，找到其在圆上的位置，然后在该位置开始依顺时针方向找到第一个服务节点
         */
        $flag = false;
        $crc = crc32($userId) / pow(2, 32); // 计算 userId 的hash值
        $bucketsCount = count($buckets);
        for ($index = 0; $index < $bucketsCount; $index++) {
            if ($buckets[$index]['index'] > $crc) {
                /*
                 * 因为已经对buckets进行了排序
                 * 所以第一个index大于key的hash值的节点即是要找的节点
                 */
                return $buckets[$index]['node'];
            }
        }
        // 未找到，则使用 buckets 中的第一个服务节点
        if (!$flag) {
            return $buckets[0]['node'];
        }
    }

    /**
     * 禁播一天
     *
     * @param $server
     * @param $request
     * @param $response
     * @param $message
     */
    public static function prohibitLiveOneDay($server, $request, $response, $message)
    {
        static::forwardingProhibit($server, $request, $response, $message, Constants::MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_RES);
    }

    /**
     * 禁播30天
     *
     * @param $server
     * @param $request
     * @param $response
     * @param $message
     */
    public static function prohibitLive30Days($server, $request, $response, $message)
    {
        static::forwardingProhibit($server, $request, $response, $message, Constants::MESSAGE_TYPE_PROHIBIT_LIVE_30_DAYS_RES);
    }

    /**
     * 永久禁播
     *
     * @param $server
     * @param $request
     * @param $response
     * @param $message
     */
    public static function perpetualProhibitLive($server, $request, $response, $message)
    {
        static::forwardingProhibit($server, $request, $response, $message, Constants::MESSAGE_TYPE_PERPETUAL_PROHIBIT_LIVE_RES);
    }

    /**
     * 禁封账号
     *
     * @param $server
     * @param $request
     * @param $response
     * @param $message
     */
    public static function prohibitAccountNumber($server, $request, $response, $message)
    {

        static::forwardingProhibit($server, $request, $response, $message, Constants::MESSAGE_TYPE_PROHIBIT_ACCOUNT_NUMBER_RES);
    }

    /**
     * 转发禁播
     *
     * @param $server
     * @param $request
     * @param $response
     * @param $message
     * @param string $messageType
     */
    private static function forwardingProhibit($server, $request, $response, $message, $messageType = Constants::MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_RES)
    {
        $ip = static::getWsIp($message['data']['roomId']);
        $key = Constants::WS_ROOM_USER . $ip . '_' . $message['data']['roomId'];
        $userInfo = json_decode($server->redis->hget($key, $message['data']['userId']), true);
        if (!empty($userInfo)) {
            $responseMessage = [
                'messageType' => $messageType,
                'data' => [
                    'userId' => $message['data']['userId'],
                    'roomId' => $message['data']['roomId'],
                    'message' => $message['data']['message']
                ]
            ];
            $server->push(intval($userInfo['fd']), json_encode($responseMessage));
        }
    }

    /**
     * 拉黑
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function blacklist($server, $frame, $message)
    {
        $ip = self::getWsIp($message['data']['roomId']);
        // 判断adminUserId是否有权限拉黑
        if (self::isManager($server, $message['data']['roomId'], $frame->fd)) {
            $keyWSRoomUser = Constants::WS_ROOM_USER . $ip . '_' . $message['data']['roomId'];
            $userInfo = json_decode($server->redis->hget($keyWSRoomUser, $message['data']['blacklistUserId']), true);
            if (!empty($userInfo)) {
                $responseMessage = [
                    'messageType' => Constants::MESSAGE_TYPE_BLACKLIST_RES,
                    'code' => Constants::CODE_SUCCESS,
                    'message' => '你已被主播拉黑',
                    'data' => [
                        'userId' => $message['data']['userId'],
                        'roomId' => $message['data']['roomId'],
                        'blacklistUserId' => $message['data']['blacklistUserId']
                    ],
                ];
                $server->push(intval($userInfo['fd']), json_encode($responseMessage));
            }
        } else {
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_BLACKLIST_RES,
                'code' => Constants::CODE_FAILED,
                'message' => '禁止操作',
                'data' => []
            ];
            $server->push($frame->fd, json_encode($responseMessage));
        }
    }

    /**
     * 音视频连麦用户列表
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function audioVideoCallUserList($server, $frame, $message)
    {
        $wsIp = self::getWsIp($message['data']['roomId']);
        $key = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $message['data']['roomId'];
        if ($server->redis->exists($key)) {
            $userList = [];
            $lmList = $server->redis->hGetAll($key);
            foreach ($lmList as $key => $value) {
                $item = json_decode($value, true);
                switch ($item['type']) {
                    case Constants::LM_TYPE_AGREE:
                        $userList[$key] = $item;
                        break;
                }
            }
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_AUDIO_VIDEO_CALL_USER_LIST_RES,
                'fd' => $frame->fd,
                'data' => [
                    'userId' => $message['data']['userId'],
                    'roomId' => $message['data']['roomId'],
                    'userList' => !empty($userList) ? array_values($userList) : []
                ],
            ];
            $server->task($responseMessage);
        }
    }

    /**
     * 静音
     *
     * @param $server
     * @param $frame
     * @param $message
     */
    public static function mute($server, $frame, $message)
    {
        $wsIp = self::getWsIp($message['data']['roomId']);
        $key = Constants::WS_ROOM_USER_LM_LIST . $wsIp . ':' . $message['data']['roomId'];
        if ($server->redis->hexists($key, $message['data']['userId'])) {
            $lmUser = json_decode($server->redis->hget($key, $message['data']['userId']), true);
            $lmUser['mute'] = $message['data']['mute'];
            $lmUser['isMaster'] = $message['data']['isMaster'];
            $responseMessage = [
                'messageType' => Constants::MESSAGE_TYPE_MUTE_RES,
                'data' => $lmUser,
            ];
            $server->task($responseMessage);

            $server->redis->hset($key, json_encode($lmUser));
        }
    }

    /**
     * 将数字转换对应的单位
     *
     * @param $number
     * @return string
     */
    public static function computeUnit($number)
    {
        for ($index = 1; $index <= 10; $index++) {
            $base = pow(10, $index);
            if ($number >= pow(10, 4) && ($number < pow(10, 8))) {
                switch (strlen($base)) {
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        return sprintf('%.1f', $number / $base) . 'w+';
                        break;
                }
            } elseif ($number >= pow(10, 8)) {
                switch (strlen($base)) {
                    case 9:
                    case 10:
                    case 11:
                        return sprintf('%.1f', $number / $base) . '亿+';
                        break;
                }
            } else {
                return strval($number);
            }
        }
    }

    /**
     * 主播-本场直播收益
     *
     * @param $server
     * @param $roomId
     * @param $userId
     * @return string
     */
    public static function masterCurrentIncome($server, $roomId, $userId)
    {
        $wsIp = static::getWsIp($roomId);
        $key = Constants::WS_MASTER_CURRENT_INCOME . $wsIp . ':' . $roomId;
        $result = $server->redis->hget($key, $userId);
        return static::computeUnit($result);
    }

    /**
     * 计算时长
     *
     * @param $server
     * @param $roomId
     * @param $userId
     * @return string
     */
    private static function computeDuration($server, $roomId, $userId)
    {
        $wsIp = static::getWsIp($roomId);
        $key = Constants::WS_ROOM_USER . $wsIp . '_' . $roomId;
        $result = json_decode($server->redis->hget($key, $userId), true);
        return static::timestampsToTime(time() - $result['startTime']);
    }

    /**
     * 秒数转换为时间
     *
     * @param $times
     * @return string
     */
    private static function timestampsToTime($times)
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

    /**
     * 获取主播信息
     *
     * @param $server
     * @param $roomId
     * @return array|mixed
     */
    private static function getMasterUserinfo($server, $roomId)
    {
        $item = [];
        $wsIp = static::getWsIp($roomId);
        $key = Constants::WS_ROOM_USER . $wsIp . '_' . $roomId;
        $userInfo = $server->redis->hGetAll($key);
        foreach ($userInfo as $key => $value) {
            $item = json_decode($value, true);
            if ($item['role'] == Constants::WS_ROLE_MASTER) {
                return $item;
            }
        }
        return $item;
    }

    /**
     * 异步广播
     *
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $message
     */
    public static function asyncBroadcast($server, $task_id, $from_id, $message)
    {
        $roomAll = LiveService::fdListByRoomId($server, $message['data']['roomId']);
        static::broadcast($server, $roomAll, $message, $message['data']['roomId']);
    }

    /**
     * 向指定用户推送消息
     *
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $message
     */
    public static function asyncBroadcastToCurrentFD($server, $task_id, $from_id, $message)
    {
        if ($server->exist(intval($message['fd']))) {
            $server->push(intval($message['fd']), json_encode($message));
        }
    }
}