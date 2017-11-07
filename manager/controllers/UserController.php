<?php

namespace app\manager\controllers;

use app\common\models\User;
use app\common\services\LiveService;
use Yii;
use yii\data\Pagination;

class UserController extends BaseController
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
        echo '<pre>';
        LiveService::getUserInfoListByRoomId(124);
        exit();
        $info['userId'] = 123;
        $info['nickName'] = 'jinhongxin';
        $info['avatar'] = 'https://img3.doubanio.com/view/photo/albumcover/public/p2500714030.jpg';
        $info['level'] = 5;
        echo json_encode($info);
        exit();

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = User::queryInfo($params);
        $count = User::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }
}
