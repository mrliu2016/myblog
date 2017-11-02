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

class WeiXinPay
{
    /**
     * 微信公众号JS支付
     *
     * @param string $openId openId
     * @param string $orderId 订单号
     * @param string $totalFee 金额
     * @param string $appId 公众号appId
     * @param string $mchId 商户号
     * @param string $payKey 支付密钥
     * @param string $body 商品描述
     * @param string $attach 附加参数
     * @param string $goodsTag 商品标记
     * @return array
     */
    public static function weiXinJsPay($openId, $orderId, $totalFee, $appId, $mchId, $payKey, $body = '', $attach = '', $goodsTag = '')
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $unifiedOrder = new WxPayUnifiedOrder();
        $unifiedOrder->setAppId($appId);
        $unifiedOrder->setMchId($mchId);
        $unifiedOrder->setPayKey($payKey);
        $unifiedOrder->setBody(!empty($body) ? $body : 'GOODS BODY');
        $unifiedOrder->setAttach(!empty($attach) ? $attach : 'ATTACH');
        $unifiedOrder->setOutTradeNo($orderId);
        $unifiedOrder->setTotalFee($totalFee * Constants::CENT);
        $unifiedOrder->setNotifyUrl($weiXinConfig['notifyUrl']);
        $unifiedOrder->setTradeType("JSAPI");
        $unifiedOrder->setOpenId($openId);
        $wxPayApi = WxPayApi::unifiedOrder($weiXinConfig['unifiedOrder'], $unifiedOrder);
        if ($wxPayApi['code'] == Constants::CODE_FAILED) {
            return ['code' => Constants::CODE_FAILED, 'message' => $wxPayApi['message']];
        }
        $result = static::getJsApiParameters($wxPayApi['data'], $payKey);
        return [
            'code' => ($result['code'] == Constants::CODE_FAILED) ? Constants::CODE_FAILED : Constants::CODE_SUCCESS,
            'message' => $result['message'],
            'data' => ($result['code'] == Constants::CODE_FAILED) ? [] : $result['data']
        ];
    }

    /**
     * 微信公众号APP支付
     *
     * @param string $openId openId
     * @param string $orderId 订单号
     * @param string $totalFee 金额
     * @param string $appId 公众号appId
     * @param string $mchId 商户号
     * @param string $payKey 支付密钥
     * @param string $body 商品描述
     * @param string $attach 附加参数
     * @return array
     */
    public static function weiXinAppPay($openId, $orderId, $totalFee, $appId, $mchId, $payKey, $body = '', $attach = '')
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $unifiedOrder = new WxPayUnifiedOrder();
        $unifiedOrder->setAppId($appId);
        $unifiedOrder->setMchId($mchId);
        $unifiedOrder->setPayKey($payKey);
        $unifiedOrder->setBody(!empty($body) ? $body : 'GOODS BODY');
        $unifiedOrder->setDetail(!empty($body) ? $body : 'DETAIL');
        $unifiedOrder->setFeeType('CNY');
        $unifiedOrder->setNotifyUrl($weiXinConfig['notifyUrl']);
        $unifiedOrder->setTimeStart(date("YmdHms"));
        $unifiedOrder->setTimeExpire(date("YmdHms", strtotime("+2 hours")));
        $unifiedOrder->setTradeType("APP");
        $unifiedOrder->setTotalFee($totalFee * Constants::CENT);
        $unifiedOrder->setOutTradeNo($orderId);
        $unifiedOrder->setAttach(!empty($attach) ? $attach : 'ATTACH');
//        $unifiedOrder->setOpenId($openId);
        $wxPayApi = WxPayApi::unifiedOrder($weiXinConfig['unifiedOrder'], $unifiedOrder);
        if ($wxPayApi['code'] == Constants::CODE_FAILED) {
            return ['code' => Constants::CODE_FAILED, 'message' => $wxPayApi['message']];
        }
        $result = static::getAppApiParameters($wxPayApi['data'], $payKey);
        return [
            'code' => ($result['code'] == Constants::CODE_FAILED) ? Constants::CODE_FAILED : Constants::CODE_SUCCESS,
            'message' => $result['message'],
            'data' => ($result['code'] == Constants::CODE_FAILED) ? [] : $result['data']
        ];
    }

    /**
     * @param $query
     * @param $appId
     * @param $mchId
     * @param $payKey
     * @param bool $isOutTradeNo
     * @return array
     */
    public static function weiXinOrderQuery($query, $appId, $mchId, $payKey, $isOutTradeNo = false)
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $orderQuery = new WxPayOrderQuery();
        if ($isOutTradeNo) {
            $orderQuery->setOutTradeNo($query);
        } else {
            $orderQuery->setTransactionId($query);
        }
        $orderQuery->setAppId($appId);
        $orderQuery->setMchId($mchId);
        $orderQuery->setPayKey($payKey);
        return WxPayApi::orderQuery($weiXinConfig['orderQuery'], $orderQuery);
    }

    /**
     * 处理支付通知结果
     *
     * @param array $param
     * @return array
     */
    public static function notifyProcess($param = [])
    {
        if (!is_array($param) || $param['result_code'] != 'SUCCESS') {
            return ['code' => Constants::CODE_FAILED, 'message' => 'notify data exception!'];
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => 'notify success!',
            'notify' => $param,
            'data' => static::arrToXML(['return_code' => 'SUCCESS', 'return_msg' => 'OK'], true)
        ];

    }

    /**
     * 获取jsapi支付的参数
     *
     * @param $result
     * @param $payKey
     * @return array
     */
    public static function getJsApiParameters($result, $payKey)
    {
        if (!array_key_exists("appid", $result) || !array_key_exists("prepay_id", $result)
            || $result['prepay_id'] == "") {
            return ['code' => Constants::CODE_FAILED, 'message' => 'js api parameters exception!'];
        }
        $timeStamp = time();
        $weiXinJsApi = new WxPayJsApiPay();
        $weiXinJsApi->setAppId($result["appid"]);
        $weiXinJsApi->setPayKey($payKey);
        $weiXinJsApi->setTimeStamp("$timeStamp");
        $weiXinJsApi->setNonceStr(WxPayApi::getNonceStr());
        $weiXinJsApi->setPackage("prepay_id=" . $result['prepay_id']);
        $weiXinJsApi->setSignType("MD5");
        $weiXinJsApi->setPaySign($weiXinJsApi->makeSign());
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => 'js api parameters success!',
            'data' => json_encode($weiXinJsApi->getValues())
        ];
    }

    /**
     * 获取AppApi支付的参数
     *
     * @param $result
     * @param $payKey
     * @return array
     */
    public static function getAppApiParameters($result, $payKey)
    {
        if (!array_key_exists("appid", $result) || !array_key_exists("prepay_id", $result)
            || $result['prepay_id'] == "") {
            return ['code' => Constants::CODE_FAILED, 'message' => 'app api parameters exception!'];
        }
        $weiXinAppApi = new WxPayAppApiPay();
        $weiXinAppApi->setPayKey($payKey);
        $weiXinAppApi->setAppId($result["appid"]);
        $weiXinAppApi->setTimeStamp(strval(time()));
        $weiXinAppApi->setNonceStr(WxPayApi::getNonceStr());
        $weiXinAppApi->setPackage('Sign=WXPay');
        $weiXinAppApi->setPartnerId($result['mch_id']);
        $weiXinAppApi->setPrepayId($result['prepay_id']);
        $weiXinAppApi->setSignType("MD5");
        $weiXinAppApi->setPaySign($weiXinAppApi->makeSign());
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => 'app api parameters success!',
            'data' => $weiXinAppApi->getValues()
        ];
    }

    /**
     * 企业付款
     *
     * @param string $mchAppId AppId
     * @param string $mchId 商户号
     * @param string $partnerTradeNo 商户交易号
     * @param string $openId openId
     * @param string $reUserName 真实姓名
     * @param int $amount 提现金额(分)
     * @param string $desc 描述
     * @param string $sslCert 证书文件
     * @param string $sslKey 证书key
     * @return array
     */
    public static function transfers($mchAppId, $mchId, $partnerTradeNo, $openId, $reUserName, $amount, $desc, $sslCert, $sslKey)
    {
        $weiXinConfig = Yii::$app->params['weiXin'];
        $transfers = new WxPayTransfers();
        $transfers->setAppId($mchAppId);
        $transfers->setMchId($mchId);
        $transfers->setPartnerTradeNo($partnerTradeNo);
        $transfers->setOpenId($openId);
        $transfers->setReUserName($reUserName);
        $transfers->setAmount(intval($amount));
        $transfers->setDesc(isset($desc) ? $desc : '企业付款');
        return WxPayApi::transfers($weiXinConfig['transfers'], $transfers, $sslCert, $sslKey);
    }

    /**
     * 解析微信企业付款错误码
     * @param array $params
     * @return array
     */
    private static function analyzeErrCode($params = [])
    {
        switch ($params['err_code']) {
            case 'NOAUTH':
                $message = '没有权限，没有授权请求此api，请联系微信支付开通api权限!';
                break;
            case 'AMOUNT_LIMIT':
                return [
                    'code' => Constants::CODE_FAILED,
                    'message' => '提现金额不能小于最低限额，每次提现金额必须大于1元!'
                ];
                break;
            case 'PARAM_ERROR':
                $message = '参数错误，或参数格式出错，参数不合法等，请查看err_code_des，修改设置错误的参数!';
                break;
            case 'OPENID_ERROR':
                $message = 'Openid格式错误或者不属于商家公众账号，请核对商户自身公众号appid和用户在此公众号下的openid!';
                break;
            case 'NOTENOUGH':
                $message = '余额不足，帐号余额不足，请用户充值或更换支付卡后再支付!';
                break;
            case 'SYSTEMERROR':
                return [
                    'code' => Constants::CODE_FAILED,
                    'message' => '系统繁忙，请稍后再试!'
                ];
                break;
            case 'NAME_MISMATCH':
                return [
                    'code' => Constants::CODE_FAILED,
                    'message' => '姓名校验出错，填写正确的用户姓名!'
                ];
                break;
            case 'SIGN_ERROR':
                $message = '签名错误，没有按照文档要求进行签名!';
                break;
            case 'XML_ERROR':
                $message = 'Post内容出错，Post请求数据不是合法的xml格式内容!';
                break;
            case 'FATAL_ERROR':
                $message = '两次请求参数不一致！';
                break;
            case 'CA_ERROR':
                $message = '证书出错，请求没带证书或者带上了错误的证书!';
                break;
            case 'V2_ACCOUNT_SIMPLE_BAN':
                $message = '无法给非实名用户付款!';
                break;
            case 'MONEY_LIMIT':
                $message = '已经达到今日付款总额上限/已达到付款给此用户额度上限!';
                break;
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => '提现成功，零钱已入账！',
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public static function weiXinPayResults($params)
    {
        return WxPayResults::Init($params);
    }

    /**
     * 数组转XML
     *
     * @param [] $param
     * @param bool $cdata
     * @return string
     */
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
     *
     * 调用支付接口
     * @param $prepayData
     * @return array
     */
    private static function initPrepayData($prepayData)
    {
        $appData = array(
            'appid' => $prepayData['APPID'],
            'partnerid' => $prepayData['MCH_ID'],
            'prepayid' => $prepayData['PREPAY_ID'],
            'package' => 'Sign=WXPay',
            'noncestr' => static::getRandomStr(),
            'timestamp' => time(),
        );
        ksort($appData);
        $str = static::arrayToKeyValueString($appData);
        $appData['sign'] = static::getSign($str);
        ll($appData, __FUNCTION__ . '.log');
        return $appData;
    }

    /**
     * 微信支付回调
     *
     * @param $request
     * @return array|string
     */
    public static function notify($request)
    {
        // 解析XML文件
        $analyze = static::analyzeNotify($request);
        if (!is_array($analyze) || $analyze['result_code'] != 'SUCCESS') {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'notify data exception!'];
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'msg' => 'notify success!',
            'notify' => $analyze,
            'data' => static::arrToXML(['return_code' => 'SUCCESS', 'return_msg' => 'OK'], true)
        ];
    }

    /**
     * 初始化订单数据
     *
     * @param int $orderId
     * @param int $amount
     * @return string
     */
    private static function initOrderData($orderId, $amount)
    {
        $param = array(
            'appid' => static::$weiXin['APP_ID'],
            'body' => static::$weiXin['BODY'],
            'detail' => static::$weiXin['DETAIL'],
            'fee_type' => 'CNY',
            'mch_id' => static::$weiXin['MCH_ID'],
            'nonce_str' => static::getRandomStr(),
            'notify_url' => static::$weiXin['NOTIFY_URL'],
            'out_trade_no' => $orderId,
            'spbill_create_ip' => $_SERVER["REMOTE_ADDR"],
            'time_expire' => date("YmdHms", strtotime("+2 hours")),
            'time_start' => date("YmdHms"),
            'total_fee' => $amount * Constants::CENT,
            'trade_type' => 'APP',
        );
        $param['sign'] = static::getSign(static::arrayToKeyValueString($param));
        return static::arrToXML($param);
    }

//    /**
//     * 数组转XML
//     *
//     * @param [] $param
//     * @param bool $cdata
//     * @return string
//     */
//    private static function arrToXML($param, $cdata = false)
//    {
//        $xml = "<xml>";
//        $cdataPrefix = $cdataSuffix = '';
//        if ($cdata) {
//            $cdataPrefix = '<![CDATA[';
//            $cdataSuffix = ']]>';
//        }
//        foreach ($param as $key => $value) {
//            $xml .= "<{$key}>{$cdataPrefix}{$value}{$cdataSuffix}</$key>";
//        }
//        $xml .= "</xml>";
//        return $xml;
//    }

    /**
     * XML转数组
     * 数组格式 array('大写xml的tag'    =>    'xml的value');
     * 数组所有键为大写！！！-----重要！
     *
     * @param $xml
     * @return array
     */
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
     * 获取签名
     *
     * @param $signParams
     * @return string
     */
    private static function getSign($signParams = '')
    {
        return strtoupper(md5(static::joinAPI_KEY($signParams)));
    }

    /**
     * 拼接API密钥
     *
     * @param $str
     * @return string
     */
    private static function joinAPI_KEY($str)
    {
        return $str . "key=" . self::$weiXin['API_KEY'];
    }

    /**
     * 数组按照Key、value拼接成字符串
     *
     * @param $param
     * @return string
     */
    private static function arrayToKeyValueString($param)
    {
        $str = '';
        foreach ($param as $key => $value) {
            $str = $str . $key . '=' . $value . '&';
        }
        return $str;
    }

    /**
     * 随机字符串
     *
     * @return string
     */
    private static function getRandomStr()
    {
        return md5(microtime() . 'weixin' . rand(100, 9999));
    }

    /**
     * 解析微信回调通知
     *
     * @param $request
     * @return mixed
     */
    private static function analyzeNotify($request)
    {
        libxml_disable_entity_loader(true);
        return json_decode(
            json_encode(
                simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA)
            ),
            true
        );
    }
}