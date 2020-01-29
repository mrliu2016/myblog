<?php

namespace app\common\services;

/**
 * Class Constants
 * @package app\common\services
 * 定义常量
 */
class Constants
{
    const CODE_SUCCESS = 0;//成功
    const CODE_FAILED = -1;//失败
    const CODE_SYSTEM_BUSY = -2; // 系统繁忙
    const CODE_WARNING = -1; //警告
    const CODE_CLOSE = -2; //强制退出

    //统一登录相关
    const COOKIE_UNIFIED_LOGIN = 'unifiedLoginTicket';
    const COOKIE_EXPIRE = 604800;


    //角色相关 来自用户中心
    const ROLE_ADMIN = 99;
    const ROLE_USER = 1;
    const ROLE_HR = 2;
    const ROLE_BD = 3;
    const ROLE_OP = 4;
    const ROLE_DEV = 5;


}