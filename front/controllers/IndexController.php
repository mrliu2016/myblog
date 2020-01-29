<?php

namespace app\front\controllers;


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

    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

}


