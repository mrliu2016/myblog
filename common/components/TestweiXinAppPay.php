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
        if ($wxPayApi['code'] == -1) {
            return ['code' => -1, 'message' => $wxPayApi['message']];
        }
        //调起支付接口
        $result = static::getAppApiParameters($wxPayApi['data'], $weiXinConfig['wxPayKey']);
        print_r($result);
        die;
    }


    public static function getAppApiParameters($result, $wxPayKey)
    {
        if (!array_key_exists('appid', $result) || !array_key_exists('mch_id', $result) || !array_key_exists('prepay_id', $result)) {
            return ['code' => -1, 'message' => '统一的下单未成功'];
        }
        $weiXinAppApi = new WxPayAppApiPay();
        $weiXinAppApi->setPayKey($wxPayKey);
        $weiXinAppApi->setAppId($result['appid']);
        $weiXinAppApi->setPartnerId($result['mch_id']);
        $weiXinAppApi->setPrepayId($result['prepay_id']);
        $weiXinAppApi->setPackage($result['Sign=WXPay']);
        $weiXinAppApi->setNonceStr(WxPayApi::getNonceStr());
        $weiXinAppApi->setTimeStamp(strval(time()));
        $weiXinAppApi->setSignType($result['sign']);
        $weiXinAppApi->setPaySign($weiXinAppApi->makeSign());
        return [
            'code' => 1,
            'message' => 'app api parameters success!',
            'data' => $weiXinAppApi->getValues()
        ];
    }
}
