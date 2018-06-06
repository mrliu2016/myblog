<?php

namespace app\api\controllers;

class WapController extends BaseController
{
    /**
     * 分享
     *
     * @return string
     */
    public function actionMpWeb()
    {
        return $this->render('mp-web', [

        ]);
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }
}