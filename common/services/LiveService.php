<?php

namespace app\common\services;

class LiveService
{
    public static function barrageRequest($server, $frame, $message)
    {
        echo 'receive message:' . json_encode($message);
        $server->push($frame->fd, json_encode(["hello", "world", $frame->fd]));
    }
}