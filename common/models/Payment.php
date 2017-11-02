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
        $orderInfo = Deposit::createOrder($params, 0);
        if ($orderInfo['code'] == Constants::CODE_FAILED) return $orderInfo;
        $user['openId'] = 'o0P9800xU2o5yUQskZ2frc_nM7C8';
        $taskResult['name'] = '测试支付';
        switch ($payType) {
            case Constants::WEI_XIN_APP_TRADE:
                $weiXinConfig = Yii::$app->params['app'];
                $result = WeiXinPay::weiXinAppPay($user['openId'], $orderInfo['orderId'], $orderInfo['price'], $weiXinConfig['wxAppId'],
                    $weiXinConfig['wxMchId'], $weiXinConfig['wxPayKey'], $taskResult['name'], $taskResult['name'], $taskResult['name']);
                break;
            case Constants::WEI_XIN_JS_TRADE:
                $weiXinConfig = Yii::$app->params['jsApi'];
                $result = WeiXinPay::weiXinJsPay($user['openId'], $orderInfo['orderId'], $orderInfo['price'], $weiXinConfig['wxAppId'],
                    $weiXinConfig['wxMchId'], $weiXinConfig['wxPayKey'], $taskResult['name'], $taskResult['name'], $taskResult['name']);
                break;
        }
        return [
            'code' => ($result['code'] == Constants::CODE_FAILED) ? Constants::CODE_FAILED : Constants::CODE_SUCCESS,
            'message' => $result['message'],
            'data' => ($result['code'] == Constants::CODE_FAILED) ? [] : [
                'orderId' => $orderInfo['orderId'],
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
        $type = isset($params['type']) ? (!empty($params['type']) ? $params['type'] : '') : '';
        switch ($type) {
            case Constants::WEI_XIN_APP_TRADE:
                $weiXinConfig = Yii::$app->params['app'];
                return WeiXinPay::weiXinOrderQuery($params['orderId'], $weiXinConfig['wxAppId'],
                    $weiXinConfig['wxMchId'], $weiXinConfig['wxPayKey'], true);
                break;
            case Constants::WEI_XIN_JS_TRADE:
                $weiXinConfig = Yii::$app->params['jsApi'];
                return WeiXinPay::weiXinOrderQuery($params['orderId'], $weiXinConfig['wxAppId'],
                    $weiXinConfig['wxMchId'], $weiXinConfig['wxPayKey'], true);
                break;
            default:
                return [
                    'code' => Constants::CODE_FAILED, 'message' => '查询订单失败!',];
                break;
        }
    }
}