<?php

namespace app\console\controllers;

use app\common\components\RedisClient;
use app\common\services\Constants;
use app\common\services\LiveService;
use yii\console\Controller;
use Yii;

class WebSocketController extends Controller
{
    private $server = null;

    //webSocket服务端
    public function actionServer()
    {
        $this->server = new \swoole_websocket_server(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT_SSL, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        $setConfig = [
            'ssl_key_file' => Yii::$app->params['webSocketSSL']['key'],
            'ssl_cert_file' => Yii::$app->params['webSocketSSL']['pem'],
            'heartbeat_check_interval' => Constants::WS_HEARTBEAT_CHECK_INTERVAL,
            'heartbeat_idle_time' => Constants::WS_HEARTBEAT_IDLE_TIME,
            'max_connection' => Constants::WS_WEB_SOCKET_MAX_CONNECTION, // 最大链接数
            'worker_num' => Constants::WS_WORKER_NUM, // worker 数
            'task_worker_num' => Constants::WS_TASK_WORKER_NUM,
            'socket_buffer_size' => intval(Constants::WS_SOCKET_BUFFER_SIZE), // M 必须为数字 用于设置客户端连接最大允许占用内存数量
//            'buffer_output_size' => intval(Constants::WS_BUFFER_OUTPUT_SIZE)// 用于设置单次最大发送长度 M
        ];
        $this->server->set($setConfig);
        //添加一个监听端口，继续支持ws方式进行连接
        $this->server->addlistener(Constants::WEB_SOCKET_IP, Constants::WEB_SOCKET_PORT_WS, SWOOLE_SOCK_TCP);
        //必须在onWorkerStart回调中创建redis/mysql连接
        $this->server->on('workerstart', function ($server, $id) {
            $redis = new RedisClient('default');
            $server->redis = $redis;
        });
        $this->server->on('open', function ($server, $req) {
            LiveService::openConnection($server, $req->fd);
        });
        $this->server->on('message', function ($server, $frame) {
            if (!empty($frame->data)) {
                LiveService::webSocketLog("{$frame->fd} message:" . $frame->data, 'webSocketMessage.log', true);
                $message = json_decode($frame->data, true);
                if (isset($message['messageType'])) {
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
                        case Constants::MESSAGE_TYPE_AUDIO_VIDEO_CALL_USER_LIST_REQ: // 音视频连麦用户列表
                            LiveService::audioVideoCallUserList($server, $frame, $message);
                            break;
                        case Constants::MESSAGE_TYPE_MUTE_REQ:
                            LiveService::mute($server, $frame, $message);
                            break;
                        default:
                            $this->server->push($frame->fd, json_encode(["message not match", $frame->fd]));
                    }
                }
            }
        });

        $this->server->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            $message = strtolower($request->server['request_method']) == 'get' ? $request->get : $request->post;
            LiveService::webSocketLog($message, 'webSocketMessage.log', true);
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

        // 处理异步任务
        $this->server->on('task', function ($server, $task_id, $from_id, $message) {
            if (isset($message['messageType'])) {
                switch ($message['messageType']) {
                    case Constants::MESSAGE_TYPE_JOIN_RES:
                    case Constants::MESSAGE_TYPE_LM_LIST_RES:
                    case Constants::MESSAGE_TYPE_AUDIO_VIDEO_CALL_USER_LIST_RES:
                        LiveService::asyncBroadcastToCurrentFD($server, $task_id, $from_id, $message);
                        break;
                    default:
                        LiveService::asyncBroadcast($server, $task_id, $from_id, $message);
                        break;
                }
            }
        });

        // 处理异步任务的结果
        $this->server->on('finish', function ($server, $task_id, $message) {
            echo "AsyncTask[$task_id] Finish: $message" . PHP_EOL;
        });

        $this->server->on('close', function ($server, $fd) {
            LiveService::webSocketLog("{$fd} connection close", 'webSocketMessage.log', true);
            LiveService::fdClose($server, $fd);
        });
        $this->server->start();
    }
}