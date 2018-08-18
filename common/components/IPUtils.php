<?php

namespace app\common\components;

class IPUtils
{
    public static $ip = null;

    public static function is_ip($ip)
    {
        $arr = explode('.', $ip);
        if (count($arr) != 4) {
            return false;
        }
        if ($arr[0] == 10) {
            return false;
        }
        for ($i = 0; $i < 4; $i++) {
            if (!is_numeric($arr[$i]) || $arr[$i] < 0 || $arr[$i] > 255) {
                return false;
            }
        }
        return true;
    }

    //use shell to get my ip address
    public static function get_local_ip()
    {
        static $local_ip;
        if (!isset ($local_ip)) {
            $local_ip = @file_get_contents('/tmp/local_ip');
            if (!empty($local_ip)) {
                $local_ip = trim($local_ip);
            }
            if (empty($local_ip) || !self::is_ip($local_ip)) {
                $local_ip = AHelper::curl_get('http://members.3322.org/dyndns/getip', 3);
                $local_ip = trim($local_ip);
                if (self::is_ip($local_ip)) {
                    file_put_contents('/tmp/local_ip', $local_ip);
                }
            }
            if (empty($local_ip)) {
                $local_ip = system('/sbin/ifconfig eth0 | grep "inet addr"|awk \'{print $2}\'|sed -e "s/^.*://g"');
                if (!self::is_ip($local_ip)) {
                    $local_ip = system('/sbin/ifconfig eth1 | grep "inet addr"|awk \'{print $2}\'|sed -e "s/^.*://g"');
                }
                if (!self::is_ip($local_ip)) {
                    $local_ip = system('/sbin/ifconfig eth0 | grep "inet"|awk \'{print $2}\'|sed -e "s/^.*://g"');
                }
                if (!self::is_ip($local_ip)) {
                    $local_ip = system('/sbin/ifconfig eth1 | grep "inet"|awk \'{print $2}\'|sed -e "s/^.*://g"');
                }
            }
        }
        return self::is_ip($local_ip) ? $local_ip : false;
    }

    /**
     * 获取服务器 IP 地址
     *
     * @return null
     */
    public static function getServerIP()
    {
        if (empty(static::$ip)) {
            static::$ip = static::get_local_ip();
        }
        return static::$ip;
    }
}