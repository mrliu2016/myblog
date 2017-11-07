<?php

namespace app\manager\controllers;

use Yii;
use yii\data\Pagination;

class GiftController extends BaseController
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


    public function actionTemplate()
    {
        return $this->render('template', []);
    }

    public function actionOrder()
    {
        return $this->render('order', []);
    }
}
