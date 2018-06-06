<?php

namespace app\common\components;

use app\common\extensions\WeiXinPay\Lib\WxPayResults;
use app\common\extensions\WeiXinPay\Lib\WxPayTransfers;
use app\common\extensions\WeiXinPay\Lib\WxPayUnifiedOrder;
use app\common\extensions\WeiXinPay\Lib\WxPayApi;
use app\common\extensions\WeiXinPay\Lib\WxPayOrderQuery;
use app\common\extensions\WeiXinPay\Lib\WxPayJsApiPay;
use app\common\extensions\WeiXinPay\Lib\WxPayAppApiPay;
use app\common\services\Constants;
use Yii;

class TestweiXinAppPay
{
    /**
     *
     *
     *
     * */
    public static function weiXinAppPay($openId, $weiXinConfig, $price, $orderIdAlias, $name)
    {
        $unifiedOrder = new WxPayUnifiedOrder();
        $unifiedOrder->setAppId($weiXinConfig['wxAppId']);
        $unifiedOrder->setMchId($weiXinConfig['wxMchId']);
        $unifiedOrder->setPayKey($weiXinConfig['wxPayKey']);
        $unifiedOrder->setBody($weiXinConfig['body']);
        $unifiedOrder->setOutTradeNo($orderIdAlias);
        $unifiedOrder->setFeeType('CNY');
        $unifiedOrder->setTotalFee($price * 100);
        $unifiedOrder->setTimeStart(date('YmdHis'));
        $unifiedOrder->setTimeExpire(date('YmdHis', strtotime('+2 hours')));
        $unifiedOrder->setNotifyUrl($weiXinConfig['notifyUrl']);
        $unifiedOrder->setTradeType('APP');
        $wxPayApi = WxPayApi::unifiedOrder($weiXinConfig['unifiedOrder'], $unifiedOrder);
        print_r($wxPayApi);
        die;
    }

}
