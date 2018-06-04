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
use app\common\models\UserFeedback;
use app\common\services\Constants;
use app\common\services\UserService;
use app\common\components\SMSHelper;
use Yii;

class UserController extends BaseController
{
    const PAGE_SIZE = 15;

    /**
     * 手机号登录、验证码登录
     */
    public function actionLogin()
    {
        $mobile = Yii::$app->request->post('mobile');
        $password = Yii::$app->request->post('password');
        $verifyCode = Yii::$app->request->post('verifyCode');
        $verifyCode = isset($verifyCode) ? $verifyCode : 0;
        $redisClient = RedisClient::getInstance();
        if (strlen($mobile) != 11 || !is_string($mobile) || !ctype_digit($mobile)) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机号错误!');
        }
        if (!empty($verifyCode)) {
            $key = Constants::PROJECT . ':' . Constants::VERIFY_CODE . ':' . Constants::VERIFY_CODE_LOGIN . ':' . $mobile;
            if (!($redisClient->exists($key))) {
                self::jsonReturnError(Constants::CODE_FAILED, '手机验证码已失效!');
            }
            $cacheVerifyCode = $redisClient->get($key);
            if (intval($cacheVerifyCode) != intval($verifyCode)) {
                self::jsonReturnError(Constants::CODE_FAILED, '手机验证码错误!');
            }
            if (empty(User::queryByPhone($mobile))) {
                User::Register($mobile, !empty($password) ? md5($password) : '');
            }
            $result = User::checkLogin($mobile, md5($password), true);
        } else {
            if (empty($mobile) || empty($password)) {
                self::jsonReturnError(Constants::CODE_FAILED, '请输入手机号或密码');
            }
            if (empty(User::queryByPhone($mobile))) {
                $this->jsonReturnError(Constants::CODE_FAILED, '该手机号未注册', []);
            }
            $result = User::checkLogin($mobile, md5($password));
        }
        if (empty($result)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '手机号或密码错误');
        }
        $token = Token::generateToken($result['id']);
        $redisClient->set(Constants::TTT_TECH_TOKEN . ':' . $token, json_encode(['userId' => $result['id'], 'token' => $token]));
        $redisClient->expire(Constants::TTT_TECH_TOKEN . ':' . $token, Constants::LOGIN_TOKEN_EXPIRES);
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
                'roomId' => intval($result['roomId']),
                'level' => $result['level'],
                'balance' => intval(!empty($result['balance']) ? $result['balance'] : 0),
            ]
        );
    }

    /**
     * 退出登录
     */
    public function actionLogout()
    {
        $headers = Yii::$app->request->headers;
//        if (!isset($headers['token'])) {
//            $this->jsonReturnError(Constants::CODE_FAILED, '已过期!');
//        }
        $redisClient = RedisClient::getInstance();
        if ($redisClient->exists(Constants::TTT_TECH_TOKEN . ':' . $headers['token'])) {
            $result = json_decode($redisClient->get(Constants::TTT_TECH_TOKEN . ':' . $headers['token']), true);
            if ($headers['token'] != $result['token']) {
                $this->jsonReturnError(Constants::CODE_FAILED, '退出登录失败，请稍后重试!');
            }
            $redisClient->del(Constants::TTT_TECH_TOKEN . ':' . $headers['token']);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '退出登录成功', []);
    }

    //实名认证
    public function actionVerified()
    {
        $params = array(
            'id' => Yii::$app->request->post('id'),
            'idCard' => Yii::$app->request->post('idCard'),
            'realName' => Yii::$app->request->post('realName'),
        );
        if (empty($params['idCard']) || strlen($params['idCard']) != 18 || !ctype_digit(substr($params['idCard'], 0, 17)) || empty($params['realName'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        $dat['id'] = User::veriFied($params);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '实名成功', $dat);
    }

    /**
     * 修改密码、忘记密码
     */
    public function actionSetPassword()
    {
        $params = array(
            'mobile' => Yii::$app->request->post('mobile'),
            'password' => md5(Yii::$app->request->post('password')),
        );
        $verifyCode = Yii::$app->request->post('verifyCode');
        $redisClient = RedisClient::getInstance();
        $key = Constants::PROJECT . ':' . Constants::VERIFY_CODE . ':' . Constants::VERIFY_CODE_RESET . ':' . $params['mobile'];
        $cacheVerifyCode = $redisClient->get($key);
        if (intval($cacheVerifyCode) != intval($verifyCode)) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机验证码错误');
        }
        $dat = User::queryByPhone($params['mobile']);
        if (!empty($dat)) {
            if (User::setPassworld($params)) {
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '设置密码成功', []);
            }
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '该手机号未注册', []);
        }
    }

    //手机号绑定
    public function actionBindPhone()
    {
        $params = array(
            'id' => Yii::$app->request->post('id'),
            'mobile' => Yii::$app->request->post('mobile'),
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
            'id' => Yii::$app->request->post('id'),
            'avatar' => Yii::$app->request->post('avatar'),
            'nickName' => Yii::$app->request->post('nickName'),
            'province' => Yii::$app->request->post('province'),
            'city' => Yii::$app->request->post('city'),
            'description' => Yii::$app->request->post('description'),
        );
        if (User::informationUpdate($params)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '成功', []);
        }
    }

    /**
     * 个人信息
     *
     * @throws \yii\db\Exception
     */
    public function actionProfile()
    {
        $params = Yii::$app->request->get();
        $list = User::profile($params['userId'], $params['observerUserId']);
        if (!empty($list)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '获取成功', $list);
        }
        $this->jsonReturnError(Constants::CODE_FAILED, '系统繁忙，请稍后重试!');
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

    /**
     * 手机号注册
     */
    public function actionPhoneRegister()
    {
        $mobile = Yii::$app->request->post('mobile');
        $password = Yii::$app->request->post('password');
        $verifyCode = Yii::$app->request->post('verifyCode');
        $verifyCode = isset($verifyCode) ? $verifyCode : 0;
        if (empty($mobile) || empty($password)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '请输入手机号或密码', []);
        }
        if (!is_string($mobile) || strlen($mobile) != 11 || !ctype_digit($mobile)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '请输入手机号', []);
        }
        $redisClient = RedisClient::getInstance();
        $key = Constants::PROJECT . ':' . Constants::VERIFY_CODE . ':' . Constants::VERIFY_CODE_REGISTER . ':' . $mobile;
        if (!($redisClient->exists($key))) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机验证码已失效!');
        }
        $cacheVerifyCode = $redisClient->get($key);
        if (intval($cacheVerifyCode) != intval($verifyCode)) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机验证码错误');
        }
        if (empty(User::queryByPhone($mobile))) {
            if (User::Register($mobile, md5($password))) {
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '注册成功');
            } else {
                $this->jsonReturnError(Constants::CODE_FAILED, '注册失败');
            }
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '该手机号已注册', []);
        }
    }

    /**
     * 发送验证码
     */
    public function actionVerificationCode()
    {
        $mobile = Yii::$app->request->post('mobile');
        $type = Yii::$app->request->post('type');
        if (strlen($mobile) != 11 || !is_string($mobile) || !ctype_digit($mobile)) {
            self::jsonReturnError(Constants::CODE_FAILED, '请输入正确的手机号!');
        }
        $result['code'] = Token::code();
        if (SMSHelper::send($result['code'], '三体云联验证码', $mobile)) {
            $redis = RedisClient::getInstance();
            $key = Constants::PROJECT . ':' . Constants::VERIFY_CODE . ':' . $type . ':' . $mobile;
            $redis->set($key, $result['code']);
            $redis->expire($key, Constants::VERIFY_CODE_EXPIRES);
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '验证码发送成功', $result);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '验证码发送失败');
        }
    }

    /**
     * 用户搜索
     */
    public function actionSearch()
    {
        $content = Yii::$app->request->post('content');
        $observerUserId = Yii::$app->request->post('observerUserId');
        $params = Yii::$app->request->post();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $list = User::SearchUser($content, $observerUserId, $params);
        $totalCount = intval(User::queryInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        if (!empty($list)) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '搜索成功',
                compact('totalCount', 'page', 'size', 'pageCount', 'list')
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }

    //检查手机号是否被注册过
    public function actionCheckPhone()
    {
        $mobile = Yii::$app->request->post('mobile');
        if (!is_string($mobile) || strlen($mobile) != 11 || !ctype_digit($mobile)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        $dat = User::queryByPhone($mobile);
        if (empty($dat)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '该手机号未注册', []);
        } else {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '可以', []);
        }
    }
    //编辑用户信息
    public function actionEditUser(){
        $params = Yii::$app->request->post();
        if (empty($params['userId'])){
            $this->jsonReturnError(Constants::CODE_FAILED, 'parameter error', []);
        }
        $result = User::updateUserInfoByUserId($params);
        if(isset($result) && $result['code'] == 0){
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, 'edit success', []);
        }
        else{
            $this->jsonReturnError(Constants::CODE_FAILED, 'edit fail', []);
        }
    }
    //判断该用户是否已认证
    public function actionCheckAuth(){
        $params = Yii::$app->request->post();
        if(empty($params['userId'])){
            $this->jsonReturnError(Constants::CODE_FAILED, 'parameter error', []);
        }
        $result = User::checkUserCredentials($params);
        if(!empty($result) && $result['code'] == 0){
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, 'check success', []);
        }
        else{
            $this->jsonReturnError(Constants::CODE_FAILED, 'check fail', []);
        }
    }

    //意见反馈
    public function actionUserFeedback(){
        $params = Yii::$app->request->post();
        if(empty($params['userId'])){
            $this->jsonReturnError(Constants::CODE_FAILED, 'parameter error', []);
        }
        if(empty($params['content'])){
            $this->jsonReturnError(Constants::CODE_FAILED, '提交的内容为空', []);
        }
        $result = UserFeedback::insertUserFeedback($params);
        if(!empty($result) && isset($result)){
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS,'提交成功');
        }
        $this->jsonReturnError(Constants::CODE_FAILED,'提交失败');
    }

}
