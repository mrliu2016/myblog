<?php

namespace app\manager\models\unified;

use Yii;
use yii\base\ActionFilter;
use yii\httpclient\Client;
use yii\web\Cookie;

class UnifiedLoginFilter extends ActionFilter
{
    const LOG_FILE = 'unified_login_filter.log';

    public function beforeAction($action) {
        $request = Yii::$app->request;
        $ticketFromCookie = $request->getCookies()->getValue('unifiedLoginTicket');
        $loginUrl = Yii::$app->params["ucDomain"]."/user/login?redirect=". urlencode($request->absoluteUrl);
        if ($ticketFromCookie == null) {
            Yii::$app->getResponse()->redirect($loginUrl);
            return false;
        }
        Yii::$app->controller->user = unserialize(base64_decode($ticketFromCookie));
        return parent::beforeAction($action);
    }
}
