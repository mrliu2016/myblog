<?php

namespace app\common\services;

class Constants
{
    const CODE_SUCCESS = 0;
    const CODE_FAILED = -1;
    const CODE_SYSTEM_BUSY = -2; // 系统繁忙
    const CODE_WARNING = -1; //警告
    const CODE_CLOSE = -2; //强制退出
    const CODE_VERIFY_CODE_LIMIT_FREQUENCY = -3; // 验证码失败次数，获取验证码按钮置灰不可点击，1小时后可恢复获取验证码功能

    const CODE_LIVE_END = 0;
    const CODE_LIVE = 1;
    const CODE_PLAYBACK = 2;
    const PROJECT_NAME = '3TLive_';

    // token 有限期
    const LOGIN_TOKEN_EXPIRES = 86400;

    const DEFAULT_EXPIRES = 86400;

    const WEB_SOCKET_IP = '0.0.0.0';
    const WEB_SOCKET_PORT = '9550';
    const WEB_SOCKET_PORT_SSL = '9551';
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
    const MESSAGE_TYPE_QUIT_REQ = 'leave_req';
    const MESSAGE_TYPE_QUIT_RES = 'leave_res';
    const MESSAGE_TYPE_KICK_REQ = 'kick_req';
    const MESSAGE_TYPE_KICK_RES = 'kick_res';
    const MESSAGE_TYPE_LM_REQ = 'lm_req'; // 连麦请求
    const MESSAGE_TYPE_LM_RES = 'lm_res'; // 连麦响应
    const MESSAGE_TYPE_LM_LIST_REQ = 'lm_list_req'; // 连麦请求
    const MESSAGE_TYPE_LM_LIST_RES = 'lm_list_res'; // 连麦响应
    const MESSAGE_TYPE_LM_AGREE_OR_REFUSE_REQ = 'lm_agree_or_refuse_req'; // 连麦同意、拒绝请求
    const MESSAGE_TYPE_LM_AGREE_OR_REFUSE_RES = 'lm_agree_or_refuse_res'; // 连麦同意、拒绝响应
    const MESSAGE_TYPE_LM_USER_LIST_RES = 'lm_user_list_res'; // 连麦响应
    const MESSAGE_TYPE_CLOSE_CALL_REQ = 'close_call_req'; // 断开连麦
    const MESSAGE_TYPE_CLOSE_CALL_RES = 'close_call_res';
    const MESSAGE_TYPE_CLOSE_CALL_SECONDARY_REQ = 'close_call_secondary_req'; // 副播断开连麦
    const MESSAGE_TYPE_CLOSE_CALL_SECONDARY_RES = 'close_call_secondary_res';
    const MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_REQ = 'prohibit_live_one_day_req'; // 禁播24小时
    const MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_RES = 'prohibit_live_one_day_res';
    const MESSAGE_TYPE_PROHIBIT_LIVE_30_DAYS_REQ = 'prohibit_live_30_days_req'; // 禁播30天
    const MESSAGE_TYPE_PROHIBIT_LIVE_30_DAYS_RES = 'prohibit_live_30_days_res';
    const MESSAGE_TYPE_PERPETUAL_PROHIBIT_LIVE_REQ = 'perpetual_prohibit_live_req'; // 永久禁播
    const MESSAGE_TYPE_PERPETUAL_PROHIBIT_LIVE_RES = 'perpetual_prohibit_live_res';
    const MESSAGE_TYPE_PROHIBIT_ACCOUNT_NUMBER_REQ = 'perpetual_account_number_req'; // 禁封账号
    const MESSAGE_TYPE_PROHIBIT_ACCOUNT_NUMBER_RES = 'perpetual_account_number_res';
    const MESSAGE_TYPE_BLACKLIST_REQ = 'blacklist_req'; // 拉黑
    const MESSAGE_TYPE_BLACKLIST_RES = 'blacklist_res';
    const MESSAGE_TYPE_GAG_REQ = 'gag_req'; // 禁言
    const MESSAGE_TYPE_GAG_RES = 'gag_res';

    const WS_ROOM_LOCATION = self::PROJECT_NAME . 'WSRoomLocation_';
    const WS_ROOM_FD = self::PROJECT_NAME . 'WSRoomFD_';
    const WS_ROOM_USER = self::PROJECT_NAME . 'WSRoomUser_';
    const WS_ROOM_USER_COUNT = self::PROJECT_NAME . 'WSRoom_';
    const WS_WARNING = self::PROJECT_NAME . 'WSWarning';
    const WS_CLOSE = self::PROJECT_NAME . 'WSClose';
    const WS_ROOM_USER_LM_LIST = self::PROJECT_NAME . 'WSRoomUserLMList_'; // 连麦用户列表
    const WS_USER_BALANCE = self::PROJECT_NAME . 'WSUserBalance';//余额
    const WS_KEYWORD = self::PROJECT_NAME . ':WSKeyword';
    const WS_SEND_GIFT_VIRTUAL_CURRENCY = self::PROJECT_NAME . ':WSSendGiftVirtualCurrency_'; // 送礼虚拟货币
    const WS_INCOME = self::PROJECT_NAME . ':WSIncome_'; // 总收到、收益虚拟货币
    const WS_MASTER_CURRENT_INCOME = self::PROJECT_NAME . 'WSCurrentIncome_'; // 主播本场直播收益
    const WS_GAG = self::PROJECT_NAME . ':WSGag_'; // 禁言
    const WS_ROBOT = self::PROJECT_NAME . 'WSRobot'; // 机器人
    const WS_BANNED_WORD = self::PROJECT_NAME . 'WSBannedWord'; // 违禁词
    const WS_ROOM_USER_QUANTITY = self::PROJECT_NAME . 'WSRoomUserQuantity:'; // 房间用户人数

    const WS_HEARTBEAT_IDLE_TIME = 30; // TCP连接如果在30秒内
    const WS_HEARTBEAT_CHECK_INTERVAL = 10; // 每10秒侦测一次心跳
    const WS_LATEST_HEARTBEAT_TIME = self::PROJECT_NAME . 'WSLatestHeartbeatTime'; // 最新的心跳时间
    const WS_CONNECTION = self::PROJECT_NAME . 'WSConnection';
    const WS_DEFAULT_EXPIRE = 3600;
    const WS_WEB_SOCKET_MAX_CONNECTION = 100000;
    const WS_WORKER_NUM = 8;
    const WS_TASK_WORKER_NUM = 8;
    const WS_SOCKET_BUFFER_SIZE = 128 * 1024 * 1024;
    const WS_BUFFER_OUTPUT_SIZE = 128;

    // 队列
    const QUEUE_WS_HEARTBEAT = self::PROJECT_NAME . 'WS_Heartbeat_Live';
    const QUEUE_WS_GIFT_ORDER = self::PROJECT_NAME . 'WSGiftOrder';
    const QUEUE_WS_HEARTBEAT_EX = '3TLive:' . YII_ENV . ':LiveHeartbeat'; // LiveHeartbeat:appId {}

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
    const NUM_WS_ROOM_USER = 10000;//每个房间最多保存100个用户的信息
    const WS_NOTICE = '文明用语';
    const WS_MESSAGE_TYPE_WARNING = 0;  //ws 警告
    const WS_MESSAGE_TYPE_CLOSE = 1;  //ws 强制关闭
    const WS_KEY_WARNING = 'WSWarning';
    const WS_KEY_CLOSE = 'WSClose';

    /**
     * 拉、推流鉴权
     */
    const AUTHORITY_APP_ID = '';
    const AUTHORITY_KEY = 'customize';
    const AUTHORITY_KEY_BAK = 'customize';

    const CDN_FACTORY_QCLOUD = 'qCloud';//腾讯云cdn
    const CDN_FACTORY_ALIYUN = 'aLiYun';//阿里云cdn
    const CDN_FACTORY_XYCDN = 'xyCDN';//星域cdn
    const CDN_FACTORY_QING_CLOUD = 'qingCloud'; // 青云
    const CDN_FACTORY_WANG_SU = 'wangSu'; // 网宿
    const CDN_FACTORY_CSY = 'CSY'; // 创世云

    const TTT_TECH_TOKEN = '3ttech_token';
    const VERIFY_CODE = 'verify_code';
    const VERIFY_CODE_FREQUENCY = 'verify_code_frequency'; // 限制发送频率
    const VERIFY_CODE_EXPIRES = 300; // 有效期5分钟
    const VERIFY_CODE_REGISTER = 'register'; // 注册
    const VERIFY_CODE_RESET = 'reset'; // 忘记密码
    const VERIFY_CODE_LOGIN = 'login'; // 验证码登录
    const VERIFY_CODE_LIMIT_FREQUENCY = 5; // 限制次数
    const VERIFY_CODE_LIMIT_FREQUENCY_EXPIRES = 3600; // 次数有效期限制

    /**
     * 服务器节点
     */
    const WS_NODE_REPLICAS = 20; // 每个节点的复制的个数

    /**
     * 连麦
     */
    const LM_APPLY = 1; // 连麦申请
    const LM_TYPE_AGREE = 2; // 连麦同意
    const LM_TYPE_REFUSE = 3; // 连麦拒绝
    const LM_TYPE_CLOSE = 4; // 断开连麦
    const LM_USER_ONLINE = 5;//连麦用户在线
    const LM_USER_OFFLINE = 6;//连麦用户离线

    const LM_TYPE_AUDIO = 'audio'; // 音频
    const LM_TYPE_VIDEO = 'video'; // 视频

    /**
     * 角色
     */
    const WS_ROLE_AUDIENCE = 0; // 观众
    const WS_ROLE_MASTER = 1; // 主播
    const WS_ROLE_OTHER = -1; // 其他

}