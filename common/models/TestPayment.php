<?php

namespace app\common\models;

use app\common\models\TestOrder;
use app\common\components\TestweiXinAppPay;

class TestPayment
{
    private static $WeixinConfig = array(
        'wxAppId' => 'wx70a3358e75e061f7',
        'wxMchId' => '1440798702', // 商户号
        'wxPayKey' => 'QxKjAppPw1357924QxKjAppPw1357924', // 支付密钥
        'wxAppSecret' => '32d60c56204a0622f6895574cb240c25',
        'body' => '模拟下单测试',
        'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder', // 统一下单
        'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery', //查询订单
        'notifyUrl' => 'http://dev.api.live.3ttech.cn/notify/notify-process',  //回调地址
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

        $user['openId'] = 'o0P9800xU2o5yUQskZ2frc_nM7C8';  // 公众号支付需要
        $taskResult['name'] = '测试支付';
        $result = TestweiXinAppPay::weiXinAppPay($user['openId'], self::$WeixinConfig, $dat['price'], $dat['orderIdAlias'], $taskResult['name']);
        return [
            'code' => ($result['code'] == -1) ? -1 : 1,
            'message' => $result['message'],
            'data' => ($result['code'] == -1) ? [] : [
                'orderId' => $dat['orderIdAlias'],
                'prepayId' => $result['data']
            ]
        ];
    }


    /**
     * 模拟space的二维码
     * */
    public static function WeiXinNativePay($params)
    {
        $data = array(
            'appid' => self::$WeixinConfig['wxAppId'],
            'mch_id' => self::$WeixinConfig['wxMchId'],
            'nonce_str' => md5(microtime() . 'weixin' . rand(100, 9999)),
            'body' => self::$WeixinConfig['body'],
            'out_trade_no' => time() . rand(100000, 999999),
            'fee_type' => 'CNY',
            'total_fee' => $params['price'] * 100,
            'spbill_create_ip' => $_SERVER["REMOTE_ADDR"],
            'time_start' => date('YmdHis'),
            'time_expire' => date('YmdHis', strtotime('+2 hours')),
            'notify_url' => self::$WeixinConfig['notifyUrl'],
            'trade_type' => 'NATIVE',
            'product_id' => $params['goodsid'],
        );
        ksort($data);
        $str = http_build_query($data);
        $str = static::joinAPI_KEY2($str);
        $data['sign'] = strtoupper(md5(urldecode($str)));
        $xml = static::arrToXML($data);
        //请求统一下单订单接口,微信返回的xml
        $result = static::postXmlCurl($xml, self::$WeixinConfig['unifiedOrder']);
// 解析微信返回的xml
        $data = static::xmlToArr($result);
         if($data['RETURN_CODE'] == 'SUCCESS' && $data['RESULT_CODE'] == 'SUCCESS'){
                 return $data['CODE_URL'];
         } else {
             return '';
         }


        /*  另一种写法
        ksort($data);
        // 数组转url字符串
        $str = static::arrayToKeyValueString($data);
        // 字符串后面拼接key
        $str = static::joinAPI_KEY($str);
        // 获取签名
        $data['sign'] = static::getSign($str);
        // 数组 data  转换成xml
        $xml = static::arrToXML($data);
        //请求统一下单订单接口,微信返回的xml
        $result = static::postXmlCurl($xml, self::$WeixinConfig['unifiedOrder']);
        echo $result;  */
    }

    /**
     *  数组转换字符串
     *
     * */
    public static function arrayToKeyValueString($params)
    {
        $str = '';
        foreach ($params as $key => $value) {
            $str = $str . $key . '=' . $value . '&';
        }
        return $str;
    }

    private static function joinAPI_KEY($str)
    {
        return $str . "key=" . self::$WeixinConfig['wxPayKey'];
    }

    private static function joinAPI_KEY2($str)
    {
        return $str . "&key=" . self::$WeixinConfig['wxPayKey'];
    }

    private static function getSign($signParams = '')
    {
        return strtoupper(md5($signParams));
    }

    /**
     * 数组转换成xml
     *
     * */
    private static function arrToXML($param, $cdata = false)
    {
        $xml = "<xml>";
        $cdataPrefix = $cdataSuffix = '';
        if ($cdata) {
            $cdataPrefix = '<![CDATA[';
            $cdataSuffix = ']]>';
        }
        foreach ($param as $key => $value) {
            $xml .= "<{$key}>{$cdataPrefix}{$value}{$cdataSuffix}</$key>";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * xml 转换成数组
     * */
    private static function xmlToArr($xml)
    {
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $xml, $data, $index);
        $arr = array();
        foreach ($data as $key => $value) {
            if (isset($value['tag']) && isset($value['value']) && ($value['tag'] != 'XML')) {
                if (!empty($value['value'])) {
                    $arr[$value['tag']] = $value['value'];
                }
            }
        }
        return $arr;
    }

    /**
     *post方式提交url
     * $useCert  false 不需要验证证书
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $curl = curl_init();
        try {
            //设置超时
            curl_setopt($curl, CURLOPT_TIMEOUT, $second);
            if ($useCert == true) {
                curl_setopt($curl, CURLOPT_URL, self::$weiXin['CASH_HTTPS']);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                //设置证书
                //使用证书：cert 与 key 分别属于两个.pem文件
                curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLCERT, self::$weiXin['SSLCERT_PATH']);
                curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLKEY, self::$weiXin['SSLKEY_PATH']);
            } else {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
                //post提交方式
                curl_setopt($curl, CURLOPT_POST, TRUE);
                //设置header
                curl_setopt($curl, CURLOPT_HEADER, FALSE);
            }
            //要求结果为字符串且输出到屏幕上
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
            $result = curl_exec($curl);
        } catch (Exception $exception) {
            ll(curl_errno($curl), __FUNCTION__ . '_failed.log');
            curl_close($curl);
            return ['code' => Constants::CODE_FAILED, 'msg' => Constants::WEI_XIN_PAYMENT_EXCEPTION];
        }
        // ll($result, __FUNCTION__ . '_success.log');
        return $result;
    }
}