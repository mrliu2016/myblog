<?php

namespace app\common\extensions\WeiXinPay\Lib;

/**
 *
 * 提交被扫输入对象
 * @author widyhu
 *
 */
class WxPayMicroPay extends WxPayDataBase
{
    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function setAppId($value)
    {
        $this->values['appid'] = $value;
    }

    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function getAppId()
    {
        return $this->values['appid'];
    }

    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function isAppIdSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function setMchId($value)
    {
        $this->values['mch_id'] = $value;
    }

    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function getMchId()
    {
        return $this->values['mch_id'];
    }

    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function isMchIdSet()
    {
        return array_key_exists('mch_id', $this->values);
    }


    /**
     * 设置终端设备号(商户自定义，如门店编号)
     * @param string $value
     **/
    public function setDeviceInfo($value)
    {
        $this->values['device_info'] = $value;
    }

    /**
     * 获取终端设备号(商户自定义，如门店编号)的值
     * @return 值
     **/
    public function getDeviceInfo()
    {
        return $this->values['device_info'];
    }

    /**
     * 判断终端设备号(商户自定义，如门店编号)是否存在
     * @return true 或 false
     **/
    public function isDeviceInfoSet()
    {
        return array_key_exists('device_info', $this->values);
    }


    /**
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->values['nonce_str'] = $value;
    }

    /**
     * 获取随机字符串，不长于32位。推荐随机数生成算法的值
     * @return 值
     **/
    public function getNonceStr()
    {
        return $this->values['nonce_str'];
    }

    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function isNonceStrSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置商品或支付单简要描述
     * @param string $value
     **/
    public function setBody($value)
    {
        $this->values['body'] = $value;
    }

    /**
     * 获取商品或支付单简要描述的值
     * @return 值
     **/
    public function getBody()
    {
        return $this->values['body'];
    }

    /**
     * 判断商品或支付单简要描述是否存在
     * @return true 或 false
     **/
    public function IsBodySet()
    {
        return array_key_exists('body', $this->values);
    }


    /**
     * 设置商品名称明细列表
     * @param string $value
     **/
    public function setDetail($value)
    {
        $this->values['detail'] = $value;
    }

    /**
     * 获取商品名称明细列表的值
     * @return 值
     **/
    public function getDetail()
    {
        return $this->values['detail'];
    }

    /**
     * 判断商品名称明细列表是否存在
     * @return true 或 false
     **/
    public function IsDetailSet()
    {
        return array_key_exists('detail', $this->values);
    }


    /**
     * 设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     * @param string $value
     **/
    public function setAttach($value)
    {
        $this->values['attach'] = $value;
    }

    /**
     * 获取附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据的值
     * @return 值
     **/
    public function GetAttach()
    {
        return $this->values['attach'];
    }

    /**
     * 判断附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据是否存在
     * @return true 或 false
     **/
    public function IsAttachSet()
    {
        return array_key_exists('attach', $this->values);
    }


    /**
     * 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
     * @param string $value
     **/
    public function setOutTradeNo($value)
    {
        $this->values['out_trade_no'] = $value;
    }

    /**
     * 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
     * @return 值
     **/
    public function GetOut_trade_no()
    {
        return $this->values['out_trade_no'];
    }

    /**
     * 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
     * @return true 或 false
     **/
    public function isOutTradeNoSet()
    {
        return array_key_exists('out_trade_no', $this->values);
    }


    /**
     * 设置订单总金额，单位为分，只能为整数，详见支付金额
     * @param string $value
     **/
    public function setTotalFee($value)
    {
        $this->values['total_fee'] = $value;
    }

    /**
     * 获取订单总金额，单位为分，只能为整数，详见支付金额的值
     * @return 值
     **/
    public function GetTotal_fee()
    {
        return $this->values['total_fee'];
    }

    /**
     * 判断订单总金额，单位为分，只能为整数，详见支付金额是否存在
     * @return true 或 false
     **/
    public function IsTotal_feeSet()
    {
        return array_key_exists('total_fee', $this->values);
    }


    /**
     * 设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @param string $value
     **/
    public function setFeeType($value)
    {
        $this->values['fee_type'] = $value;
    }

    /**
     * 获取符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型的值
     * @return 值
     **/
    public function getFeeType()
    {
        return $this->values['fee_type'];
    }

    /**
     * 判断符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型是否存在
     * @return true 或 false
     **/
    public function IsFee_typeSet()
    {
        return array_key_exists('fee_type', $this->values);
    }


    /**
     * 设置调用微信支付API的机器IP
     * @param string $value
     **/
    public function setSpbillCreateIp($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }

    /**
     * 获取调用微信支付API的机器IP 的值
     * @return 值
     **/
    public function getSpbillCreateIp()
    {
        return $this->values['spbill_create_ip'];
    }

    /**
     * 判断调用微信支付API的机器IP 是否存在
     * @return true 或 false
     **/
    public function isSpbillCreateIpSet()
    {
        return array_key_exists('spbill_create_ip', $this->values);
    }


    /**
     * 设置订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则
     * @param string $value
     **/
    public function setTimeStart($value)
    {
        $this->values['time_start'] = $value;
    }

    /**
     * 获取订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则的值
     * @return 值
     **/
    public function GetTime_start()
    {
        return $this->values['time_start'];
    }

    /**
     * 判断订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。详见时间规则是否存在
     * @return true 或 false
     **/
    public function IsTime_startSet()
    {
        return array_key_exists('time_start', $this->values);
    }


    /**
     * 设置订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。详见时间规则
     * @param string $value
     **/
    public function setTimeExpire($value)
    {
        $this->values['time_expire'] = $value;
    }

    /**
     * 获取订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。详见时间规则的值
     * @return 值
     **/
    public function GetTime_expire()
    {
        return $this->values['time_expire'];
    }

    /**
     * 判断订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。详见时间规则是否存在
     * @return true 或 false
     **/
    public function IsTime_expireSet()
    {
        return array_key_exists('time_expire', $this->values);
    }


    /**
     * 设置商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
     * @param string $value
     **/
    public function setGoodsTag($value)
    {
        $this->values['goods_tag'] = $value;
    }

    /**
     * 获取商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠的值
     * @return 值
     **/
    public function GetGoods_tag()
    {
        return $this->values['goods_tag'];
    }

    /**
     * 判断商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠是否存在
     * @return true 或 false
     **/
    public function IsGoods_tagSet()
    {
        return array_key_exists('goods_tag', $this->values);
    }


    /**
     * 设置扫码支付授权码，设备读取用户微信中的条码或者二维码信息
     * @param string $value
     **/
    public function SetAuth_code($value)
    {
        $this->values['auth_code'] = $value;
    }

    /**
     * 获取扫码支付授权码，设备读取用户微信中的条码或者二维码信息的值
     * @return 值
     **/
    public function GetAuth_code()
    {
        return $this->values['auth_code'];
    }

    /**
     * 判断扫码支付授权码，设备读取用户微信中的条码或者二维码信息是否存在
     * @return true 或 false
     **/
    public function IsAuth_codeSet()
    {
        return array_key_exists('auth_code', $this->values);
    }
}