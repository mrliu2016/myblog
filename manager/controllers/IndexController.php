<?php

namespace app\manager\controllers;

use Yii;

class IndexController extends BaseController{

    //首页
    public function actionIndex(){
        $this->render('index');
    }
}