<?php

namespace app\common\services;

use app\common\models\UserTag;

class TagService
{
    public static function getUserTagList($params)
    {
        if (!isset($params['userId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $params['defaultPageSize'] = 100;
        $list = UserTag::queryInfo($params);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $list];
    }
}