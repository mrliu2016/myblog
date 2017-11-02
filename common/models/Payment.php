<?php

namespace app\common\models;

use app\common\components\WeiXinPay;
use app\common\services\Constants;
use Yii;

class Payment
{

    /**
     * @param array $params
     * @param string $payType
     * @return array
     */
    public static function weiXinPay($params = [], $payType = Constants::WEI_XIN_APP_TRADE)
    {
        $result = [];
        $orderId = Deposit::orderIdAlias();
        $user['openId'] = 'o0P9800xU2o5yUQskZ2frc_nM7C8';
        $taskResult['name'] = '测试支付';
        switch ($payType) {
            case Constants::WEI_XIN_APP_TRADE:
                $weiXinConfig = Yii::$app->params['app'];
                $result = WeiXinPay::weiXinAppPay($user['openId'], $orderId, $params['price'], $weiXinConfig['wxAppId'],
                    $weiXinConfig['wxMchId'], $weiXinConfig['wxPayKey'], $taskResult['name'], $taskResult['name'], $taskResult['name']);
                break;
            case Constants::WEI_XIN_JS_TRADE:
                $weiXinConfig = Yii::$app->params['jsApi'];
                $result = WeiXinPay::weiXinJsPay($user['openId'], $orderId, $params['price'], $weiXinConfig['wxAppId'],
                    $weiXinConfig['wxMchId'], $weiXinConfig['wxPayKey'], $taskResult['name'], $taskResult['name'], $taskResult['name']);
                break;
        }
        return [
            'code' => ($result['code'] == Constants::CODE_FAILED) ? Constants::CODE_FAILED : Constants::CODE_SUCCESS,
            'message' => $result['message'],
            'data' => ($result['code'] == Constants::CODE_FAILED) ? [] : [
                'orderId' => $orderId,
                'prepayId' => $result['data']
            ]
        ];
    }

    /**
     * 查询订单
     * @param array $params
     * @return array
     */
    public static function queryOrder($params = [])
    {
        $application = Application::queryById($params['appId']);
        if (isset($params['transaction_id']) && !empty($params['transaction_id'])) {
            return WeiXinPay::weiXinOrderQuery($params['transaction_id'], $application['wxAppId'],
                $application['wxMchId'], $application['wxPayKey'], false);
        }
        if (isset($params['out_trade_no']) && !empty($params['out_trade_no'])) {
            return WeiXinPay::weiXinOrderQuery($params['out_trade_no'], $application['wxAppId'],
                $application['wxMchId'], $application['wxPayKey'], true);
        }
    }
}