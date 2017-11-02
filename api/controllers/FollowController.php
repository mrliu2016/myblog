<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\FollowService;
use Yii;

class FollowController extends BaseController
{
    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    //关注
    public function actionAttention()
    {
        $params = Yii::$app->request->post();
        $result = FollowService::attention($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    //关注列表
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $result = FollowService::getUserFollowList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}