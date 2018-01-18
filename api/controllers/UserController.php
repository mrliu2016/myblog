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
use app\common\services\UserService;
use app\common\components\SMSHelper;
use Yii;

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
        $token = Token::generateToken($result['id']);
        RedisClient::getInstance()->set($token, json_encode(['userid' => $result['id'], 'token' => $token]));
        RedisClient::getInstance()->expire($token, Constants::LOGIN_TOKEN_EXPIRES);
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '登录成功',
            [
                'userId' => intval($result['id']),
                'nickName' => $result['nickName'],
                'userName' => $result['userName'],
                'token' => $token,
                'avatar' => $result['avatar'],
                'mobile' => $result['mobile'],
                'roomId' => '',
                'level' => $result['level'],
                'balance' => $result['balance'],
            ]
        );
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

    //实名认证
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
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '实名成功', $dat);
    }

    //修改密码
    public function actionSetPassword()
    {
        $params = array(
            'mobile' => \Yii::$app->request->post('mobile'),
            'password' => md5(\Yii::$app->request->post('password')),
        );
        $dat = User::queryByPhone($params['mobile']);
        if (!empty($dat)) {
            if (User::setPassworld($params)) {
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '设置密码成功', []);
            }
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '该手机号未被注册', []);
        }
    }

    //手机号绑定
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

    //修改个人信息
    public function actionPersonalInformation()
    {
        $params = array(
            'id' => \Yii::$app->request->post('id'),
            'avatar' => \Yii::$app->request->post('avatar'),
            'nickName' => \Yii::$app->request->post('nickName'),
            'province' => \Yii::$app->request->post('province'),
            'city' => \Yii::$app->request->post('city'),
            'description' => \Yii::$app->request->post('description'),
        );
        if (User::informationUpdate($params)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '成功', []);
        }
    }

    //获取个人信息
    public function actionGetPersonal()
    {
        $params = Yii::$app->request->post();
        $list = User::profile($params['userid'], $params['observerUserId']);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '获取个人信息成功', $list);
    }

    //上报位置
    public function actionLocation()
    {
        $params = Yii::$app->request->post();
        $result = UserService::updateUserLocation($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    //附近的人
    public function actionNearby()
    {
        $params = Yii::$app->request->get();
        $result = UserService::nearby($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    //手机号注册
    public function actionPhoneRegister()
    {
        $mobile = Yii::$app->request->post('mobile');
        $password = \Yii::$app->request->post('password');
        if (empty($mobile) || empty($password)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '非空选项', []);
        }
        if (!is_string($mobile) || strlen($mobile) != 11 || !ctype_digit($mobile)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        $dat = User::queryByPhone($mobile);
        if (empty($dat)) {
            if (User::Register($mobile, md5($password))) {
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '注册成功', []);
            }
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '该手机号已被注册', []);
        }
    }

    //发送验证码
    public function actionVerificationCode()
    {
        $mobile = Yii::$app->request->post('mobile');
        if (strlen($mobile) != 11 || !is_string($mobile) || !ctype_digit($mobile)) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机号错误', []);
        }
        $dat['code'] = Token::code();
        if (SMSHelper::send($dat['code'], $mobile)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '验证码发送成功', $dat);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '验证码发送失败', []);
        }
    }

    //用户搜索
    public function actionSearch()
    {
        $content = Yii::$app->request->post('content');
        $observerUserId = Yii::$app->request->post('observerUserId');
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '搜索成功',
            User::SearchUser($content, $observerUserId)
        );
    }

    //检查手机号是否被注册过
    public function actionCheckPhone()
    {
        $mobile = Yii::$app->request->post('mobile');
        $dat = User::queryByPhone($mobile);
        if (empty($dat)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '可以注册', []);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '已被注册', []);
        }
    }
}
