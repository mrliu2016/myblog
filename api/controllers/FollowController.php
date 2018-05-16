<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\FollowService;
use Yii;

class FollowController extends BaseController
{
    const PAGE_SIZE = 15;

    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    /**
     * 关注
     */
    public function actionAttention()
    {
        $params = Yii::$app->request->post();
        $result = FollowService::attention($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 取消关注
     */
    public function actionCancelAttention()
    {
        $params = Yii::$app->request->post();
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

    public function actionMyAttention()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
    }
}