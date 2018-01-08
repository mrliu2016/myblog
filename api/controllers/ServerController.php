<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\LiveService;
use Yii;

class ServerController extends BaseController
{
    public function actionLocation(){
        $params = Yii::$app->request->get();
        $result = LiveService::serverInfo($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}