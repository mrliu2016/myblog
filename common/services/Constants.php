<?php

namespace app\common\services;

class Constants
{
    const CODE_SUCCESS = 0;
    const CODE_FAILED = -1;


    // token 有限期
    const LOGIN_TOKEN_EXPIRES = 86400;

    const WEB_SOCKET_IP = '127.0.0.1';
    const WEB_SOCKET_PORT = '9502';
    const MESSAGE_TYPE_BARRAGE_REQ = 'barrage_req';

    const LOGIN_TYPE_WEI_XIN = 'WEI_XIN';
    const LOGIN_TYPE_QQ = 'QQ';
    const LOGIN_TYPE_WEI_BO = 'WEI_BO';

    // 分
    const CENT = 100;

    /**
     * 提现
     */
    const WITHDRAW_STATUS_APPLY = 0; // 未处理
    const WITHDRAW_STATUS_AGREE = 1; // 通过
    const WITHDRAW_STATUS_REFUSE = -1; // 拒绝
    const WITHDRAW_STATUS_FAILED = -2; // 失败
    const WITHDRAW_STATUS_COMPLETE = 2; // 成功

    const PAY_TYPE_WEI_XIN = 'WEI_XIN';

    const WEI_XIN_JS_TRADE = 'JS_API';
    const WEI_XIN_APP_TRADE = 'APP';


    /**
     * 订单
     */
    const ORDER_STATUS_ALL = 'ORDER_ALL'; // 所有订单
    const ORDER_STATUS_PENDING_PAYMENT = 'CREATED'; // 待付款
    const ORDER_STATUS_PAYMENT = 'PAID'; // 已付款
    const ORDER_STATUS_TRANSACTION_CANCELLED = 'CANCELLED'; // 交易取消
    const ORDER_STATUS_TRANSACTION_CLOSED = 'CLOSED'; // 交易关闭
    const ORDER_STATUS_COMPLETED = 'COMPLETED'; // 交易完成
    const ORDER_STATUS_REFUND = 'REFUND'; // 退款中
    const ORDER_STATUS_REFUND_END = 'REFUND_END'; // 已退款

    const ORDER_STATUS_APPROVAL_PENDING = 'APPROVAL'; // 待审核
    const ORDER_STATUS_APPROVAL_APPROVAL_ING = 'APPROVAL_ING'; // 审核中
    const ORDER_STATUS_APPROVAL_THROUGH = 'THROUGH'; // 审核通过
    const ORDER_STATUS_APPROVAL_REFUSE = 'REFUSE'; // 审核不通过

    const EARTH_RADIUS = 6371; //地球半径，平均半径为6371km
}