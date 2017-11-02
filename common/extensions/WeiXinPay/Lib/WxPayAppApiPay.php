<?php

namespace app\common\extensions\WeiXinPay\Lib;

/**
 *
 * 提交APP输入对象
 * @author widyhu
 *
 */
class WxPayAppApiPay extends WxPayDataBase
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
     * 设置支付时间戳
     * @param string $value
     **/
    public function setTimeStamp($value)
    {
        $this->values['timestamp'] = $value;
    }

    /**
     * 获取支付时间戳的值
     * @return 值
     **/
    public function GetTimeStamp()
    {
        return $this->values['timestamp'];
    }

    /**
     * 判断支付时间戳是否存在
     * @return true 或 false
     **/
    public function IsTimeStampSet()
    {
        return array_key_exists('timestamp', $this->values);
    }

    /**
     * 随机字符串
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->values['noncestr'] = $value;
    }

    /**
     * 获取notify随机字符串值
     * @return 值
     **/
    public function GetReturn_code()
    {
        return $this->values['noncestr'];
    }

    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function IsReturn_codeSet()
    {
        return array_key_exists('noncestr', $this->values);
    }


    /**
     * 设置订单详情扩展字符串
     * @param string $value
     **/
    public function setPackage($value)
    {
        $this->values['package'] = $value;
    }

    /**
     * 获取订单详情扩展字符串的值
     * @return 值
     **/
    public function GetPackage()
    {
        return $this->values['package'];
    }

    /**
     * 判断订单详情扩展字符串是否存在
     * @return true 或 false
     **/
    public function IsPackageSet()
    {
        return array_key_exists('package', $this->values);
    }

    /**
     * 设置签名方式
     * @param string $value
     **/
    public function setSignType($value)
    {
        $this->values['signType'] = $value;
    }

    /**
     * 获取签名方式
     * @return 值
     **/
    public function GetSignType()
    {
        return $this->values['signType'];
    }

    /**
     * 判断签名方式是否存在
     * @return true 或 false
     **/
    public function IsSignTypeSet()
    {
        return array_key_exists('signType', $this->values);
    }

    /**
     * 设置签名方式
     * @param string $value
     **/
    public function setPaySign($value)
    {
        $this->values['sign'] = $value;
    }

    /**
     * 获取签名方式
     * @return 值
     **/
    public function GetPaySign()
    {
        return $this->values['sign'];
    }

    /**
     * 判断签名方式是否存在
     * @return true 或 false
     **/
    public function IsPaySignSet()
    {
        return array_key_exists('sign', $this->values);
    }

    /**
     * 设置商户号方式
     * @param string $value
     **/
    public function setPartnerId($value)
    {
        $this->values['partnerid'] = $value;
    }

    /**
     * 获取商户号方式
     * @return 值
     **/
    public function getPartnerId()
    {
        return $this->values['partnerid'];
    }

    /**
     * 判断商户号方式是否存在
     * @return true 或 false
     **/
    public function isPartnerIdSet()
    {
        return array_key_exists('partnerid', $this->values);
    }

    /**
     * 设置预支付交易会话ID方式
     * @param string $value
     **/
    public function setPrepayId($value)
    {
        $this->values['prepayid'] = $value;
    }

    /**
     * 获取预支付交易会话ID方式
     * @return 值
     **/
    public function getPrepayId()
    {
        return $this->values['prepayid'];
    }

    /**
     * 判断预支付交易会话ID方式是否存在
     * @return true 或 false
     **/
    public function isPrepayIdSet()
    {
        return array_key_exists('prepayid', $this->values);
    }
}