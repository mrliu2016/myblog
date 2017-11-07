<?php

namespace app\manager\controllers;

use app\common\components\OSS;
use app\common\extensions\OSS\OssClient;
use app\common\models\User;
use app\common\services\Constants;
use Yii;
use yii\data\Pagination;

class IndexController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    const PAGE_SIZE = 15;
    public $enableCsrfValidation = false;

    private static function pagination($pageNo, $count)
    {
        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $count,
        ]);
        $pagination->setPage($pageNo);
        return $pagination;
    }


    public function actionIndex()
    {
//        $user = User::queryById(100001);
//        var_dump($user);
//        exit();
//        $application = Application::queryById(1);
//        var_dump($application);
//        exit();
        $userName = $this->user->userName;
        return $this->render('index', array(
            'userName' => $userName
        ));
    }
}
