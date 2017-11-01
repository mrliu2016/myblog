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
}