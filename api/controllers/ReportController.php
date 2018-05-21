<?php

namespace app\api\controllers;

use app\common\models\Report;
use app\common\services\Constants;
use Yii;

class ReportController extends BaseController
{
    /**
     * userId
     * reportedUserId
     */
    public function actionReport()
    {
        $params = Yii::$app->request->post();
        $result = Report::report($params);
        if ($result) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '举报成功');
        }
        $this->jsonReturnError(Constants::CODE_FAILED, '举报失败');
    }
}