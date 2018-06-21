<?php

namespace app\api\controllers;

use app\common\models\FusilladeAttribute;
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
     * 礼物连发属性
     */
    public function actionFusilladeAttribute()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $list = FusilladeAttribute::queryInfo($params);
        $totalCount = intval(FusilladeAttribute::queryInfoNum($params));
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

    //礼物 -- 充值记录 送出礼物 收到礼物
    public function actionBill()
    {
        $params = Yii::$app->request->get();
        if (empty($params) || empty($params['userId']) || empty($params['type'])) {
            $this->jsonReturnError(Constants::CODE_FAILED, 'miss parameter');
        }
        $params['defaultPageSize'] = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $params['page'] = !empty($params['page']) ? $params['page'] : 0;
        $page = $params['page'];
        $list = GiftService::queryBillList($params);

        $totalCount = intval(GiftService::queryInfoNumByUserId($params['type'], $params['userId']));
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