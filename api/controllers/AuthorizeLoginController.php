<?php

namespace app\api\controllers;

use app\common\components\RedisClient;
use app\common\models\User;
use app\common\services\Constants;
use Yii;

class AuthorizeLoginController extends BaseController
{
    /**
     * 授权登录
     */
    public function actionAuthorizeLogin()
    {
        $params = Yii::$app->request->post();
        $result = User::authorizeLogin($params);
        if ($result) {
            $redisClient = RedisClient::getInstance();
            $redisClient->set(
                Constants::TTT_TECH_TOKEN . ':' . $result['token'],
                json_encode(['userId' => $result['userId'], 'token' => $result['token']])
            );
            $redisClient->expire(Constants::TTT_TECH_TOKEN . ':' . $result['token'], Constants::LOGIN_TOKEN_EXPIRES);
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '登录成功!', $result);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '登录失败，请稍后重试!', []);
        }
    }

    public function actionWebOauth()
    {
        $params = Yii::$app->request->get();
        $redirect = urlencode(Yii::$app->request->absoluteUrl);
    }
}
