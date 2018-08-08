<?php

namespace app\api\controllers;

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
        $keyFrequency = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE_FREQUENCY . ':' . Constants::VERIFY_CODE_LOGIN . ':' . $mobile;
        if (!empty($verifyCode)) {
            $time = time();
            $key = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE . ':' . Constants::VERIFY_CODE_LOGIN . ':' . $mobile;
            if (!($redisClient->exists($key))) {
                self::jsonReturnError(Constants::CODE_FAILED, '手机验证码已失效!');
            }
            if ($redisClient->exists($keyFrequency)) {
                $frequencyResult = json_decode($redisClient->get($keyFrequency), true);
                if ($frequencyResult['failureTimes'] >= Constants::VERIFY_CODE_LIMIT_FREQUENCY) {
                    static::jsonReturnError(Constants::CODE_VERIFY_CODE_LIMIT_FREQUENCY, '手机验证码错误，操作太频繁，请稍后再试');
                }
            }
            $cacheVerifyCode = $redisClient->get($key);
            if (intval($cacheVerifyCode) != intval($verifyCode)) {
                if ($redisClient->exists($keyFrequency)) {
                    $frequencyResult = json_decode($redisClient->get($keyFrequency), true);
                    $frequencyResult['failureTimes'] += 1;
                    $redisClient->set($keyFrequency, json_encode($frequencyResult));
                } else {
                    $redisClient->set($keyFrequency, json_encode(['firstTimes' => $time, 'latestTimes' => $time, 'failureTimes' => 1, 'sendTimes' => 0]));
                    $redisClient->expire($keyFrequency, Constants::VERIFY_CODE_LIMIT_FREQUENCY_EXPIRES);
                }
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
        switch ($result['playType']) {
            case 4:
                $this->jsonReturnError(Constants::CODE_FAILED, '该账号已被封禁', []);
                break;
            default:
                $token = Token::generateToken($result['id']);
                $redisClient->set(Constants::TTT_TECH_TOKEN . ':' . $token, json_encode(['userId' => $result['id'], 'token' => $token]));
                $redisClient->expire(Constants::TTT_TECH_TOKEN . ':' . $token, Constants::LOGIN_TOKEN_EXPIRES);
                $redisClient->del($keyFrequency);
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
                break;
        }
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
        $time = time();
        $redisClient = RedisClient::getInstance();
        $key = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE . ':' . Constants::VERIFY_CODE_RESET . ':' . $params['mobile'];
        $cacheVerifyCode = $redisClient->get($key);
        $keyFrequency = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE_FREQUENCY . ':' . Constants::VERIFY_CODE_RESET . ':' . $params['mobile'];
        if ($redisClient->exists($keyFrequency)) {
            $frequencyResult = json_decode($redisClient->get($keyFrequency), true);
            if ($frequencyResult['failureTimes'] >= Constants::VERIFY_CODE_LIMIT_FREQUENCY) {
                static::jsonReturnError(Constants::CODE_VERIFY_CODE_LIMIT_FREQUENCY, '手机验证码错误，操作太频繁，请稍后再试');
            }
        }
        if (intval($cacheVerifyCode) != intval($verifyCode)) {
            if ($redisClient->exists($keyFrequency)) {
                $frequencyResult = json_decode($redisClient->get($keyFrequency), true);
                $frequencyResult['failureTimes'] += 1;
                $redisClient->set($keyFrequency, json_encode($frequencyResult));
            } else {
                $redisClient->set($keyFrequency, json_encode(['firstTimes' => $time, 'latestTimes' => $time, 'failureTimes' => 1, 'sendTimes' => 0]));
                $redisClient->expire($keyFrequency, Constants::VERIFY_CODE_LIMIT_FREQUENCY_EXPIRES);
            }
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
        $time = time();
        if (empty($mobile) || empty($password)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '请输入手机号或密码', []);
        }
        if (!is_string($mobile) || strlen($mobile) != 11 || !ctype_digit($mobile)) {
            $this->jsonReturnError(Constants::CODE_FAILED, '请输入手机号', []);
        }
        $redisClient = RedisClient::getInstance();
        $key = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE . ':' . Constants::VERIFY_CODE_REGISTER . ':' . $mobile;
        if (!($redisClient->exists($key))) {
            self::jsonReturnError(Constants::CODE_FAILED, '手机验证码已失效!');
        }
        $cacheVerifyCode = $redisClient->get($key);
        $keyFrequency = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE_FREQUENCY . ':' . Constants::VERIFY_CODE_REGISTER . ':' . $mobile;
        if ($redisClient->exists($keyFrequency)) {
            $frequencyResult = json_decode($redisClient->get($keyFrequency), true);
            if ($frequencyResult['failureTimes'] >= Constants::VERIFY_CODE_LIMIT_FREQUENCY) {
                static::jsonReturnError(Constants::CODE_VERIFY_CODE_LIMIT_FREQUENCY, '手机验证码错误，操作太频繁，请稍后再试');
            }
        }
        if (intval($cacheVerifyCode) != intval($verifyCode)) {
            if ($redisClient->exists($keyFrequency)) {
                $frequencyResult = json_decode($redisClient->get($keyFrequency), true);
                $frequencyResult['failureTimes'] += 1;
                $redisClient->set($keyFrequency, json_encode($frequencyResult));
            } else {
                $redisClient->set($keyFrequency, json_encode(['firstTimes' => $time, 'latestTimes' => $time, 'failureTimes' => 1, 'sendTimes' => 0]));
                $redisClient->expire($keyFrequency, Constants::VERIFY_CODE_LIMIT_FREQUENCY_EXPIRES);
            }
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
        $redis = RedisClient::getInstance();
        $keyFrequency = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE_FREQUENCY . ':' . $type . ':' . $mobile;
        $time = time();
        if ($redis->exists($keyFrequency)) {
            $frequencyResult = json_decode($redis->get($keyFrequency), true);
            if (intval($frequencyResult['latestTimes'] - $frequencyResult['firstTimes']) <= Constants::VERIFY_CODE_LIMIT_FREQUENCY_EXPIRES
                && $frequencyResult['sendTimes'] >= Constants::VERIFY_CODE_LIMIT_FREQUENCY) {
                $this->jsonReturnError(Constants::CODE_FAILED, '一小时内，允许发送5次');
            }
        }
        $result['code'] = Token::code();
        if (SMSHelper::sendCaptcha($result['code'], $mobile)) {
            $key = Constants::PROJECT_NAME . ':' . Constants::VERIFY_CODE . ':' . $type . ':' . $mobile;
            $redis->set($key, $result['code']);
            $redis->expire($key, Constants::VERIFY_CODE_EXPIRES);
            if ($redis->exists($keyFrequency)) {
                $frequencyResult = json_decode($redis->get($keyFrequency), true);
                $frequencyResult['sendTimes'] += 1;
                $frequencyResult['latestTimes'] = $time;
                $redis->set($keyFrequency, json_encode($frequencyResult));
                $redis->expire($keyFrequency, $redis->ttl($keyFrequency));
            } else {
                $redis->set($keyFrequency, json_encode(['firstTimes' => $time, 'latestTimes' => $time, 'failureTimes' => 0, 'sendTimes' => 1]));
                $redis->expire($keyFrequency, Constants::VERIFY_CODE_LIMIT_FREQUENCY_EXPIRES);
            }
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
        $content = str_replace([" ", "　", "\t", "\n", "\r"], '', Yii::$app->request->post('content'));
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
    public function actionEditUser()
    {
        $params = Yii::$app->request->post();
        if (empty($params['userId'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        $result = User::updateUserInfoByUserId($params);
        $message = '';
        if (!empty($result['avatar'])) {
            $message = '编辑';
        } else {
            $message = '保存';
        }
        if (isset($result) && $result['code'] == 0) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $message . '成功', []);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, $message . '失败', []);
        }
    }

    //判断该用户是否已认证
    public function actionCheckAuth()
    {
        $params = Yii::$app->request->get();
        if (empty($params['userId'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        $result = User::checkUserCredentials($params);
        if (!empty($result) && $result['code'] == 0) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '验证成功', []);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, '验证失败', []);
        }
    }

    //意见反馈
    public function actionUserFeedback()
    {
        $params = Yii::$app->request->post();
        if (empty($params['userId'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '参数错误', []);
        }
        if (empty($params['content'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '提交的内容为空', []);
        }
        $result = UserFeedback::insertUserFeedback($params);
        if ($result['code'] == 0) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '提交成功');
        }
        $this->jsonReturnError(Constants::CODE_FAILED, '提交失败');
    }

}
