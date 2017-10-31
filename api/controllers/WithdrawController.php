<?php

namespace app\api\controllers;

use app\common\models\WithdrawDetail;
use app\common\services\Constants;
use app\common\services\WithdrawService;
use Yii;

class WithdrawController extends BaseController
{
    /**
     * 申请提现
     */
    public function actionApplyWithdrew()
    {
        $params = Yii::$app->request->post();
        ll($params, 'actionApplyWithdrew.log');
        $result = WithdrawDetail::applyWithdraw($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, '', []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 同意提现【审核】
     */
    public function actionAgreeWithdraw()
    {
        $params = Yii::$app->request->post();
        $params['appId'] = Yii::$app->params['appId'];
        ll($params, 'actionAgreeWithdraw.log');
        $result = WithdrawDetail::agreeWithdraw($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg']);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 我的提现列表
     */
    public function actionMyWithdraw()
    {
        $params = Yii::$app->request->get();
        $result = WithdrawService::myWithdrawList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}