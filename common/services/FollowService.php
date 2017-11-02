<?php

namespace app\common\services;

use app\common\models\Follow;

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

    public static function getUserFollowList($params)
    {
        if (!isset($params['userId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $list = Follow::queryInfo($params);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $list];
    }
}