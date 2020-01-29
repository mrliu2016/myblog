<?php

namespace app\common\components;

use app\common\models\Application;
use app\common\services\Base64JsonConvert;
use app\common\services\Constants;

class Share
{
    /**
     * 组装分享
     *
     * @param $appId
     * @param $title
     * @param $linkUrl
     * @param $imgUrl
     * @param $content
     * @param bool $isShare
     * @return array
     */
    public static function share($appId, $title, $linkUrl, $imgUrl, $content, $isShare = true)
    {
        $application = Base64JsonConvert::base64ToJsonDecode(RedisClient::getInstance()->get(md5($appId)));
        if (empty($application) && $isShare) {
            $application = Application::queryById($appId);
            RedisClient::getInstance()->set(md5($appId), Base64JsonConvert::jsonToBase64Encode($application));
            RedisClient::getInstance()->expire(md5($appId), Constants::ACCESS_TOKEN_EXPIRES_IN);
        }
        return [
            'share' => WeiXinShare::share($application['wxAppId'], $application['wxAppSecret'], $isShare),
            'title' => empty($title) ? $application['name'] : $title,
            'link' => $linkUrl,
            'imgUrl' => empty($imgUrl) ? $application['qrCode'] : $imgUrl,
            'content' => $content
        ];
    }
}