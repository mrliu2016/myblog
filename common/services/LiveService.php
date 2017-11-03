<?php

namespace app\common\services;

use Yii;

class LiveService
{
    //弹幕（关键字过滤、数据不保存）
    public static function barrageRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $respondMessage['messageType'] = Constants::MESSAGE_TYPE_BARRAGE_RES;
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = 'hello world!';
        $data = array(
            'roomId' => 123,
            'userId' => 321,
            'nickName' => "nickName",
            'avatar' => "http://avatar.jpg",
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
        $respondMessage['code'] = Constants::CODE_SUCCESS;
        $respondMessage['message'] = '';
        $data = array(
            'cdn' => array(
                'hls' => 'zbj-pull2.3ttech.cn',
                'pull' => 'zbj-pull.3ttech.cn',
                'push' => 'zbj-push.3ttech.cn',
            ),
            'roomServer' => array(
                'ip' => '47.94.92.113',
                'port' => 9502
            )
        );
        $respondMessage['data'] = $data;
        $server->push($frame->fd, json_encode($respondMessage));
    }

    //送礼物
    public static function giftRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        if (empty($message["roomId"]) || empty($message["userId"]) || empty($message["userIdTo"]) || empty($message["giftId"])
            || empty($message["price"]) || empty($message["num"])
        ) {
            $respondMessage['messageType'] = Constants::MESSAGE_TYPE_GIFT_RES;
            $respondMessage['code'] = Constants::CODE_FAILED;
            $respondMessage['message'] = 'parameter error';
            $respondMessage['data'] = array();
            $server->push($frame->fd, json_encode($respondMessage));
            return;
        }
        $roomId = $message["roomId"];
        $userId = $message["userId"];
        $userIdTo = $message["userIdTo"];
        $giftId = $message["giftId"];
        $price = $message["price"];
        $num = $message["num"];
        $balance = 100;
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
            'balance' => $balance,
        );
        $respondMessage['data'] = $data;
        $server->push($frame->fd, json_encode($respondMessage));
    }

    //心跳
    public static function heartbeatRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
    }
}