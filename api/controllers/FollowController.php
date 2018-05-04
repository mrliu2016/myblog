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
        ll($params,__FUNCTION__.'.log');
        $result = FollowService::attention($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    public function actionCancelAttention()
    {
        $params = Yii::$app->request->post();
        ll($params,__FUNCTION__.'.log');
        $result = FollowService::cancelAttention($params);
        if (!$result) {
            $this->jsonReturnError(Constants::CODE_FAILED, '取消关注失败!');
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '取消关注成功!');

    }

    /**
     * 关注列表
     *
     * @throws \yii\db\Exception
     */
    public function actionList()
    {
        $params = Yii::$app->request->get();
        if (!isset($params['userId'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, '系统繁忙，请稍后重试!');
        }
        $result = FollowService::getUserFollowList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}