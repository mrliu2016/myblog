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
        $server = new \swoole_websocket_server(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT_SSL, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        $setConfig = [
            'ssl_key_file' => '/etc/nginx/cert/dev_api_demo.key',
            'ssl_cert_file' => '/etc/nginx/cert/dev_api_demo.pem',
            'heartbeat_check_interval' => Constants::WS_HEARTBEAT_CHECK_INTERVAL,
            'heartbeat_idle_time' => Constants::WS_HEARTBEAT_IDLE_TIME,
            'max_connection' => Constants::WS_WEB_SOCKET_MAX_CONNECTION, // 最大链接数
//            'worker_num' => Constants::WS_WORKER_NUM, // worker 数
            // swFactoryProcess_finish: send failed, session#1 output buffer has been overflowed.
            // 服务器有大量TCP连接时，最差的情况下将会占用serv->max_connection * buffer_output_size字节的内存
            'socket_buffer_size' => Constants::WS_SOCKET_BUFFER_SIZE, // M 必须为数字 用于设置客户端连接最大允许占用内存数量
            'buffer_output_size' => Constants::WS_BUFFER_OUTPUT_SIZE // 用于设置单次最大发送长度 M
        ];
        $server->set($setConfig);
        //添加一个监听端口，继续支持ws方式进行连接
        $server->addlistener(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT, SWOOLE_SOCK_TCP);

        $server->on('open', function ($server, $req) {
            YII_DEBUG || LiveService::openConnection($req->fd);
        });
        $server->on('message', function ($server, $frame) {
            if (!empty($frame->data)) {
                if (YII_DEBUG) {
                    LiveService::webSocketLog(
                        "{$frame->fd} message:" . $frame->data,
                        'webSocketMessage.log',
                        true
                    );
                }
                $message = json_decode($frame->data, true);
                switch ($message['messageType']) {
                    case Constants::MESSAGE_TYPE_BARRAGE_REQ://弹幕
                        LiveService::barrageRequest($server, $frame, $message);
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
                    case Constants::MESSAGE_TYPE_LM_REQ: // 连麦请求
                        LiveService::requestLM($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_LM_RES: // 连麦相应
                        LiveService::responseLM($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_LM_LIST_REQ: // 连麦请求列表
                        LiveService::requestLMList($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_LM_AGREE_REQ: // 连麦相应列表
                        LiveService::responseLMList($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_GAG_REQ: // 禁言
                        LiveService::gag($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_KICK_REQ: // 踢人
                        LiveService::kickUser($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_CLOSE_CALL_REQ: // 断开连麦
                        LiveService::closeCall($server, $frame, $message);
                        break;
                    default:
                        $server->push($frame->fd, json_encode(["message not match", $frame->fd]));
                }
            }
        });
        $server->on('close', function ($server, $fd) {
            ll("{$fd} connection close", 'webSocketMessage.log');
            LiveService::fdClose($server, $fd);
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
                if (!empty($gift)) {
                    $priceReal = $gift['price'] * $num; // 去掉分，webSocket不涉及业务，交易类结算已最小单位透传
//                    $priceReal = $gift['price'] * $num * Constants::CENT;
                    //更新余额
                    $stat = User::updateUserBalance($userId, -$priceReal);
                    if ($stat > 0) {
                        //购买礼物记录
                        Order::create($order['streamId'], $giftId, $userId, $userIdTo, $price, $num);
//                        $balance = $user['balance'] - $priceReal; // 不涉及业务计算
//                        $redis->hset('WSUserBalance', $userId, $balance); // 送礼物时已更新
                    }
                }
            }
        }
    }
}