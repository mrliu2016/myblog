<?php

namespace app\api\controllers;

use app\common\components\WeiXinPay;
use app\common\models\TaskMember;
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
            $resultTaskMember = TaskMember::updateTaskMember(
                $result['notify']['out_trade_no'], $result['notify']['transaction_id'],
                $result['notify']['time_end'], Constants::TASK_PAY_STATUS_SUCCESS
            );
            if ($resultTaskMember) {
                echo $result['data'];
            }
        }
    }
}