<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\LiveService;
use Yii;

class ServerController extends BaseController
{
    public function actionLocation()
    {
        $params = Yii::$app->request->get();
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '获取成功',
            LiveService::serverInfo($params)
        );
    }
}