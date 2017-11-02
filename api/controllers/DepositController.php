<?php

namespace app\api\controllers;

use app\common\models\Deposit;
use app\common\services\Constants;
use Yii;

class DepositController extends BaseController
{
    const PAGE_SIZE = 10;

    /**
     * 充值记录
     */
    public function actionDepositRecord()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;
        $result = Deposit::depositRecord($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['message'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['message'], $result['data']);
    }
}