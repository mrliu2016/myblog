<?php

namespace app\api\controllers;

use app\common\models\TaskMember;
use app\common\services\Constants;
use Yii;

class PaymentController extends BaseController
{
    /**
     * 微信公众号支付
     */
    public function actionWeiXinJsPay()
    {
        $params = Yii::$app->request->post();
        $result = TaskMember::weiXinPayRecord($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}