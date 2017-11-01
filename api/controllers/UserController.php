<?php
/**
 * Created by PhpStorm.
 * User: 左撇子
 * Date: 2017/11/1/001
 * Time: 14:12
 */

namespace app\api\controllers;

use app\common\components\WeiXinApi;
use app\common\components\Token;
use app\common\components\RedisClient;
use app\common\models\User;
use app\common\services\Constants;

class UserController extends BaseController
{

    public function actionLogin()
    {
        $mobile = \Yii::$app->request->post('mobile');
        $password = \Yii::$app->request->post('password');
        if (empty($mobile) || empty($password)) {
            self::jsonReturnError(Constants::CODE_FAILED, '非空选项', []);
        }
        if (strlen($mobile) != 11 || !is_string($mobile) || !ctype_digit($mobile)) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机号错误', []);
        }
        $result = User::checkLogin($mobile, md5($password));
        if (empty($result)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '用户名或密码错误', []);
        }
        $token = Token::generateToken($result['userName']);
        RedisClient::getInstance()->set($token, ['userName' => $result['userName'], 'token' => $token]);
        RedisClient::getInstance()->expire($token, Constants::LOGIN_TOKEN_EXPIRES);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '登陆成功', [
            'id' => $result['id'],
            'userName' => $result['userName'],
            'token' => $token,
        ]);
    }


}
