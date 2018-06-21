<?php

namespace app\common\services;

use app\common\models\Video;

class VideoService
{
    const BASE = 10;

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

    public static function computeUnit($number)
    {
        for ($index = 1; $index <= self::BASE; $index++) {
            $base = pow(self::BASE, $index);
            if ($number >= pow(self::BASE, 4) && ($number < pow(self::BASE, 8))) {
                switch (strlen($base)) {
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        return sprintf('%.1f', $number / $base) . 'w+';
                        break;
                }
            } elseif ($number >= pow(self::BASE, 8)) {
                switch (strlen($base)) {
                    case 9:
                    case 10:
                    case 11:
                        return sprintf('%.1f', $number / $base) . '亿+';
                        break;
                }
            } else {
                return strval($number);
            }
        }
    }

    public static function queryByStreamId($id)
    {
        return Video::queryById($id);
    }
}