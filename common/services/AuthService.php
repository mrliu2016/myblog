<?php

namespace app\common\services;

use app\common\models\ApplicationInfo;

class AuthService
{
    const TIMEOUT = 6000;
    const CODE_SUCCESS = 0;
    const CODE_ERROR_MISS_PARAMS = -1;
    const CODE_ERROR_SIGN = -2;
    const CODE_ERROR_DISABLE = -3;
    const CODE_ERROR_TIMEOUT = -4;

    /*
     * app 鉴权
     * */
    public static function check($appKey, $timestamp, $sign, $inner)
    {
        if (empty($appKey) || empty($timestamp) || empty($sign)) {
            return [self::CODE_ERROR_MISS_PARAMS, "参数不完整", []];
        }
        if (time() - $timestamp > AuthService::TIMEOUT) {
            return [self::CODE_ERROR_TIMEOUT, "时间戳超时", []];
        }
        if ($timestamp > time() + AuthService::TIMEOUT) {
            return [self::CODE_ERROR_TIMEOUT, "时间戳异常", []];
        }
        $application = ApplicationInfo::queryByKey($appKey);
        if (empty($application)) {
            return [self::CODE_ERROR_DISABLE, "应用权限不存在或未启用", []];
        }
        if ($sign <> md5($appKey . $application['appSecret'] . $timestamp)) {
//            return [self::CODE_ERROR_SIGN, '签名异常' . md5($appKey . $application['appSecret'] . $timestamp), []];
            return [self::CODE_ERROR_SIGN, '签名异常', []];
        }
        if ($application) {
            if ($inner) {
                $data['appId'] = $application['id'];
            }
            $data['ossAccessKeyId'] = $application['ossAccessKeyId'];
            $data['ossAccessKeySecret'] = $application['ossAccessKeySecret'];
            $data['ossBucket'] = $application['ossBucket'];
            $data['ossEndPoint'] = $application['ossEndPoint'];
            $data['ossTactics'] = $application['ossTactics'];
            return [self::CODE_SUCCESS, '', $data];
        }
    }

    //获取单个appKey的应用信息
    public static function applicationMap($appKey)
    {
        $application = ApplicationInfo::queryByKey($appKey);
        $result['id'] = empty($application['id']) ? '' : $application['id'];
        $result['name'] = empty($application['name']) ? '' : $application['name'];
        return $result;
    }

    //根据权限返回角色
    public static function roleMap($role)
    {
        $roleMap = Constants::roleMap();
        foreach ($roleMap as $roleKey => $roleName) {
            if ($roleKey > $role) {
                unset($roleMap[$roleKey]);
            }
        }
        return $roleMap;
    }

    public static function applicationMapList($applicationId = 0)
    {
        $applicationMap = ApplicationInfo::applicationMap();
        foreach ($applicationMap as $k => $v) {
            if (!empty($applicationId) && $k <> $applicationId) {
                unset($applicationMap[$k]);
            }
        }
        return $applicationMap;
    }

    public static function applicationList($type)
    {
        $list = ApplicationInfo::ListByType($type, 'id,appKey,name');
        return $list;
    }
}