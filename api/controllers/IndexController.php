<?php

namespace app\api\controllers;

use app\common\components\WeiXinApi;

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
        echo 'welcome';
        exit;
    }

    public function actionTest()
    {
        var_dump(WeiXinApi::getAccessToken('wxc157967034c8f60b', 'c098668310efd73a1fa1df5d436fe299'));
    }
}