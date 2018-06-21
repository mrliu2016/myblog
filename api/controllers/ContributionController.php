<?php
namespace app\api\controllers;

use Yii;

class ContributionController extends BaseController
{
    public function actionWeekContribution()
    {
        $params = Yii::$app->request->post();
    }
}