<?php

namespace app\api\controllers;

use app\common\components\RongCloud;
use app\common\services\Constants;
use Yii;

class RongCloudController extends BaseController
{
    /**
     * 获取融云token
     */
    public function actionRongCloudToken()
    {
        $params = Yii::$app->request->get();
        if (empty($params['avatar'])) {
            $params['avatar'] = Yii::$app->params['defaultAvatar'];
        }
        $token = RongCloud::getToken($params['userId'], $params['nickName'], $params['avatar']);
        if (!empty($token)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '', json_decode($token, true));
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }
}