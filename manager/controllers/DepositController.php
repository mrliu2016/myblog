<?php

namespace app\manager\controllers;

use app\common\models\Deposit;
use app\common\models\Withdraw;
use app\common\services\Constants;
use Yii;
use yii\data\Pagination;

class DepositController extends BaseController
{
    const PAGE_SIZE = 10;
    public $enableCsrfValidation = false;

    private static function pagination($pageNo, $count)
    {
        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $count,
        ]);
        $pagination->setPage($pageNo);
        return $pagination;
    }

    /**
     * 充值记录
     */
    public function actionRecord()
    {
        $params = Yii::$app->request->getQueryParams();
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $params['defaultPageSize'] = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;
        $result = Deposit::queryInfo($params);
        $count = Deposit::queryInfoNum($params);
        return $this->render('deposit_record', [
            'itemList' => $result['data']['list'],
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Deposit::queryInfo($params);
        $count = Deposit::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionWithdraw()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Withdraw::queryInfo($params);
        $count = Withdraw::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('withdraw', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    /**
     * 提现详情
     */
    public function actionWithdrawDetail()
    {
        $params = Yii::$app->request->get();
        $result = Withdraw::withdrawDetailRule($params);
        if ($result) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, $result);
        }
    }

    /**
     * 同意
     */
    public function actionAgree()
    {
        $params = Yii::$app->request->post();
        ll($params, 'actionAgree.log');
        $result = Withdraw::agreeWithdraw($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg']);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['data']);
    }

    /**
     * 拒绝
     */
    public function actionRefuse()
    {
        $params = Yii::$app->request->post();
        $result = Withdraw::refuseWithdraw($params);
        if ($result) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result);
        } else {
            $this->jsonReturnError(Constants::CODE_FAILED, $result);
        }
    }
}