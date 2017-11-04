<?php
/**
 * Created by PhpStorm.
 * User: 左撇子
 * Date: 2017/11/1/001
 * Time: 15:17
 */

namespace app\common\components;

class Token
{

    /**
     * 随机生成token.
     *
     * @param $salt
     *
     * @return string
     */
    public static function generateToken($salt)
    {
        return md5(md5(static::generateRandomString(10)) . $salt);
    }

    /**
     * 生成指定长度的随机字符串.
     *
     * @param int $length
     *
     * @return string
     */
    private static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_len = strlen($characters);
        $random_str = '';
        for ($i = 0; $i < $length; ++$i) {
            $random_str .= $characters[rand(0, $characters_len - 1)];
        }
        return $random_str;
    }

    //随机生成验证码
    public static function code()
    {
        $length = 6;
        $table = '0123456789';
        $code = '';
        if ($length <= 0 || empty($table)) {
            return $code;
        }
        $max_size = strlen($table) - 1;
        while ($length-- > 0) {
            $code .= $table[rand(0, $max_size)];
        }
        return $code;
    }
}