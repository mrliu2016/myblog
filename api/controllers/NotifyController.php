<?php

namespace app\api\controllers;

use app\common\components\WeiXinPay;
use app\common\models\Deposit;
use app\common\models\User;
use app\common\services\Constants;
use app\common\services\VideoService;
use app\common\models\TestPayment;

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

    //微信回调
    public function actionWeixinCallBack(){
         $result = file_get_contents('php://input');
        // ll($result,'call_back.log');
         // 将微信回调返回的xml转换成数组
        $dat = TestPayment::xmlToArr($result);
          ll(json_encode($dat),'call_back.log');

    }



    //更新视频录播地址和视频封面图
    public function actionVideo(){
        $params = \Yii::$app->request->get();
        $result = VideoService::updateVideoInfo($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}