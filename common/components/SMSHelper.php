<?php

namespace app\common\components;


use yii\base\Exception;

class SMSHelper
{
    const APP_CODE = '0d961a34dffa40df935b04b6bde61fe0';
    const SIGN = '无双110';

    /**
     * 使用方法 https://market.aliyun.com/products/57002003/cmapi011900.html
     * @param string $service 服务名称
     * @param string $message 告警信息
     * @param string $phone   目标手机号
     * @param string   模板CODE
     * @return mixed
     */
    public static function send($service, $message, $phone, $templateCode = 'SMS_94755028')
    {
        return static::sendNew($service, $message, $phone);
    }

    /*
     * 阿里云短信服务器迁移后调用
     *
     * */
    public static function sendNew($service, $message, $phone, $templateCode = 'SMS_94755028')
    {
        $params = array();
        $accessKeyId = "LTAI7qIs9avI22uS";
        $accessKeySecret = "Rvp7DHSwwcMIEREFTSrrk3PGXXM9ZS";

        $content = null;
        $phoneList = explode(',', $phone);
        foreach ($phoneList as $phone) {

            $params["PhoneNumbers"] = $phone;
            $params["SignName"] = "无双110";
            $params["TemplateCode"] = $templateCode;
            $params['TemplateParam'] = Array(
                "service" => $service,
                "message" => $message
            );
            if (!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
                $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
            }
            $helper = new SignatureHelper();
            try {
                // 此处可能会抛出异常，注意catch
                $content = $helper->request(
                    $accessKeyId,
                    $accessKeySecret,
                    "dysmsapi.aliyuncs.com",
                    array_merge($params, array(
                        "RegionId" => "cn-hangzhou",
                        "Action" => "SendSms",
                        "Version" => "2017-05-25",
                    ))
                );
            } catch (Exception $ex) {
                $content = 'issue';
            }
        }
        return $content;
    }

    /**
     * 阿里云短信服务器迁移后调用
     *
     * @param $service
     * @param $phone
     * @param string $templateCode
     * @return bool|null|\stdClass|string
     */
    public static function sendCaptcha($service, $phone, $templateCode = 'SMS_107825044')
    {
        $params = array();
        $accessKeyId = "LTAI7qIs9avI22uS";
        $accessKeySecret = "Rvp7DHSwwcMIEREFTSrrk3PGXXM9ZS";

        $content = null;
        $phoneList = explode(',', $phone);
        foreach ($phoneList as $phone) {
            $params["PhoneNumbers"] = $phone;
            $params["SignName"] = "三体云";
            $params["TemplateCode"] = $templateCode;
            $params['TemplateParam'] = Array(
                "service" => $service,
            );
            if (!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
                $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
            }
            $helper = new SignatureHelper();
            try {
                // 此处可能会抛出异常，注意catch
                $content = $helper->request(
                    $accessKeyId,
                    $accessKeySecret,
                    "dysmsapi.aliyuncs.com",
                    array_merge($params, array(
                        "RegionId" => "cn-hangzhou",
                        "Action" => "SendSms",
                        "Version" => "2017-05-25",
                    ))
                );
            } catch (\Exception $ex) {
                $content = 'issue';
            }
        }
        return $content;
    }
}
