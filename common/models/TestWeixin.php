<?php
/**
 * Created by PhpStorm.
 * User: 左撇子
 * Date: 2018/6/13
 * Time: 21:42
 */

namespace app\common\models;

use yii\db\ActiveQuery;

class TestWeixin extends ActiveRecord
{

    private static $WeixinConfig = array(
        'wxAppId' => 'wxa0413a83379b4290',
        'wxMchId' => '1282937801',
        'wxPayKey' => '9f5404fe732cff39a955ff5dfa9f1364',
        'wxAppSecret' => 'ef16db3f5c7077a75e7192fbba982e52',
        'sslCert' => '/var/www/html/wechat/api/web/upload/34/apiclient_cert.pem',  //公众号证书
        'sslKey' => '/var/www/html/wechat/api/web/upload/34/apiclient_key.pem',  // 证书密钥
        'imgSrc' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/11/10/12/0330_7202.png',
        'qrCode' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/11/10/12/0340_2898.jpg', // 二维码
        'CASH_HTTPS' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers'  // 企业付款地址
    );

    public static function WeixinRefund($params)
    {
        $data = array(
            'mch_appid' => self::$WeixinConfig['wxAppId'],
            'mchid' => self::$WeixinConfig['wxMchId'],
            'nonce_str' => md5(microtime() . 'weixin' . rand(100, 9999)),
            'partner_trade_no' => time() . rand(10000, 99999),
            'openid' => 'oltaz1amknJsYA5SP-27tesYbDsM',
            'check_name' => 'NO_CHECK',
            'amount' => $params['price'] * 100,
            'desc' => '测试退款',
            'spbill_create_ip' => $_SERVER["REMOTE_ADDR"],
        );
        ksort($data);
        $str = http_build_query($data);
        $str = static::joinAPI_KEY2($str);
        $data['sign'] = strtoupper(md5(urldecode($str)));
        $xml = static::arrToXML($data);
        $result = static::postXmlCurl($xml, self::$WeixinConfig['CASH_HTTPS'], true);
        echo $result;
    }

    private static function joinAPI_KEY2($str)
    {
        return $str . "&key=" . self::$WeixinConfig['wxPayKey'];
    }

    /**
     * 数组转换成xml
     *
     * */
    public static function arrToXML($param, $cdata = false)
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
     *post方式提交url
     * $useCert  false 不需要验证证书
     */
    private static function postXmlCurl($xml, $url, $useCert, $second = 30)
    {
        $curl = curl_init();
        try {
            //设置超时
            curl_setopt($curl, CURLOPT_TIMEOUT, $second);
            if ($useCert == true) {
                curl_setopt($curl, CURLOPT_URL, self::$WeixinConfig['CASH_HTTPS']);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                //设置证书
                //使用证书：cert 与 key 分别属于两个.pem文件
                curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLCERT, self::$WeixinConfig['sslCert']);
                curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLKEY, self::$WeixinConfig['sslKey']);
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