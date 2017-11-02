<?php

namespace app\api\controllers;

use app\common\models\TaskMember;
use app\common\services\Constants;
use Yii;

class OrderQueryController extends BaseController
{
    /**
     * 查询订单
     */
    public function actionWeiXinOrderQuery()
    {
        $params = Yii::$app->request->post();
        $result = TaskMember::queryOrder($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, '', []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * H5查询、更新订单
     */
    public function actionH5OrderQuery()
    {
        $params = Yii::$app->request->post();
        if (TaskMember::h5UpdateTaskMember($params['orderId'], '', '', $params['status'])) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, 'success', []);
        }
        $this->jsonReturnError(Constants::CODE_FAILED, 'failed', []);
    }
}