<?php

namespace app\api\controllers;

use app\common\components\ParseUtils;
use app\common\models\Application;
use app\common\models\User;
use app\common\services\Constants;

class UserController extends BaseController
{
    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    public function actionInfo()
    {
        header("Content-Type: text/html;charset=utf-8");
        //成功回去code后会回调该页面,注意该域名或ip需要在 接口“网页授权获取用户基本信息”中配置
        if (!empty($_GET['redirectUrl'])) {
            $redirectUrl = $_GET['redirectUrl'];
        } else {
            $redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . "/user/info";
        }
        $self = Application::queryById(Constants::LOCAL_APPLICATION_ID);
        $weChatAppId = empty($self['wxAppId']) ? '' : $self['wxAppId'];
        $weChatAppSecret = empty($self['wxAppSecret']) ? '' : $self['wxAppSecret'];

        //通过code获得openid
        if (!isset($_GET['code'])) {

            //==设置回调页面参数begin
            //公众号id
            $applicationId = empty($_GET['aid']) ? '' : $_GET['aid'];
            //任务id
            $taskId = empty($_GET['tid']) ? '' : $_GET['tid'];
            //回调页面参数
            $state = empty($_GET['state']) ? '' : $_GET['state'];
            if (!empty($_GET['aid'])) {
                $state .= '--aid__' . $applicationId;
            }
            if (!empty($_GET['aid'])) {
                $state .= '--tid__' . $taskId;
            }
            //==设置回调页面参数end

            //snsapi_base 		无需弹出确认框，但是需要关注后才能获取用户信息
            //snsapi_userinfo 弹出确认后，不需要关注公众号即可获取用户信息
            $scope = 'snsapi_userinfo';
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $weChatAppId . "&redirect_uri=" . $redirectUrl . "&response_type=code&scope=" . $scope . "&state=" . $state . "#wechat_redirect";
            header("location:" . $url);
        } else {
            //header("Content-type: application/json; charset=utf-8");
            //回调页面
            //调用code授权后会回调该页面并在url带有code
            $code = $_GET["code"];
            //通过code\appid\secret 获取openid
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $weChatAppId . '&secret=' . $weChatAppSecret . '&code=' . $code . "&grant_type=authorization_code";
            $json = file_get_contents($url);
            $arr = json_decode($json, true);
            if (empty($arr['access_token'])) {
                $url = $redirectUrl . '?state=' . $_GET['state'];
                header("location:" . $url);
                exit;
            }
            $accessToken = $arr['access_token'];
            $openId = $arr["openid"];
            $unionId = $arr["unionid"];

            //当$scope='snsapi_userinfo'时用该接口获取用户信息 需要弹出确认（可以未关注）
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openId . '&lang=zh_CN';
            $json = file_get_contents($url);
            $arr = json_decode($json, true);

            $nickName = addslashes($arr['nickname']);
            $imgUrl = $arr['headimgurl'];
            User::addUser($unionId, $openId, $nickName, $imgUrl);
            $queryArray = ParseUtils::parseStatus($_GET['state']);
            //根据flag 判断跳转地址
            $url = ParseUtils::parseUrl('http://' . $_SERVER['HTTP_HOST'], $queryArray);
            header("location:" . $url . '&unionid=' . $unionId);
        }
        exit;
    }
}