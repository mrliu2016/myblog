/**
 *
 * @type {string}
 */
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