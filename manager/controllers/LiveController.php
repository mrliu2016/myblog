<?php

namespace app\manager\controllers;

use app\common\models\Video;
use Yii;
use yii\data\Pagination;

class LiveController extends BaseController
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
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Video::queryInfo($params);
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionRecord()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Video::queryInfo($params);
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('record', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }
}
