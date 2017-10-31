<?php

namespace app\common\components;

use app\common\services\Constants;

class ParseUtils
{
    //解析url参数
    public static function parseQuery($query)
    {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            if (!empty($param)) {
                $item = explode('=', $param);
                $params[$item[0]] = $item[1];
            }
        };
        return $params;
    }

    //解析微信status
    public static function parseStatus($state)
    {
        $state = urldecode($state);
        $state = str_replace("__", "=", $state);
        $state = str_replace("--", "&", $state);
        return self::parseQuery($state);
    }

    //根据查询条件返回跳转地址
    public static function parseUrl($host, $query)
    {
        if (!empty($query['flag'])) {
            if ($query['flag'] == Constants::FLAG_USER_PROFILE) {
                return $host . '/h5/personal-center?timestamp=' . time();
            }
            if ($query['flag'] == Constants::FLAG_USER_TASK) {
                return $host . '/h5/my-task?timestamp=' . time();
            }
            if ($query['flag'] == Constants::FLAG_TASK_CREATE) {
                return $host . '/h5/createtask?aid=' . $query['aid'];
            }
            if ($query['flag'] == Constants::FLAG_TASK_JOIN) {
                return $host . '/h5/join-task?taskid=' . $query['taskid'];
            }
            if ($query['flag'] == Constants::FLAG_GRADE) {
                return $host . '/h5/grade?aid=' . $query['aid'] . '&tid=' . $query['tid'];
            }
        }
        return $host;
    }
}
