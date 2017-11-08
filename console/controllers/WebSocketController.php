<?php

namespace app\console\controllers;

use app\common\components\RedisClient;
use app\common\models\Gift;
use app\common\models\Order;
use app\common\models\User;
use app\common\services\Constants;
use app\common\services\LiveService;
use yii\console\Controller;

class WebSocketController extends Controller
{
    //webSocket服务端
    public function actionServer()
    {
        $server = new \swoole_websocket_server(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT);
        $server->on('open', function ($server, $req) {
            echo "connection open: {$req->fd}\n";
        });
        $server->on('message', function ($server, $frame) {
            if (!empty($frame->data)) {
                $message = json_decode($frame->data, true);
                switch ($message['messageType']) {
                    case Constants::MESSAGE_TYPE_BARRAGE_REQ://弹幕
                        LiveService::barrageRequest($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_SERVER_INFO_REQ://服务器信息
                        LiveService::serverInfoRequest($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_GIFT_REQ://送礼物
                        LiveService::giftRequest($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_HEARTBEAT_REQ://心跳
                        LiveService::heartbeatRequest($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_JOIN_REQ: // 进入房间 含机器人
                        LiveService::joinRoomAndAI($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_LEAVE_REQ: // 离开房间
                        LiveService::leaveRoom($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_KICK_REQ: // 踢人
                        LiveService::kickUser($server, $frame, $message);
                        break;
                    default:
                        $server->push($frame->fd, json_encode(["message not match", $frame->fd]));
                }
            }
            ll($frame->data, 'webSocketMessage');
        });
        $server->on('close', function ($server, $fd) {
            echo "connection close: {$fd}\n";
        });
        $server->start();
    }

    //送礼物消费队列
    public function actionGiftOrder()
    {
        $redis = RedisClient::getInstance();
        while ($order = $redis->rpop(Constants::WSGIFTORDER)) {
            $order = json_decode(base64_decode($order), true);
            $giftId = $order['giftId'];
            $userId = $order['userId'];
            $userIdTo = $order['userIdTo'];
            $price = $order['price'];
            $num = $order['num'];
            $user = User::queryById($userId);
            if (!empty($user)) {
                $gift = Gift::queryById($giftId);
                if(!empty($gift)){
                    $priceReal = $gift['price'] * $num;
                    //更新余额
                    $stat = User::updateUserBalance($userId, -$priceReal);
                    if ($stat > 0) {
                        //购买礼物记录
                        Order::create($giftId, $userId, $userIdTo, $price, $num);
                        $balance = $user['balance'] - $priceReal;
                        $redis->hset('WSUserBalance', $userId, $balance);
                    }
                }
            }
        }
    }
}