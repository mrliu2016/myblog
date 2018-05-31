<?php

namespace app\console\controllers;

use yii\console\Controller;

class WebSocketTestController extends Controller
{
    public function actionWebSocketTest()
    {
        $server = new \swoole_websocket_server("0.0.0.0", 9556);

        $options = [
            'max_connection' => 150000, // 最大链接数
            'worker_num' => 6, // worker 数
            // swFactoryProcess_finish: send failed, session#1 output buffer has been overflowed.
            // 服务器有大量TCP连接时，最差的情况下将会占用serv->max_connection * buffer_output_size字节的内存
            'socket_buffer_size' => 512 * 1024 * 1024, // M 必须为数字 用于设置客户端连接最大允许占用内存数量
            // 心跳检测
//    'heartbeat_idle_time' => 30,
//    'heartbeat_check_interval' => 10,
//    'buffer_output_size' => 2 // 用于设置单次最大发送长度 M
        ];

        $server->set($options);

        $server->on('open', function ($server, $req) {
            error_log(date('Y-m-d H:i:s') . 'open connection count : ' . count($server->connections) . "\n", 3, './result.log');
        });

        $server->on('message', function ($server, $frame) {
            $count = count($server->connections);
            error_log(date('Y-m-d H:i:s') . "当前服务器共有 " . $count . " 个连接\n", 3, './result.log');
            $message = json_decode($frame->data, true);
            if (isset($message['messageType'])) {
                switch ($message['messageType']) {
                    case 'ping':
                        error_log(date('Y-m-d H:i:s') . "当前服务器共有 " . $count . " 个连接\n", 3, './result.log');
                        $testFd = file_get_contents('./fd.log');
                        for ($index = 1; $index <= $count; $index++) {
                            $server->push(
                                $testFd,
                                json_encode(
                                    [
                                        'messageType' => 'pong',
                                        'connection_num' => $count,
                                        'index' => $index,
                                        'time' => microtime(true),
                                        'data' => '{"messageType": "join_req", "data": {"roomId": "124","userId": numMax,"nickName": "nickName","avatar": "avatar","message": "message","role":0,"level":5,"masterUserId":111,"masterNickNam    e":"masterNickName","masterAvatar":"masterAvatar","masterLevel":9}}'
                                    ]
                                )
                            );
                        }
                        break;
                    case 'opon':
                        file_put_contents('./fd.log', $frame->fd);
                        break;
                    default:
                        break;

                }
            }
            if (file_exists('./fd.log')) {
                $fd = file_get_contents('./fd.log');
                $server->push(
                    intval($fd),
                    json_encode(
                        [
                            'messageType' => '并发：' . $count . ' 客户端向指定单一客户端 ' . $fd . ' 下发消息',
                            'connection_num' => $count,
                            'fd' => $frame->fd,
                            'time' => microtime(true),
                            'data' => '{"messageType": "join_req", "data": {"roomId": "124","userId": numMax,"nickName": "nickName","avatar": "avatar","message": "message","role":0,"level":5,"masterUserId":111,"masterNickNam    e":"masterNickName","masterAvatar":"masterAvatar","masterLevel":9}}'
                        ]
                    )
                );
            }
        });

        $server->on('close', function ($server, $fd) {
            if (file_exists('./fd.log')) {
                $testFd = file_get_contents('./fd.log');
                $server->push(
                    intval($testFd),
                    json_encode(
                        [
                            'messageType' => 'pong',
                            'connection_num' => count($server->connections),
                            'fdClose' => $fd,
                            'time' => microtime(true),
                            'data' => '{"messageType": "join_req", "data": {"roomId": "124","userId": numMax,"nickName": "nickName","avatar": "avatar","message": "message","role":0,"level":5,"masterUserId":111,"masterNickNam    e":"masterNickName","masterAvatar":"masterAvatar","masterLevel":9}}'
                        ]
                    )
                );
                if ($fd == $testFd) {
                    unlink('./fd.log');
                }
            }
            error_log(
                date('Y-m-d H:i:s') . "当前服务器 close 客户端，现共有 " . count($server->connections) . " 个连接\n",
                3,
                './result.log'
            );
        });

        $server->start();
    }
}