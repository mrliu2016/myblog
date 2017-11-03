<?php

namespace app\common\services;

use app\common\models\User;
use app\common\models\Video;
use Yii;

class LiveService
{
    //弹幕（关键字过滤、数据不保存）
    public static function barrageRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $param = $message['data'];
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
        $param = $message['data'];
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
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || empty($param["userIdTo"]) || empty($param["giftId"])
            || empty($param["price"]) || empty($param["num"])
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
        $price = $param["price"];
        $num = $param["num"];
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
        $param = $message['data'];
        if (empty($param["roomId"]) || empty($param["userId"]) || !isset($param["isMaster"])
        ) {
            return;
        }
        $roomId = $param["roomId"];
        $userId = $param["userId"];
        $isMaster = $param["isMaster"]; //1主播 0粉丝
        if ($isMaster == 1) {
            $video = Video::findLastRecord($userId, $roomId);
            if(empty($video)){
                //直播开始
                Video::create($userId,$roomId);
            }else{
                //更新直播结束时间
                Video::updateEndTime($video);
            }
            //更新用户直播时间
            User::updateLiveTime($userId);
        }
    }
}