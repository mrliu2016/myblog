<?php

namespace app\common\services;

class Constants
{
    const CODE_SUCCESS = 0;
    const CODE_FAILED = -1;
    const CODE_WARNING = -1; //警告
    const CODE_CLOSE = -2; //强制退出


    // token 有限期
    const LOGIN_TOKEN_EXPIRES = 86400;

    const WEB_SOCKET_IP = '0.0.0.0';
    const WEB_SOCKET_PORT = '9505';
    const WEB_SOCKET_PORT_SSL = '9506';
    const MESSAGE_TYPE_BARRAGE_REQ = 'barrage_req';
    const MESSAGE_TYPE_BARRAGE_RES = 'barrage_res';
    const MESSAGE_TYPE_SERVER_INFO_REQ = 'server_info_req';
    const MESSAGE_TYPE_SERVER_INFO_RES = 'server_info_res';
    const MESSAGE_TYPE_GIFT_REQ = 'gift_req';
    const MESSAGE_TYPE_GIFT_RES = 'gift_res';
    const MESSAGE_TYPE_GIFT_NOTIFY_RES = 'gift_notify_res';
    const MESSAGE_TYPE_HEARTBEAT_REQ = 'heartbeat_req';
    const MESSAGE_TYPE_HEARTBEAT_RES = 'heartbeat_res';
    const MESSAGE_TYPE_JOIN_REQ = 'join_req';
    const MESSAGE_TYPE_JOIN_RES = 'join_res';
    const MESSAGE_TYPE_JOIN_NOTIFY_RES = 'join_notify_res';
    const MESSAGE_TYPE_LEAVE_REQ = 'leave_req';
    const MESSAGE_TYPE_LEAVE_RES = 'leave_res';
    const MESSAGE_TYPE_KICK_REQ = 'kick_req';
    const MESSAGE_TYPE_KICK_RES = 'kick_res';
    const MESSAGE_TYPE_LM_REQ = 'lm_req'; // 连麦请求
    const MESSAGE_TYPE_LM_RES = 'lm_res'; // 连麦响应
    const MESSAGE_TYPE_LM_LIST_REQ = 'lm_list_req'; // 连麦请求
    const MESSAGE_TYPE_LM_LIST_RES = 'lm_list_res'; // 连麦响应
    const MESSAGE_TYPE_LM_AGREE_REQ = 'lm_agree_req'; // 连麦同意请求
    const MESSAGE_TYPE_LM_AGREE_RES = 'lm_agree_res'; // 连麦同意响应
    const MESSAGE_TYPE_LM_USER_LIST_RES = 'lm_user_list_res'; // 连麦响应

    const MESSAGE_TYPE_GAG_REQ = 'gag_req';
    const MESSAGE_TYPE_GAG_RES = 'gag_res';

    const WSGIFTORDER = 'WSGiftOrder';

    const WS_ROOM_LOCATION = 'WSRoomLocation_';
    const WS_ROOM_FD = 'WSRoomFD_';
    const WS_ROOM_USER = 'WSRoomUser_';
    const WS_ROOM_USER_COUNT = 'WSRoom_';
    const WSWARNING = 'WSWarning';
    const WSCLOSE = 'WSClose';
    const WS_ROOM_USER_LM_LIST = 'WSRoomUserLMList_';

    const LOGIN_TYPE_WEI_XIN = 'WEI_XIN';
    const LOGIN_TYPE_QQ = 'QQ';
    const LOGIN_TYPE_WEI_BO = 'WEI_BO';


    //统一登录相关
    const COOKIE_UNIFIED_LOGIN = 'unifiedLoginTicket';
    const COOKIE_DOMAIN = '.3ttech.cn';
    const COOKIE_EXPIRE = 604800;

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
    const NUM_WS_ROOM_USER = 100;//每个房间最多保存100个用户的信息
    const WS_NOTICE = '文明用语';
    const WS_MESSAGE_TYPE_WARNING = 0;  //ws 警告
    const WS_MESSAGE_TYPE_CLOSE = 1;  //ws 强制关闭
    const WS_KEY_WARNING = 'WSWarning';
    const WS_KEY_CLOSE = 'WSClose';

    /**
     * 拉、推流鉴权
     */
    const AUTHORITY_APP_ID = '';
    const AUTHORITY_KEY = 'wushuanglive';
    const AUTHORITY_KEY_BAK = 'wushuang';

    const CDN_FACTORY_QCLOUD = 'qCloud';//腾讯云cdn
    const CDN_FACTORY_ALIYUN = 'aLiYun';//阿里云cdn
    const CDN_FACTORY_XYCDN = 'xyCDN';//星域cdn
}