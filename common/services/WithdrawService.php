<?php

namespace app\common\services;


use app\common\models\Income;
use app\common\models\Task;
use app\common\models\TaskDetail;
use app\common\models\TaskMember;
use app\common\models\User;
use app\common\models\Withdraw;

class WithdrawService
{
    /**
     * 我的提现列表
     * @param $params
     * @return array
     */
    public static function myWithdrawList($params)
    {
        if (!isset($params['unionId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
        $unionId = $params['unionId'];
        $data = Withdraw::queryAllByUnionIdWithPager($unionId, $page, $pageSize);
        $list = array();
        foreach ($data['list'] as $key => $value) {
            $item = array();
            $item['created'] = date('Y-m-d H:i:s', $value['created']);
            $item['priceReal'] = $value['priceReal'] / 100;
            $item['status'] = (int)$value['status']; //0未处理   1 通过  -1拒绝  -2失败   2成功
            $list[] = $item;
        }
        $data['list'] = $list;

        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }
}