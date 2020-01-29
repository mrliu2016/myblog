<?php

namespace app\common\extensions\WeiXinPay\Lib;

class WxPayTransfers extends WxPayDataBase
{

    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function setAppId($value)
    {
        $this->values['mch_appid'] = $value;
    }

    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function getAppId()
    {
        return $this->values['mch_appid'];
    }

    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function isAppIdSet()
    {
        return array_key_exists('mch_appid', $this->values);
    }


    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function setMchId($value)
    {
        $this->values['mchid'] = $value;
    }

    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function getMchId()
    {
        return $this->values['mchid'];
    }

    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function isMchIdSet()
    {
        return array_key_exists('mchid', $this->values);
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
     * 设置APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
     * @param string $value
     **/
    public function setSpbillCreateIp($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }

    /**
     * 获取APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。的值
     * @return 值
     **/
    public function getSpbillCreateIp()
    {
        return $this->values['spbill_create_ip'];
    }

    /**
     * 判断APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。是否存在
     * @return true 或 false
     **/
    public function isSpbillCreateIpSet()
    {
        return array_key_exists('spbill_create_ip', $this->values);
    }

    /**
     * 设置trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。
     * @param string $value
     **/
    public function setOpenId($value)
    {
        $this->values['openid'] = $value;
    }

    /**
     * 获取trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。 的值
     * @return 值
     **/
    public function getOpenid()
    {
        return $this->values['openid'];
    }

    /**
     * 判断trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。 是否存在
     * @return true 或 false
     **/
    public function IsOpenidSet()
    {
        return array_key_exists('openid', $this->values);
    }

    /**
     * 金额
     * @param $value
     */
    public function setAmount($value)
    {
        $this->values['amount'] = $value;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->values['amount'];
    }

    /**
     * @return bool
     */
    public function isAmountSet()
    {
        return array_key_exists('amount', $this->values);
    }

    /**
     * @param $value
     */
    public function setPartnerTradeNo($value)
    {
        $this->values['partner_trade_no'] = $value;
    }

    /**
     * @return mixed
     */
    public function getPartnerTradeNo()
    {
        return $this->values['partner_trade_no'];
    }

    /**
     * @return bool
     */
    public function isPartnerTradeNoSet()
    {
        return array_key_exists('partner_trade_no', $this->values);
    }

    /**
     * @param $value
     */
    public function setReUserName($value)
    {
        $this->values['re_user_name'] = $value;
    }

    /**
     * @return mixed
     */
    public function getReUserName()
    {
        return $this->values['re_user_name'];
    }

    /**
     * @return bool
     */
    public function isReUserNameSet()
    {
        return array_key_exists('re_user_name', $this->values);
    }

    /**
     * @param $value
     */
    public function setDesc($value)
    {
        $this->values['desc'] = $value;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->values['desc'];
    }

    /**
     * @return bool
     */
    public function isDescSet()
    {
        return array_key_exists('desc', $this->values);
    }

    /**
     * 真实姓名
     * @param $value
     */
    public function setCheckName($value)
    {
        $this->values['check_name'] = $value;
    }

    /**
     * @return mixed
     */
    public function getCheckName()
    {
        return $this->values['check_name'];
    }

    /**
     * @return bool
     */
    public function isCheckNameSet()
    {
        return array_key_exists('check_name', $this->values);
    }
}