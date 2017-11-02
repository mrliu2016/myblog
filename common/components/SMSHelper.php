<?php

namespace app\common\components;


class SMSHelper
{
    const APP_CODE = '0d961a34dffa40df935b04b6bde61fe0';
    const SIGN = '三体云';

    /**
     * 使用方法 https://market.aliyun.com/products/57002003/cmapi011900.html
     * @param $service 短信验证码
     * @param $phone   目标手机号
     * @param string   模板CODE
     * @return mixed
     */
    public static function send($code, $phone, $templateCode = 'SMS_107825044')
    {
        $paramString['service'] = $code;
        $host = "http://sms.market.alicloudapi.com";
        $path = "/singleSendSms";
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . self::APP_CODE);

        $phoneList = explode(',', $phone);
        foreach ($phoneList as $phone) {
            $querys = 'ParamString=' . urlencode(json_encode($paramString)) . '&RecNum=' . $phone . '&SignName=' . urlencode(self::SIGN) . '&TemplateCode=' . urlencode($templateCode);
            $url = $host . $path . "?" . $querys;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, true);
            if (1 == strpos("$" . $host, "https://")) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            ll($url, 'serverHeartDiskWarning.log');
            $res = curl_exec($curl);
        }
        return $res;
    }
}
