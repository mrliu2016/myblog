<?php

namespace app\common\services;

use app\common\models\Follow;
use app\common\models\User;

class FollowService
{
    const PAGE_SIZE = 10;

    public static function attention($params)
    {
        if (!isset($params['userId']) || !isset($params['userIdFollow'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        Follow::attention($params['userId'], $params['userIdFollow']);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => []];
    }

    public static function cancelAttention($params)
    {
        if (!isset($params['userId']) || !isset($params['userIdFollow'])) {
            return false;
        }
        return Follow::updateChannelAttention($params['userId'], $params['userIdFollow']);
    }


    public static function getUserFollowList($params)
    {
        if (!isset($params['userId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $size = isset($params['pageSize']) ? (int)$params['pageSize'] : self::PAGE_SIZE;
        $params['defaultPageSize'] = $size;
        $list = array();
        $result = Follow::queryInfo($params);
        foreach ($result as $key => $value) {
            $item = array();
            $item["userId"] = (int)$value['userIdFollow'];
            $user = User::queryById($value['userIdFollow']);
            $item["avatar"] = $user['avatar'];
            $item["nickName"] = $user['nickName'];
            $item["level"] = (int)$user['level'];
            $item["description"] = $user['description'];
            $item["updated"] = date('Y-m-d H:i:s',$value['updated']);
            $list[] = $item;
        }
        $total_cnt = (int)Follow::queryInfoNum($params);;
        $page_cnt = ceil($total_cnt / $size);
        $data = compact('total_cnt', 'page', 'size', 'page_cnt', 'list');
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }
}