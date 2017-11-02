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
}