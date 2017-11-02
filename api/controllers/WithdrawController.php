<?php

namespace app\api\controllers;

use app\common\models\Withdraw;
use app\common\services\Constants;
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
        $result = Withdraw::applyWithdraw($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
    }

    /**
     * 同意提现【审核】
     */
    public function actionAgreeWithdraw()
    {
        $params = Yii::$app->request->post();
        ll($params, 'actionAgreeWithdraw.log');
        $result = Withdraw::agreeWithdraw($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message']);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
    }
}