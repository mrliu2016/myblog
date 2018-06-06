<?php

namespace app\common\models;

use app\common\models\TestOrder;
use app\common\components\TestweiXinAppPay;

class TestPayment
{

    public static $weixinconfig = array(
        'wxAppId' => 'wx70a3358e75e061f7',
        'wxMchId' => '1440798702',   //商户号
        'wxPayKey' => 'QxKjAppPw1357924QxKjAppPw1357924',  // 支付密钥
        'wxAppSecret' => '32d60c56204a0622f6895574cb240c25',
        'body'=>'下单测试',
        'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',  // 统一下单接口
        'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',  //查询订单接口
        'notifyUrl' => 'http://dev.api.customize.3ttech.cn/notify/notify-process', // 回掉地址
        'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
        'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
        'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
        'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='
    );


    public static function weiXinPay($params)
    {
        //先去检测订单表里是否存在该订单
        $dat = TestOrder::queryAll($params);
        if ($dat['code'] == -1) return $dat;

        $user['openId'] = 'o0P9800xU2o5yUQskZ2frc_nM7C8';
        $taskResult['name'] = '测试支付';
        TestweiXinAppPay::weiXinAppPay($user['openId'], self::$weixinconfig, $dat['price'], $dat['orderIdAlias'], $taskResult['name']);


    }


}