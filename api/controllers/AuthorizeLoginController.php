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
            RedisClient::getInstance()->set(
                $result['token'],
                json_encode(['userid' => $result['userId'], 'token' => $result['token']])
            );
            RedisClient::getInstance()->expire($result['token'], Constants::LOGIN_TOKEN_EXPIRES);
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '登录成功!', $result);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '登录失败!', []);
        }
    }
}
