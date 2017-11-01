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

    public function actionList()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $result = UserTag::queryInfo($params);
        $count = UserTag::queryInfoNum($params);
        var_dump($result);
    }
}