<?php

namespace app\manager\controllers;

use app\common\models\LoginUser;
use app\common\models\ManagerUser;
use app\common\services\Constants;
use Yii;
use yii\web\Cookie;

class IndexController extends BaseController
{
    //首页
    public function actionLogin()
    {
        setcookie(Constants::COOKIE_UNIFIED_LOGIN, null, time() - 1000, "/", $_SERVER['HTTP_HOST']);

        $redirect = Yii::$app->request->get("redirect");
        return $this->render('login', [
            'redirect' => $redirect
        ]);
    }

    //登录验证
    public function actionCheck()
    {
        $params = Yii::$app->request->post();
        $user = ManagerUser::findOne($params);//通过用户输入的用户名重表中选出数据
        if (!empty($user)) {
//            $session = \Yii::$app->session;
//            $session->set(Constants::COOKIE_UNIFIED_LOGIN . $_SERVER['HTTP_HOST'], base64_encode(serialize($params['username'])));
            $loginUser = new LoginUser();
            $loginUser->userId = $user['id'];
            $loginUser->userName = $user['username'];
            $loginUser->avatar = '';
            $loginUser->role = '';
            $loginUser->environment = (YII_ENV_PRE || YII_ENV_ONLINE) ? 'online' : 'dev';
            $loginUser->nickName = $user['nickName'];

            $cookie = new Cookie([
                'name' => Constants::COOKIE_UNIFIED_LOGIN,
                'value' => base64_encode(serialize($loginUser)),
                'expire' => time() + 86400,//一天后过期
                'domain' => $_SERVER['HTTP_HOST']
            ]);
            Yii::$app->response->cookies->add($cookie);

            $redirect = $params['redirect'];
            if (empty($redirect)) {
                $redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/user/index';
            }
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, ['redirect' => $redirect], '');
        } else {
            $this->jsonReturnError(-1, "用户名或者密码错误");
        }
    }

    //注销
    public function actionLogout()
    {
        $loginUrl = "/index/login";
//        $session = \Yii::$app->session;
//        $session->remove(Constants::COOKIE_UNIFIED_LOGIN . $_SERVER['HTTP_HOST']);

        if (strpos($_SERVER['HTTP_HOST'], '3ttech')) {
            $domain = '.3ttech.cn';
        } else {
            $domain = '.holyvision.com.cn';
        }
        setcookie(Constants::COOKIE_UNIFIED_LOGIN, null, time() - 1000, "/", $domain);
        Yii::$app->getResponse()->redirect($loginUrl);
    }

}