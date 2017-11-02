<?php

namespace app\common\services;

use app\common\models\Follow;

class FollowService
{
    public static function attention($params)
    {
        if (!isset($params['userId']) || !isset($params['userIdFollow'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        Follow::attention($params['userId'], $params['userIdFollow']);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => []];
    }
}