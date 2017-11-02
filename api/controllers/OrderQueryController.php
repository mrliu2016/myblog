<?php

namespace app\api\controllers;

use app\common\models\Payment;
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
        $result = Payment::queryOrder($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
    }
}