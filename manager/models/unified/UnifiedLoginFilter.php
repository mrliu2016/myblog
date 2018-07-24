<?php

namespace app\manager\models\unified;

use app\common\services\Constants;
use Yii;
use yii\base\ActionFilter;
use yii\httpclient\Client;
use yii\web\Cookie;

class UnifiedLoginFilter extends ActionFilter
{
    const LOG_FILE = 'unified_login_filter.log';

    public function beforeAction($action) {
        $request = Yii::$app->request;
//        $ticketFromCookie = 'TzoyNzoiYXBwXGNvbW1vblxtb2RlbHNcTG9naW5Vc2VyIjo5OntzOjY6InVzZXJJZCI7czoyOiI4NyI7czo4OiJ1c2VyTmFtZSI7czoxMzoiaHVhbmdtZW5ncm9uZyI7czoxMzoiYXBwbGljYXRpb25JZCI7aToxO3M6NjoiYXBwS2V5IjtzOjEzOiJnc3dzMTIzNDU2Nzg5IjtzOjk6ImFwcFNlY3JldCI7czoxMzoiZ3N3czEyMzQ1Njc4OSI7czo2OiJhdmF0YXIiO3M6MDoiIjtzOjY6ImRvbWFpbiI7YToxNzp7aTowO3M6MjQ6ImRldi51c2VyY2VudGVyLjN0dGVjaC5jbiI7aToxO3M6MzE6ImRldi51c2VyY2VudGVyLnd1c2h1YW5ndGVjaC5jb20iO2k6MjtzOjI5OiJsb2NhbC5jb21wcmVzcy4zdHRlY2guY246ODA4NiI7aTozO3M6MzA6ImxvY2FsaG9zdC5jb21tZW50LjN0dGVjaC5jbjo3NSI7aTo0O3M6MjA6ImxvY2FsLjN0dGVjaC5jbjo4MjAxIjtpOjU7czozMToibG9jYWwudXNlcmNlbnRlci4zdHRlY2guY246ODA3MSI7aTo2O3M6MTc6IjU5LjQ0LjQzLjIyOTo3ODc0IjtpOjc7czoyNDoiZGV2LnVzZXJjZW50ZXIuM3R0ZWNoLmNuIjtpOjg7czozMToiZGV2LnVzZXJjZW50ZXIud3VzaHVhbmd0ZWNoLmNvbSI7aTo5O3M6Mjk6ImxvY2FsLmNvbXByZXNzLjN0dGVjaC5jbjo4MDg2IjtpOjEwO3M6MzA6ImxvY2FsaG9zdC5jb21tZW50LjN0dGVjaC5jbjo3NSI7aToxMTtzOjIwOiJsb2NhbC4zdHRlY2guY246ODIwMSI7aToxMjtzOjMxOiJsb2NhbC51c2VyY2VudGVyLjN0dGVjaC5jbjo4MDcxIjtpOjEzO3M6MTc6IjU5LjQ0LjQzLjIyOTo3ODc0IjtpOjE0O3M6MTg6ImRldi5saXZlLjN0dGVjaC5jbiI7aToxNTtzOjMxOiJsb2NhbGhvc3Qud3VzaHVhbmcuM3R0ZWNoLmNuOjkyIjtpOjE2O3M6MzE6ImxvY2FsLnVzZXJjZW50ZXIuM3R0ZWNoLmNuOjgwODUiO31zOjQ6InJvbGUiO2k6MDtzOjExOiJlbnZpcm9ubWVudCI7czozOiJkZXYiO30';
//        $ticketFromCookie = $request->getCookies()->getValue(Constants::COOKIE_UNIFIED_LOGIN);
        $session = \Yii::$app->session;
        $ticketFromCookie = $session->get(Constants::COOKIE_UNIFIED_LOGIN.$_SERVER['HTTP_HOST']);
        $loginUrl = Yii::$app->params["liveDomain"]."/index/login?redirect=". urlencode($request->absoluteUrl);

        if ($ticketFromCookie == null) {
            Yii::$app->getResponse()->redirect($loginUrl);
            return false;
        }
//        Yii::$app->controller->user = unserialize(base64_decode($ticketFromCookie));
//        //登录区分环境
//        $env = (YII_ENV_PRE || YII_ENV_ONLINE) ? 'online' : 'dev';
////        echo Yii::$app->controller->user->environment;die;
//        if (Yii::$app->controller->user->environment <> $env) {
//            setcookie(Constants::COOKIE_UNIFIED_LOGIN, null, time() - 1000, "/", Constants::COOKIE_DOMAIN);
//            Yii::$app->getResponse()->redirect($loginUrl);
//        }
//        //验证登录服务域名
//        if (is_array(Yii::$app->controller->user->domain) && !in_array($_SERVER['HTTP_HOST'], Yii::$app->controller->user->domain)) {
//            Yii::$app->getResponse()->redirect($loginUrl);
//        }

        return parent::beforeAction($action);
    }
}