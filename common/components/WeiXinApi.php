<?php

namespace app\common\components;

use Yii;

class WeiXinApi
{
    /**
     * 获取微信access_token
     *
     * @param $appId
     * @param $appSecret
     * @param string $grantType
     * @return mixed
     */
    public static function getAccessToken($appId, $appSecret, $grantType = 'client_credential')
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $curlInstance = CurlRequests::Instance();
        $curlInstance->setRequestMethod('get');
        $params = [
            'grant_type' => $grantType,
            'appid' => $appId,
            'secret' => $appSecret
        ];
        $response = $curlInstance->request($weiXinConfig['accessToken'], $params);
        return json_decode($response, true);
    }

    /**
     * 发送微信模板消息
     *
     * @param $appId
     * @param $appSecret
     * @param $accessToken
     * @param $params
     * @return mixed
     */
    public static function sendTemplate($appId, $appSecret, $accessToken, $params)
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $curlInstance = CurlRequests::Instance();
        $curlInstance->setRequestMethod('post');
        return $curlInstance->request($weiXinConfig['template'] . $accessToken, $params);
    }
}