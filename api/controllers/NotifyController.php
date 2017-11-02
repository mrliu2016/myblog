<?php

namespace app\api\controllers;

use app\common\components\WeiXinPay;
use app\common\models\Deposit;
use app\common\models\User;
use app\common\services\Constants;

class NotifyController extends BaseController
{
    /**
     * 微信支付结果通知
     */
    public function actionNotifyProcess()
    {
        $result = WeiXinPay::weiXinPayResults(file_get_contents('php://input'));
        ll($result, 'notify_process.log');
        $result = WeiXinPay::notifyProcess($result);
        ll($result, 'notify_process.log');
        if ($result['code'] == Constants::CODE_SUCCESS) {
            $res = Deposit::updateDeposit(
                $result['notify']['out_trade_no'], $result['notify']['transaction_id'],
                $result['notify']['time_end'], Constants::ORDER_STATUS_COMPLETED
            );

            if ($res) {
                User::updateUserBalance($res['userId'],$res['price']);
                echo $result['data'];
            }
        }
    }
}