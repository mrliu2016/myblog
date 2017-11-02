<?php

namespace app\common\services;


use app\common\models\User;

class UserService
{
    //更新用户位置
    public static function updateUserLocation($params){
        if (!isset($params['userId']) || !isset($params['longitude']) || !isset($params['latitude'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        User::updateUserLocation($params['userId'], $params['longitude'], $params['latitude']);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => []];
    }
}