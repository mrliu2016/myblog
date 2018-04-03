<?php

namespace app\common\components;

use app\common\services\Constants;

class CdnUtils
{
    //直播管理 分配id
    const BIZID = 19062;
    //推流防盗链key
    const KEY = 'aff4243d7e72bedc4ae0212fc0ef56bb';

    /**
     * 获取推流地址
     * 如果不传key和过期时间，将返回不含防盗链的url
     * @param streamId 您用来区别不同推流地址的唯一id
     *        time 过期时间 sample 2016-11-12 12:00:00
     * @return String url
     */
    public static function qCloudPushUrl($streamId)
    {
        $bizId = self::BIZID;
        $key = self::KEY;
        $txTime = strtoupper(base_convert(time() + 86400, 10, 16));
        $livecode = $bizId . "_" . $streamId; //直播码
        $txSecret = md5($key . $livecode . $txTime);
        $ext_str = "?" . http_build_query(array(
                "bizid" => $bizId,
                "txSecret" => $txSecret,
                "txTime" => $txTime
            ));
        return "rtmp://" . $bizId . ".livepush.myqcloud.com/live/" . $livecode . (isset($ext_str) ? $ext_str : "");
    }

    /**
     * 获取播放地址
     * @param bizId 您在腾讯云分配到的bizid
     *        streamId 您用来区别不同推流地址的唯一id
     * @return String url
     */
    public static function qCloudPlayUrl($streamId)
    {
        $bizId = self::BIZID;
        $livecode = $bizId . "_" . $streamId; //直播码
        return "rtmp://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode;

//        return array(
//            "rtmp://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode,
//            "http://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode . ".flv",
//            "http://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode . ".m3u8"
//        );
    }

    /**
     * 腾讯云Wap直播流地址
     *
     * @param $roomId
     * @return string
     */
    public static function qCloudWapPullStream($roomId)
    {
        $bizId = self::BIZID;
        $livecode = $bizId . "_" . $roomId; //直播码
        return 'http://' . $bizId . 'liveplay.myqcloud.com/live/' . $livecode . '.m3u8';
    }

    /**
     * Web 拉流鉴权地址
     *
     * @param $roomId
     * @return string
     */
    public static function aliWapPullStream($roomId)
    {
        $pullUrl = 'http://ali.push.cdn.3ttechlive.3ttech.cn/3ttechlive/' . $roomId . '.m3u8?';
        $timeSp = time();
        $signStr = "/3ttechlive/" . $roomId . ".m3u8-" . $timeSp . "-0-0-" . Constants::AUTHORITY_KEY;
        return $pullUrl . "auth_key=" . $timeSp . "-0-0-" . md5($signStr);
    }

    /**
     * App 阿里云推流鉴权地址
     *
     * @param $roomId
     * @return string
     */
    public static function aliAppPushRtmpStream($roomId)
    {
        $timeSp = time();
        $hashAuthority = '/3ttechlive/' . $roomId . '-' . $timeSp . '-0-0-' . Constants::AUTHORITY_KEY;
        $pushUrl = "rtmp://video-center-bj.alivecdn.com/3ttechlive/" . $roomId . "?vhost=ali.push.cdn.3ttechlive.3ttech.cn";
        $pushUrl .= '&auth_key=' . $timeSp . '-0-0-' . md5($hashAuthority);
        return $pushUrl;
    }

    /**
     * App 阿里云拉流鉴权地址
     *
     * @param $roomId
     * @return string
     */
    public static function aliAppPullRtmpStream($roomId)
    {
        $timeSp = time();
        $hashAuthority = '/3ttechlive/' . $roomId . '-' . $timeSp . '-0-0-' . Constants::AUTHORITY_KEY;
        $pullUrl = "rtmp://ali.push.cdn.3ttechlive.3ttech.cn/3ttechlive/" . $roomId . "?";
        $pullUrl .= 'auth_key=' . $timeSp . '-0-0-' . md5($hashAuthority);
        return $pullUrl;
    }

    /**
     * 青云推流地址
     *
     * @param $streamId
     * @return string
     */
    public static function qingCloudPushRtmpStream($streamId)
    {
        return 'rtmp://publish-wenlian.qingcache.com/livestream/' . $streamId;
    }

    /**
     * 青云拉流地址
     *
     * @param $streamId
     * @return string
     */
    public static function qingCloudPullRtmpStream($streamId)
    {
        return 'rtmp://play-wenlian.qingcache.com/livestream/' . $streamId;
    }

    /**
     * 移动端拉流地址
     *
     * @param $streamId
     * @return string
     */
    public static function qingCloudHlsStream($streamId)
    {
        return 'http://hls-wenlian.qingcache.com/livestream/' . $streamId . '.m3u8';
    }

    public static function qingCloudHdlStream($streamId)
    {
        return 'http://hdl-wenlian.qingcache.com/livestream/' . $streamId . '.flv';
    }

    //星域cdn推流
    public static function xyCDNPushUrl($streamId)
    {
        return "rtmp://push.3ttech.cn/sdk/" . $streamId;
    }

    //星域cdn拉流Rtmp
    public static function xyCDNPullUrl($streamId)
    {
        return "rtmp://pull.3ttech.cn/sdk/" . $streamId;
    }

    //星域cdn拉流m3u8
    public static function xyCDNWapPullUrl($streamId)
    {
        return "rtmp://pull2.3ttech.cn/sdk/" . $streamId . ".m3u8";
    }


    //获取拉流地址
    public static function getPullUrl($streamId, $rtmp = true)
    {
        $pullUrl = '';
        $cdnFactory = \Yii::$app->params['cdnFactory'];
        switch ($cdnFactory) {
            case Constants::CDN_FACTORY_QCLOUD:
                if ($rtmp) {
                    $pullUrl = self::qCloudPlayUrl($streamId);
                } else {
                    $pullUrl = self::qCloudWapPullStream($streamId);
                }
                break;
            case Constants::CDN_FACTORY_ALIYUN:
                if ($rtmp) {
                    $pullUrl = self::aliAppPullRtmpStream($streamId);
                } else {
                    $pullUrl = self::aliWapPullStream($streamId);
                }
                break;
            case Constants::CDN_FACTORY_XYCDN:
                if ($rtmp) {
                    $pullUrl = self::xyCDNPullUrl($streamId);
                } else {
                    $pullUrl = self::xyCDNWapPullUrl($streamId);
                }
                break;
            case Constants::CDN_FACTORY_QING_CLOUD:
                if ($rtmp) {
                    $pullUrl = self::qingCloudPullRtmpStream($streamId);
                } else {
                    $pullUrl = self::qingCloudHlsStream($streamId);
                }
                break;
            default:
        }
        return $pullUrl;
    }

    //获取推流地址
    public static function getPushUrl($streamId)
    {
        $pushUrl = '';
        $cdnFactory = \Yii::$app->params['cdnFactory'];
        switch ($cdnFactory) {
            case Constants::CDN_FACTORY_QCLOUD:
                $pushUrl = self::qCloudPushUrl($streamId);
                break;
            case Constants::CDN_FACTORY_ALIYUN:
                $pushUrl = self::aliAppPushRtmpStream($streamId);
                break;
            case Constants::CDN_FACTORY_XYCDN:
                $pushUrl = self::xyCDNPushUrl($streamId);
                break;
            case Constants::CDN_FACTORY_QING_CLOUD:
                $pushUrl = self::qingCloudPushRtmpStream($streamId);
                break;
            default:
        }
        return $pushUrl;
    }
}