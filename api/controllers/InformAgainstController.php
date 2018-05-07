<?php

namespace app\api\controllers;

use app\common\models\InformAgainst;
use app\common\services\Constants;
use Yii;

class InformAgainstController extends BaseController
{
    /**
     * 举报
     */
    public function actionInformAgainst()
    {
        $params = Yii::$app->request->post();
        InformAgainst::informAgainst($params);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS,'举报成功！');
    }
}