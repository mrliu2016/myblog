<?php

namespace app\api\controllers;

use app\common\models\User;
use app\common\services\Constants;
use Yii;

class AuthorizeLoginController extends BaseController
{
    public function actionAuthorizeLogin()
    {
        $params = Yii::$app->request->post();
        $result = User::authorizeLogin($params);
        if (!$result) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '登录成功!', $result);
        }
        $this->jsonReturnError(Constants::CODE_FAILED, '登录失败!', []);
    }
}
