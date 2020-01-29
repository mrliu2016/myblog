<?php

namespace app\common\services;

class Base64JsonConvert
{

    /**
     * base64字符串解码为json，返回数据
     * @param string $params
     * @return mixed
     */
    public static function base64ToJsonDecode($params = '')
    {
        return json_decode(base64_decode($params), true);
    }

    /**
     * $params 编码为json，再进行base64编码
     * @param $params
     * @return string
     */
    public static function jsonToBase64Encode($params)
    {
        return base64_encode(json_encode($params));
    }

    /**
     * json字符串解码，并检测json串合法性
     *
     * @param $string
     * @param bool $isArray
     * @return array|mixed
     */
    public static function jsonDecode($string, $isArray = true)
    {
        $result = json_decode($string, $isArray);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            default:
                $result = [];
                break;
        }
        return $result;
    }
}