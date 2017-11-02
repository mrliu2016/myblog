<?php

namespace app\api\controllers;

use app\common\models\UserTag;
use app\common\services\Constants;
use Yii;

class UserTagController extends BaseController
{
    const PAGE_SIZE = 10;

    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    //用户标签列表
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $list = UserTag::queryInfo($params);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, 'success', $list);
    }
}