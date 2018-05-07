<?php

namespace app\common\services;


use app\common\components\CdnUtils;
use app\common\models\User;
use app\common\models\Video;

class UserService
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_MAX = 100;

    //更新用户位置
    public static function updateUserLocation($params)
    {
        if (!isset($params['userId']) || !isset($params['longitude']) || !isset($params['latitude'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        User::updateUserLocation($params['userId'], $params['longitude'], $params['latitude']);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => []];
    }

    /**
     * 附近的人
     *
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public static function nearby($params)
    {
        if (!isset($params['userId']) || !isset($params['longitude']) || !isset($params['latitude'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $userId = $params['userId'];
        $lng = $params['longitude'];
        $lat = $params['latitude'];
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
        $tmpUserId = '';
        $squares = self::_returnSquarePoint($lng, $lat);
        $result = User::getNearUserList($userId, $squares, $lng, $lat, $page, $pageSize);
        foreach ($result['list'] as $key => $value) {
            $tmpUserId .= $value['userId'] . ',';
            $distance = self::_getDistance($lat, $lng, $value['latitude'], $value['longitude'], 1, 0);
            if ($distance > 1000) {
                $distance = round($distance / 1000, 2);
                $result['list'][$key]["distance"] = $distance . "Km";
            } else {
                $result['list'][$key]["distance"] = $distance . "m";
            }
            $result['list'][$key]["userId"] = (int)$value['userId'];
            $result['list'][$key]["level"] = (int)$value['level'];
        }
        $liveLIst = [];
        if (!empty($tmpUserId)) {
            $sql = 'select id,userId,roomId,startTime,endTime,imgSrc,remark,isLive,viewerNum from '
                . Video::tableName() . ' where userId in(' . trim($tmpUserId, ',') . ') and isLive = 1';
            $liveLIst = Video::queryBySQLCondition($sql);
        }
        foreach ($result['list'] as $key => $value) {
            $flag = true;
            foreach ($liveLIst as $itemKey => $itemValue) {
                if ($value['userId'] == $itemValue['userId']) {
                    $result['list'][$key]['imgSrc'] = $itemValue['imgSrc'];
                    $result['list'][$key]['title'] = $itemValue['remark'];
                    $result['list'][$key]['isLive'] = $itemValue['isLive'];
                    $result['list'][$key]['startTime'] = $itemValue['startTime'];
                    $result['list'][$key]['pullRtmp'] = CdnUtils::getPullUrl($itemValue['id']);
                    $result['list'][$key]['viewerNum'] = $itemValue['viewerNum'];
                    $result['list'][$key]['roomId'] = $itemValue['id'];
                    $flag = false;
                }
            }
            if ($flag) {
                $result['list'][$key]['imgSrc'] = '';
                $result['list'][$key]['title'] = '';
                $result['list'][$key]['isLive'] = "0";
                $result['list'][$key]['pullRtmp'] = '';
                $result['list'][$key]['viewerNum'] = "0";
                $result['list'][$key]['roomId'] = '0';
            }
        }
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $result];
    }

    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     * @param lng float 经度
     * @param lat float 纬度
     * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     * @return array 正方形的四个点的经纬度坐标
     */
    private static function _returnSquarePoint($lng, $lat, $distance = 60)
    {
        $dlng = 2 * asin(sin($distance / (2 * Constants::EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance / Constants::EARTH_RADIUS;
        $dlat = rad2deg($dlat);
        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }

    /**
     * 计算两点地理坐标之间的距离
     * @param  Decimal $longitude1 起点经度
     * @param  Decimal $latitude1 起点纬度
     * @param  Decimal $longitude2 终点经度
     * @param  Decimal $latitude2 终点纬度
     * @param  Int $unit 单位 1:米 2:公里
     * @param  Int $decimal 精度 保留小数位数
     * @return Decimal
     */
    private static function _getDistance($latitude1, $longitude1, $latitude2, $longitude2, $unit = 2, $decimal = 2)
    {
        $PI = pi();
        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * Constants::EARTH_RADIUS * 1000;

        if ($unit == 2) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }

    //人气
    public static function hot($params)
    {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
        $result = User::getHotList($page, $pageSize);
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]["userId"] = (int)$value['userId'];
            $result['list'][$key]["level"] = (int)$value['level'];
        }
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $result];
    }

    public static function profile($userId)
    {

    }
}