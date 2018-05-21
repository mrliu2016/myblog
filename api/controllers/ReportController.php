<?php

namespace app\api\controllers;

use app\common\models\Report;
use app\common\models\ReportOptions;
use app\common\services\Constants;
use Yii;

class ReportController extends BaseController
{
    /**
     * 举报
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

    /**
     * 举报选项
     */
    public function actionOptions()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $list = ReportOptions::queryInfo($params, 'id,content,created');
        $totalCount = intval(ReportOptions::queryInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        if (!empty($list)) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '',
                compact('totalCount', 'page', 'size', 'pageCount', 'list')
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }
}