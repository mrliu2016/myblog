<?php

namespace app\common\services;

use app\common\models\Gift;
use app\common\models\Order;
use app\common\models\User;
use Yii;

class GiftService
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_MAX = 100;

    public static function getGiftList($params)
    {
        $params['defaultPageSize'] = self::PAGE_SIZE_MAX;
        $list = Gift::queryInfo($params);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $list];
    }

    /**
     * 榜单
     *
     * @param $params
     * @return mixed
     * @throws \yii\db\Exception
     */
    public static function contribution($params)
    {
        $userId = '';
        $userInfo = [];
        $result = Order::queryContributionInfo($params);
        foreach ($result as $key => $value) {
            $userId .= $value['userId'] . ',';
        }
        if (!empty($userId)) {
            $sql = 'select id,avatar,nickName,level,description,roomId from '
                . User::tableName() . ' where id in(' . trim($userId, ',') . ')';
            $userInfo = Order::queryBySQLCondition($sql);
        }
        foreach ($result as $key => $value) {
            $flag = true;
            foreach ($userInfo as $userKey => $userValue) {
                if ($value['userId'] == $userValue['id']) {
                    $result[$key]['avatar'] = !empty($userValue['avatar']) ? $userValue['avatar'] : Yii::$app->params['defaultAvatar'];
                    $result[$key]['nickName'] = $userValue['nickName'];
                    $result[$key]['level'] = intval($userValue['level']);
                    $result[$key]['description'] = $userValue['description'];
                    $flag = false;
                }
            }
            if ($flag) {
                $result[$key]['avatar'] = Yii::$app->params['defaultAvatar'];
                $result[$key]['nickName'] = '';
                $result[$key]['level'] = 0;
                $result[$key]['description'] = '';
            }
        }
        return $result;
    }

    /**
     * 记录数
     *
     * @param $params
     * @return mixed
     */
    public static function queryInfoNum($params)
    {
        return Order::queryInfoNum($params);
    }


    /**
     * 礼物-账单
     * 收到礼物、送出礼物、充值记录
     * @type: 0、收到礼物 1、发送礼物 2、充值记录
     */
    public static function queryBillList($params){

        $type = intval($params['type']);
        unset($params['type']);
        $list = array();
        switch ($type){
            case 1: // 收到礼物
                $list = Order::queryReceiveGiftList($params);
                break;
            case 2: // 送出礼物
                $list = Order::queryGiveGiftList($params);
                break;
            case 3: // 充值记录
                break;
        }
        if(isset($list)){
            $userId = '';
            $giftId = '';
            //接收礼物  查询支付方    //送出礼物  查询接收方
            foreach ($list as $key => $value) {
                if($type == 1){
                    $userId .= $value['userId'] . ',';
                }
                else if($type == 2){
                    $userId .= $value['userIdReceive'] . ',';
                }
                $giftId .= $value['giftId'] . ',';
            }

            if (!empty($userId)) {
                $sql = 'select id,avatar,nickName,level,description,roomId from '
                    . User::tableName() . ' where id in(' . trim($userId, ',') . ')';
                $userInfo = Order::queryBySQLCondition($sql);
            }
            //查询礼物
            if(!empty($giftId)){
                $sql = 'select `id`,`name` from '
                    . Gift::tableName() . ' where `id` in(' . trim($giftId, ',') . ')';
                $giftInfo = Order::queryBySQLCondition($sql);
            }
            foreach ($list as $k => $v) {
                foreach ($userInfo as $userKey => $userValue) {
                    if($type == 1 && $v['userId'] == $userValue['id']){
                        $list[$k]['avatar'] = !empty($userValue['avatar']) ? $userValue['avatar'] : Yii::$app->params['defaultAvatar'];
                        $list[$k]['nickName'] = $userValue['nickName'];
                    }
                    else if($type == 2 && $v['userIdReceive'] == $userValue['id']){
                        $list[$k]['avatar'] = !empty($userValue['avatar']) ? $userValue['avatar'] : Yii::$app->params['defaultAvatar'];
                        $list[$k]['nickName'] = $userValue['nickName'];
                    }
                }
                foreach ($giftInfo as $giftKey => $giftVal) {
                    if ($v['giftId'] == $giftVal['id']) {
                        $list[$k]['name'] = $giftVal['name'];
                    }
                }
            }
        }
        return $list;
    }
    //通过用户id查询
    public static function queryInfoNumByUserId($type,$userId){
        return Order::queryInfoNumByUserId($type,$userId);
    }
}