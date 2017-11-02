<?php

namespace app\common\services;

use app\common\models\Gift;

class GiftService
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_MAX = 100;

    public static function getGiftList($params)
    {
        $params['defaultPageSize'] = self::PAGE_SIZE_MAX;
        $list = Gift::queryInfo($params);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $list];
    }
}