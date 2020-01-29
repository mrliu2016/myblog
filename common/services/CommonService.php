<?php

namespace app\common\services;

class CommonService
{
    /**
     * 通话质量，构建时间映射
     * @author lhz
     * @date 2019.11.11
     * @param $interval :2 时间间隔2秒
     * 将开始时间和结束时间，都按照偶数处理
     */
    public static function buildTimeMap($startTime, $endTime, $interval = 86400, $format = 'Y-m-d')
    {
        $timeMap = [];
        while ($startTime <= $endTime) {
            $timeMap[date($format, $startTime)] = 0;
            $startTime += $interval;
        }
        return $timeMap;
    }


}