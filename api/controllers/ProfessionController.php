<?php

namespace app\api\controllers;
use app\common\models\Profession;
use app\common\services\Constants;
use Yii;

class ProfessionController extends BaseController
{
    /**
     * 职业选项
     */
    public function actionProfessionOptions()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $list = Profession::queryInfo($params,'id,name,created');
        $totalCount = intval(Profession::queryInfoNum($params));
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