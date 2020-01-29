<?php

namespace app\backend\controllers;

use app\common\models\Admin;
use app\common\services\Constants;

/**
 * Class UserController
 * @package app\backend\controllers
 * 用户
 */
class UserController extends BaseController
{
    /**
     * 登录
     */
    public function actionLogin()
    {
        if (\Yii::$app->request->isGet) {
            $this->layout = true;
            return $this->render('login');
        } else {
            $params = \Yii::$app->request->post();
            if (empty($params['username']) || empty($params['password'])) {
                $this->jsonReturnError(Constants::CODE_FAILED, '账号或密码错误！');
            }
            $result = Admin::login($params['username'], $params['password']);
            if ($result) {
                $session = \Yii::$app->session;
                $session['admin_name'] = $params['username'];//管理员用户名
                $session['admin_id'] = $result;//管理员ID
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result, '登录成功！');
            }
            $this->jsonReturnError(Constants::CODE_FAILED, '账号或密码错误！');
        }
    }

    public function actionError()
    {
        $this->layout = false;
        return $this->render('error');
    }

    /**
     * 退出
     */
    public function actionLogout()
    {
        $this->layout = true;
        unset(\Yii::$app->session['admin_name']);
        unset(\Yii::$app->session['admin_id']);
        $this->redirect('login');
    }

}