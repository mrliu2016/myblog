<?php

namespace app\common\services;

class Constants
{
    const CODE_SUCCESS = 0;
    const STATUS_SUCCESS = 0;
    const CODE_FAILED = -1;
    const COOKIE_UNIFIED_LOGIN = 'unifiedLoginTicket';
    const COOKIE_DOMAIN = '.wushuangtech.com';
    const COOKIE_EXPIRE = 604800;
    const ROLE_ADMIN = 99;
    const STATUS_DELETED = 1;
    const STATUS_UNDELETED = 0;
    const RECOMMEND = 1;
    const UNRECOMMEND = 0;

    public static function roleMap()
    {
        return [0 => '用户', 1 => '管理员', 99 => '超级管理员'];
    }

    const LOCAL_APPLICATION_ID = 1;
    const FLAG_USER_PROFILE = 1;  //用户个人信息页面
    const FLAG_USER_TASK = 2;  //用户任务
    const FLAG_TASK_CREATE = 3;  //创建任务
    const FLAG_TASK_JOIN = 4;  //加入任务
    const FLAG_GRADE = 9;  //打分页面

    const CENT = 100;

    /**
     * 提现
     */
    const WITHDRAW_STATUS_APPLY = 0; // 未处理
    const WITHDRAW_STATUS_AGREE = 1; // 通过
    const WITHDRAW_STATUS_REFUSE = -1; // 拒绝
    const WITHDRAW_STATUS_FAILED = -2; // 失败
    const WITHDRAW_STATUS_COMPLETE = 2; // 成功

    const COUPON_TYPE_NEWER = 0;//新人优惠券
    const COUPON_STATUS_VALID = 0;//优惠券未使用
    const COUPON_STATUS_USED = 1;//优惠券使用

    const TASK_STATUS_CREATE = 0;
    const TASK_STATUS_SUCCESS = 1;
    const TASK_STATUS_FAILED = -1;
    const TASK_STATUS_CLOSE = 2;

    const TASK_PAY_STATUS_CREATE = 0;
    const TASK_PAY_STATUS_SUCCESS = 1;
    const TASK_PAY_STATUS_FAILED = -1;
    const TASK_PAY_STATUS_REFUND = 2;

    const PAY_TYPE_WEI_XIN = 'WEI_XIN';
    const PAY_TYPE_COUPON = 'COUPON';

    const TASK_QUEUE_NOTICE = 'TASK_NOTICE';
    const ACCESS_TOKEN_EXPIRES_IN = 7200;

    const WEI_XIN_DESCRIPTION = '国内领先的第三方自媒体内容质量评价监测和增值运营服务平台，拥有数十万名“内容评审员”对自媒体文章的内容质量进行评价反馈和统计分析，为国内自媒体提升内容运营质量提供有力的数据支持。';
}