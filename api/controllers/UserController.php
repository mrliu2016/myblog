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
        RedisClient::getInstance()->set($token, json_encode(['userName' => $result['userName'], 'token' => $token]));
        RedisClient::getInstance()->expire($token, Constants::LOGIN_TOKEN_EXPIRES);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '登陆成功', [
            'id' => $result['id'],
            'userName' => $result['userName'],
            'token' => $token,
        ]);
    }

    public function actionLogout()
    {
        $headers = \Yii::$app->request->headers;
        if (!isset($headers['token'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, 'missing token!');
        }
        $usinfo = json_decode(RedisClient::getInstance()->get($headers['token']), true);
        if ($headers['token'] != $usinfo['token']) {
            $this->jsonReturnError(Constants::CODE_FAILED, 'token错误');
        }
        RedisClient::getInstance()->del($headers['token']);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '退出成功', []);
    }

    public function actionVerified()
    {
        $params = array(
            'id' => \Yii::$app->request->post('id'),
            'idCard' => \Yii::$app->request->post('idCard'),
            'realName' => \Yii::$app->request->post('realName'),
        );
        if (empty($params['idCard']) || strlen($params['idCard']) != 18 || !ctype_digit(substr($params['idCard'], 0, 17)) || empty($params['realName'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        $dat['id'] = User::veriFied($params);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '实名成功', []);
    }

    public function actionSetPassword()
    {
        $params = array(
            'id' => \Yii::$app->request->post('id'),
            'password' => md5(\Yii::$app->request->post('password')),
        );
        if (User::setPassworld($params)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '设置密码成功', []);
        }
    }

    public function actionBindPhone()
    {
        $params = array(
            'id' => \Yii::$app->request->post('id'),
            'mobile' => \Yii::$app->request->post('mobile'),
        );
        if (empty($params['mobile']) || !is_string($params['mobile']) || strlen($params['mobile']) != 11 || !ctype_digit($params['mobile'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        if (User::bindPhone($params)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '绑定手机成功', []);
        }
    }
}
