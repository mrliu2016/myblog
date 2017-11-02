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
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message'], []);
        } else {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
        }
    }
}
