<?php

namespace app\common\components;

use app\common\services\Constants;

class WeiXinTemplate
{
    /**
     *
     *
     * {{first.DATA}}
     * 任务名称：{{keyword1.DATA}}
     * 通知类型：{{keyword2.DATA}}
     * {{remark.DATA}}
     *
     * @param $appId
     * @param $appSecret
     * @param array $params
     * @return mixed
     */
    public static function processTemplate($appId, $appSecret, $params = [])
    {
        $templateParams = [
            'touser' => $params['openId'],
            'template_id' => $params['template_id'],
            'url' => $params['url'],
            'topcolor' => $params['topcolor'],
            'data' => [
                'first' => [
                    'value' => urlencode($params['first'])
                ],
                'keyword1' => [
                    'value' => $params['keyword1']
                ],
                'keyword2' => [
                    'value' => $params['keyword2']
                ],
                'remark' => [
                    'value' => urlencode($params['remark'])
                ]
            ]
        ];
        $redis = RedisClient::getInstance();
        if ($redis->setnx($appId . '_access_token_setnx', Constants::ACCESS_TOKEN_EXPIRES_IN)) {
            $accessToken = WeiXinApi::getAccessToken($appId, $appSecret);
            $redis->expire($appId . '_ACCESS_TOKEN', Constants::ACCESS_TOKEN_EXPIRES_IN);
            $redis->set($appId . '_ACCESS_TOKEN', $accessToken['access_token']);
        } else {
            $accessToken['access_token'] = $redis->get($appId . '_ACCESS_TOKEN');
        }
        return WeiXinApi::sendTemplate($appId, $appSecret, $accessToken['access_token'], urldecode(json_encode($templateParams)));
    }
}