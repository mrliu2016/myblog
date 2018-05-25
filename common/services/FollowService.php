<?php

namespace app\common\services;

use app\common\components\CdnUtils;
use app\common\extensions\OSS\Model\CnameConfig;
use app\common\models\Follow;
use app\common\models\User;
use app\common\models\Video;

class FollowService
{
    const PAGE_SIZE = 10;

    /**
     * 关注
     *
     * @param $params
     * @return array
     */
    public static function attention($params)
    {
        if (!isset($params['userId']) || !isset($params['userIdFollow'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => '系统繁忙，请稍后重试！'];
        }
        Follow::attention($params['userId'], $params['userIdFollow']);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => []];
    }

    /**
     * 取消关注
     *
     * @param $params
     * @return bool
     */
    public static function cancelAttention($params)
    {
        if (!isset($params['userId']) || !isset($params['userIdFollow'])) {
            return false;
        }
        return Follow::updateChannelAttention($params['userId'], $params['userIdFollow']);
    }

    /**
     * 关注列表
     *
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getUserFollowList($params)
    {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $size = isset($params['pageSize']) ? (int)$params['pageSize'] : self::PAGE_SIZE;
        $list = [];
        $liveLIst = [];
        $result = Follow::getUserFollowLive($params['userId'], $page, $size);
        $userId = '';
        foreach ($result as $key => $value) {
            $userId .= $value['userIdFollow'] . ',';
            $item = array();
            $item["userId"] = (int)$value['userIdFollow'];
            $user = User::queryById($value['userIdFollow']);
            $item["avatar"] = $user['avatar'];
            $item["nickName"] = $user['nickName'];
            $item["level"] = (int)$user['level'];
            $item["description"] = $user['description'];
            $item["updated"] = $value['updated'];
            $item["roomId"] = $value['roomId'];
            $item["imgSrc"] = $value['imgSrc'];
            $item["title"] = $value['title'];
            $item["isLive"] = $value['isLive'];
            $item['startTime'] = date('Y.m.d H:i', $value['startTime']);
            $item['viewerNum'] = $value['viewerNum'];
            $item['pullRtmp'] = CdnUtils::getPullUrl($value['streamId']);
            $list[] = $item;
        }
//        if (!empty($userId)) {
//            $sql = 'select id,userId,roomId,startTime,endTime,imgSrc,remark,isLive,viewerNum from '
//                . Video::tableName() . ' where userId in(' . trim($userId, ',') . ') and isLive = 1';
//            $liveLIst = Video::queryBySQLCondition($sql);
//        }
//
//        foreach ($list as $key => $value) {
//            $flag = true;
//            foreach ($liveLIst as $itemKey => $itemValue) {
//                if ($value['userId'] == $itemValue['userId']) {
//                    $list[$key]['imgSrc'] = $itemValue['imgSrc'];
//                    $list[$key]['title'] = $itemValue['remark'];
//                    $list[$key]['isLive'] = $itemValue['isLive'];
//                    $list[$key]['startTime'] = $itemValue['startTime'];
//                    $list[$key]['pullRtmp'] = CdnUtils::getPullUrl($itemValue['id']);
//                    $list[$key]['viewerNum'] = $itemValue['viewerNum'];
//                    $list[$key]['roomId'] = $itemValue['id'];
//                    $flag = false;
//                }
//            }
//            if ($flag) {
//                $list[$key]['imgSrc'] = '';
//                $list[$key]['title'] = '';
//                $list[$key]['isLive'] = "0";
//                $list[$key]['pullRtmp'] = '';
//                $list[$key]['viewerNum'] = "0";
//                $list[$key]['roomId'] = $itemValue['id'];
//            }
//        }

        $total_cnt = (int)Follow::followLiveCount($params['userId']);;
        $page_cnt = ceil($total_cnt / $size);
        $data = compact('total_cnt', 'page', 'size', 'page_cnt', 'list');
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }
}