<?php

namespace app\api\controllers;

use app\common\models\Follow;
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
     */
    public function actionList()
    {
        try {
            $params = Yii::$app->request->get();
            if (!isset($params['userId'])) {
                $this->jsonReturnError(Constants::CODE_SYSTEM_BUSY, '系统繁忙，请稍后重试!');
            }
            $result = FollowService::getUserFollowList($params);
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                $result['msg'],
                ($result['code'] == Constants::CODE_SUCCESS) ? $result['data'] : []
            );
        } catch (\Exception $exception) {
            $this->jsonReturnError(Constants::CODE_FAILED);
        }
    }

    /**
     * 我的关注、我的粉丝
     */
    public function actionMyAttention()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $list = FollowService::queryInfo($params, 'id,userId,userIdFollow,created');
        $totalCount = intval(FollowService::queryInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        if (!empty($list)) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '',
                compact('totalCount', 'page', 'size', 'pageCount', 'list')
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }
}