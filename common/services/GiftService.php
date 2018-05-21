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
}