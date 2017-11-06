<?php

namespace app\common\services;

use app\common\models\Video;

class VideoService
{
    public static function updateVideoInfo($params)
    {
        if (!isset($params['roomId']) || !isset($params['videoSrc']) || !isset($params['imgSrc'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $roomId = $params['roomId'];
        $videoSrc = $params['videoSrc'];
        $imgSrc = $params['imgSrc'];
        Video::updateVideoInfo($roomId, $videoSrc, $imgSrc);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => []];
    }
}