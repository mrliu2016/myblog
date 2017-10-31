<?php

namespace app\common\components;

use app\common\extensions\WeiXinPay\Lib\WxPayResults;
use app\common\extensions\WeiXinPay\Lib\WxPayTransfers;
use app\common\extensions\WeiXinPay\Lib\WxPayUnifiedOrder;
use app\common\extensions\WeiXinPay\Lib\WxPayApi;
use app\common\extensions\WeiXinPay\Lib\WxPayOrderQuery;
use app\common\extensions\WeiXinPay\Lib\WxPayJsApiPay;
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
            return ['code' => Constants::CODE_FAILED, 'msg' => $wxPayApi['msg']];
        }
        $result = static::getJsApiParameters($wxPayApi['data'], $payKey);
        return [
            'code' => ($result['code'] == Constants::CODE_FAILED) ? Constants::CODE_FAILED : Constants::CODE_SUCCESS,
            'msg' => $result['msg'],
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
            return ['code' => Constants::CODE_FAILED, 'msg' => 'notify data exception!'];
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'msg' => 'notify success!',
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
            return ['code' => Constants::CODE_FAILED, 'msg' => 'js api parameters exception!'];
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
            'msg' => 'js api parameters success!',
            'data' => json_encode($weiXinJsApi->getValues())
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
                $msg = '没有权限，没有授权请求此api，请联系微信支付开通api权限!';
                break;
            case 'AMOUNT_LIMIT':
                return [
                    'code' => Constants::CODE_FAILED,
                    'msg' => '提现金额不能小于最低限额，每次提现金额必须大于1元!'
                ];
                break;
            case 'PARAM_ERROR':
                $msg = '参数错误，或参数格式出错，参数不合法等，请查看err_code_des，修改设置错误的参数!';
                break;
            case 'OPENID_ERROR':
                $msg = 'Openid格式错误或者不属于商家公众账号，请核对商户自身公众号appid和用户在此公众号下的openid!';
                break;
            case 'NOTENOUGH':
                $msg = '余额不足，帐号余额不足，请用户充值或更换支付卡后再支付!';
                break;
            case 'SYSTEMERROR':
                return [
                    'code' => Constants::CODE_FAILED,
                    'msg' => '系统繁忙，请稍后再试!'
                ];
                break;
            case 'NAME_MISMATCH':
                return [
                    'code' => Constants::CODE_FAILED,
                    'msg' => '姓名校验出错，填写正确的用户姓名!'
                ];
                break;
            case 'SIGN_ERROR':
                $msg = '签名错误，没有按照文档要求进行签名!';
                break;
            case 'XML_ERROR':
                $msg = 'Post内容出错，Post请求数据不是合法的xml格式内容!';
                break;
            case 'FATAL_ERROR':
                $msg = '两次请求参数不一致！';
                break;
            case 'CA_ERROR':
                $msg = '证书出错，请求没带证书或者带上了错误的证书!';
                break;
            case 'V2_ACCOUNT_SIMPLE_BAN':
                $msg = '无法给非实名用户付款!';
                break;
            case 'MONEY_LIMIT':
                $msg = '已经达到今日付款总额上限/已达到付款给此用户额度上限!';
                break;
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'msg' => '提现成功，零钱已入账！',
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
}