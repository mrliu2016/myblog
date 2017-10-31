<?php

namespace app\console\controllers;

use app\common\components\AHelper;
use yii\console\Controller;

class WeixinApiController extends Controller
{
    //创建公众号菜单
    public function actionCreateMenu()
    {
        $appId = 'wxc157967034c8f60b';
        $appSecret = 'c098668310efd73a1fa1df5d436fe299';
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appId . "&secret=" . $appSecret . "";
        $res = AHelper::curl_get($url);
        $result = json_decode($res, 1);
        $access_token = $result["access_token"];

        $createUrl = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
//        $data = '{
//                 "button":[
//                  {
//                      "type":"view",
//                      "name":"首页",
//                      "url":"http://dev.api.wechat.3ttech.cn/h5/index"
//                  },
//                  {
//                      "type":"view",
//                      "name":"任务",
//                      "url":"http://dev.api.wechat.3ttech.cn/user/info?state=--flag__2"
//                  },
//                  {
//                       "name":"我",
//                       "sub_button":[{
//                           "type":"view",
//                           "name":"个人中心",
//                           "url":"http://dev.api.wechat.3ttech.cn/user/info?state=--flag__1"
//                        },
//                        {
//                           "type":"view",
//                           "name":"问题咨询",
//                           "url":"http://dev.api.wechat.3ttech.cn/h5/f-q"
//                        }]
//                   }]
//             }';
        $data = '{
                 "button":[
                  {
                      "type":"view",
                      "name":"首页",
                      "url":"http://dev.api.wechat.3ttech.cn/h5/index"
                  },
                  {
                      "type":"view",
                      "name":"top",
                      "url":"http://dev.api.wechat.3ttech.cn/h5/price-real-top"
                  },
                  {
                       "name":"我",
                       "sub_button":[{
                           "type":"view",
                           "name":"个人中心",
                           "url":"http://dev.api.wechat.3ttech.cn/user/info?state=--flag__1"
                        },
                        {
                           "type":"view",
                           "name":"问题咨询",
                           "url":"http://dev.api.wechat.3ttech.cn/h5/f-q"
                        }]
                   }]
             }';
        $result = AHelper::curlPost($createUrl, $data);
        $result = json_decode($result, true);
        if ($result['errmsg'] == 'ok') {
            echo 'Success';
        } else {
            echo 'Failed: <br/>&nbsp;&nbsp;errcode=>' . $result['errcode'] . ';errmsg=>' . $result['errmsg'];
        }
    }
}