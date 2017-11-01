<?php

namespace app\console\controllers;

use yii\console\Controller;

class WebSocketController extends Controller
{
    //webSocket服务端
    public function actionServer()
    {
        $server = new \swoole_websocket_server("127.0.0.1", 9502);
        $server->on('open', function($server, $req) {
            var_dump($server);
            echo "connection open: {$req->fd}\n";
        });
        $server->on('message', function($server, $frame) {
            echo "received message: {$frame->data}\n";
            $server->push($frame->fd, json_encode(["hello", "world",$frame->fd]));
        });
        $server->on('close', function($server, $fd) {
            echo "connection close: {$fd}\n";
        });
        $server->start();
    }
}