<?php

namespace app\common\services;


use app\common\models\UserInfo;

class UserService
{
    public static function userList($params, $applicationMap)
    {
        $result = UserInfo::queryInfo($params);
        $roleMap = Constants::roleMap();
        foreach ($result as &$item) {
            $item['applicationName'] = '';
            if (!empty($applicationMap[$item['applicationId']])) {
                $item['applicationName'] = $applicationMap[$item['applicationId']];
            }
            $item['roleName'] = '';
            if (!empty($roleMap[$item['role']])) {
                $item['roleName'] = $roleMap[$item['role']];
            }
        }
        return $result;
    }

    public static function userCount($params)
    {
        return UserInfo::queryInfoNum($params);
    }

    public static function checkName($applicationId, $name)
    {
        return empty(UserInfo::queryUserName($applicationId, $name)) ? true : false;
    }
}