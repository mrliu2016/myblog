<?php

namespace app\common\components;

use Yii;

class WeiXinOAuth
{
    /**
     * @param $appId
     * @param $appSecret
     * @return array
     * @throws \Exception
     */
    public static function webOAuth($appId, $appSecret)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_GET['code'])) {
            header("location:" . static::getWebOAuthCodeUrl($appId));
            exit;
        } else {
            $weiXinConfig = Yii::$app->params['weiXin'];
            $curlRequest = CurlRequests::Instance();
            $curlRequest->setRequestMethod('GET');
            // 通过code换取网页授权access_token
            $webAccessToken = $curlRequest->request(
                $weiXinConfig['webAccessToken'],
                [
                    'appid' => $appId,
                    'secret' => $appSecret,
                    'code' => $_GET["code"],
                    'grant_type' => 'authorization_code'
                ]
            );
            $webAccessToken = json_decode($webAccessToken, true);
            // 拉取用户信息
            $weiXinUserInfo = $curlRequest->request(
                $weiXinConfig['webOAuthUserInfo'],
                [
                    'access_token' => $webAccessToken['access_token'],
                    'openid' => $webAccessToken['openid'],
                    'lang' => 'zh_CN'
                ]
            );
            $weiXinUserInfo = json_decode($weiXinUserInfo, true);
            $weiXinUserInfo['redirectUri'] = $_GET['state'];
            return array_merge($webAccessToken, $weiXinUserInfo);
        }
    }

    /**
     * 获取OAuthCodeUrl
     *
     * @param $appId
     * @param string $scope
     * @return string
     */
    public static function getWebOAuthCodeUrl($appId, $scope = 'snsapi_userinfo')
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $redirectUri = urlencode(static::getCurrentUrl());
        $url = $weiXinConfig['webOAuth'] . '?appid=' . $appId . '&redirect_uri='
            . $redirectUri . '&response_type=code&scope=' . $scope . '&state=' . $redirectUri;
        $url .= "#wechat_redirect";
        return $url;
    }

    /**
     * 获取当前URL
     * @return string
     */
    public static function getCurrentUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}