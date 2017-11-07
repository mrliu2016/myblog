<?php

namespace app\console\controllers;

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
}