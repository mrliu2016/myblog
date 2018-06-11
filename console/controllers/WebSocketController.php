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
    private $server = null;

    //webSocket服务端
    public function actionServer()
    {
        $this->server = new \swoole_websocket_server(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT_SSL, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        $setConfig = [
            'ssl_key_file' => '/etc/nginx/cert/dev_api_demo.key',
            'ssl_cert_file' => '/etc/nginx/cert/dev_api_demo.pem',
            'heartbeat_check_interval' => Constants::WS_HEARTBEAT_CHECK_INTERVAL,
            'heartbeat_idle_time' => Constants::WS_HEARTBEAT_IDLE_TIME,
            'max_connection' => Constants::WS_WEB_SOCKET_MAX_CONNECTION, // 最大链接数
            'worker_num' => Constants::WS_WORKER_NUM, // worker 数
            'socket_buffer_size' => intval(Constants::WS_SOCKET_BUFFER_SIZE), // M 必须为数字 用于设置客户端连接最大允许占用内存数量
//            'buffer_output_size' => intval(Constants::WS_BUFFER_OUTPUT_SIZE )// 用于设置单次最大发送长度 M
        ];
        $this->server->set($setConfig);
        //添加一个监听端口，继续支持ws方式进行连接
        $this->server->addlistener(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT, SWOOLE_SOCK_TCP);

        $this->server->on('open', function ($server, $req) {
            if (YII_DEBUG) {
                LiveService::openConnection($req->fd);
            }
        });
        $this->server->on('message', function ($server, $frame) {
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
                    case Constants::MESSAGE_TYPE_QUIT_REQ: // 离开房间
                        LiveService::quitRoom($server, $frame, $message);
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
                    case Constants::MESSAGE_TYPE_LM_AGREE_OR_REFUSE_REQ: // 连麦同意、拒绝请求
                        LiveService::responseLMList($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_GAG_REQ: // 禁言
                        LiveService::gag($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_KICK_REQ: // 踢人
                        LiveService::kickUser($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_CLOSE_CALL_REQ: // 主播断开连麦
                        LiveService::closeCall($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_CLOSE_CALL_SECONDARY_REQ: // 副播断开连麦
                        LiveService::secondaryCloseCall($server, $frame, $message);
                        break;
                    case Constants::MESSAGE_TYPE_BLACKLIST_REQ: // 黑名单
                        LiveService::blacklist($server, $frame, $message);
                        break;
                    default:
                        $this->server->push($frame->fd, json_encode(["message not match", $frame->fd]));
                }
            }
        });

        $this->server->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            $message = strtolower($request->server['request_method']) == 'get' ? $request->get : $request->post;
            ll(json_encode($message), 'webSocketMessage.log');
            switch ($message['messageType']) {
                case Constants::MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_REQ: // 禁播24小时
                    LiveService::prohibitLiveOneDay($this->server, $request, $response, $message);
                    break;
                case Constants::MESSAGE_TYPE_PROHIBIT_LIVE_30_DAYS_REQ: // 禁播30天
                    LiveService::prohibitLive30Days($this->server, $request, $response, $message);
                    break;
                case Constants::MESSAGE_TYPE_PERPETUAL_PROHIBIT_LIVE_REQ: // 永久禁播
                    LiveService::perpetualProhibitLive($this->server, $request, $response, $message);
                    break;
                case Constants::MESSAGE_TYPE_PROHIBIT_ACCOUNT_NUMBER_REQ: // 禁封账号
                    LiveService::prohibitAccountNumber($this->server, $request, $response, $message);
                    break;
                default:
                    break;
            }
        });
        $this->server->on('close', function ($server, $fd) {
            ll("{$fd} connection close", 'webSocketMessage.log');
            LiveService::fdClose($server, $fd);
        });
        $this->server->start();
    }
}