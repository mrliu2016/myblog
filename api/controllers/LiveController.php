<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\UserService;
use Yii;

class LiveController extends BaseController
{
    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    //直播人气列表
    public function actionHot()
    {
        $params = Yii::$app->request->get();
        $result = UserService::hot($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}