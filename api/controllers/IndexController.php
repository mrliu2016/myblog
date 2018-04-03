<?php

namespace app\api\controllers;

use app\common\components\UploadUtils;
use app\common\components\WeiXinApi;
use app\common\models\User;
use app\common\services\Constants;

class IndexController extends BaseController
{
//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ]
//        ];
//    }

    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    public function actionIndex()
    {
        $result = UploadUtils::multiUploadPicture();
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS,'',$result);
//        echo 'welcome';
//        exit;
    }

    public function actionTest()
    {
        self::jsonReturnSuccess(Constants::CODE_SUCCESS, User::queryById(100001));
    }
}