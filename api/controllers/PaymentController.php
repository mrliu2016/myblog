<?php

namespace app\api\controllers;

use app\common\models\Order;
use app\common\services\Constants;
use Yii;

class PaymentController extends BaseController
{
    /**
     * 微信公众号支付
     */
    public function actionWeiXinJsPay()
    {
        $params = Yii::$app->request->post();
        $result = Order::weiXinPay($params,Constants::WEI_XIN_JS_TRADE);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
    }

    /**
     * 微信APP支付
     */
    public function actionWeiXinAppPay()
    {
        $params = Yii::$app->request->post();
        $result = Order::weiXinPay($params,Constants::WEI_XIN_APP_TRADE);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
    }
}