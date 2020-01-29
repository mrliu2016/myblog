<?php

namespace app\api\models\unified;

use app\common\services\Constants;
use Yii;
use yii\base\ActionFilter;
use yii\httpclient\Client;
use yii\web\Cookie;

class UnifiedLoginFilter extends ActionFilter
{
    const LOG_FILE = 'unified_login_filter.log';

    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        $ticketFromCookie = $request->getCookies()->getValue('unifiedLoginTicket');
        $loginUrl = Yii::$app->params["domain"] . "/authorize-login/web-oauth?redirect=" . urlencode($request->absoluteUrl);
        if ($ticketFromCookie == null) {
            Yii::$app->getResponse()->redirect($loginUrl);
            return false;
        }
        Yii::$app->controller->user = unserialize(base64_decode($ticketFromCookie));
        // 登录区分环境
        $env = (YII_ENV_PRE || YII_ENV_ONLINE) ? 'online' : 'dev';
        if (Yii::$app->controller->user->environment <> $env) {
            setcookie(Constants::COOKIE_UNIFIED_LOGIN, null, time() - 1000, "/", Constants::COOKIE_DOMAIN);
            Yii::$app->getResponse()->redirect($loginUrl);
        }
        // 验证登录服务域名
        if (is_array(Yii::$app->controller->user->domain) && !in_array($_SERVER['HTTP_HOST'], Yii::$app->controller->user->domain)) {
            Yii::$app->getResponse()->redirect($loginUrl);
        }
        return parent::beforeAction($action);
    }
}