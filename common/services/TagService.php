<?php

namespace app\common\services;

use app\common\models\UserTag;

class TagService
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_MAX = 100;

    public static function getUserTagList($params)
    {
        if (!isset($params['userId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $params['defaultPageSize'] = self::PAGE_SIZE_MAX;
        $list = UserTag::queryInfo($params);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $list];
    }
}