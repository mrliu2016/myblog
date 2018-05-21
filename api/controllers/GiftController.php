<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\GiftService;
use Yii;

class GiftController extends BaseController
{
    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    /**
     * 礼物列表
     */
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $result = GiftService::getGiftList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     *
     * 榜单
     * 0：日榜单，1：周榜单，2：总榜单
     *
     * @throws \yii\db\Exception
     */
    public function actionContribution()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $list = GiftService::contribution($params);
        $totalCount = intval(GiftService::queryInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
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