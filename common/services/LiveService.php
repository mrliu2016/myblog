<?php

namespace app\common\services;

class LiveService
{
    //弹幕（关键字过滤、数据不保存）
    public static function barrageRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_BARRAGE_RES;
        $data = array(
            'roomId' => 123,
            'userId' => 321,
            'nickName' => "nickName",
            'avatar' => "http://avatar.jpg",
            'message' => 'hello world!',
        );
        $respondMessage['data'] = $data;
        $server->push($frame->fd, json_encode($respondMessage));
    }

    //服务器信息
    public static function serverInfoRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $respondMessage = array();
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_SERVER_INFO_RES;
        $respondMessage['data']['cdn'] = array('hls' => 'zbj-pull2.3ttech.cn', 'pull' => 'zbj-pull.3ttech.cn', 'push' => 'zbj-push.3ttech.cn');
        $respondMessage['data']['roomServer'] = array('ip' => '47.94.92.113', 'port' => 9502);
        $server->push($frame->fd, json_encode($respondMessage));
    }
}