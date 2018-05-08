<?php

namespace app\manager\controllers;

use app\common\models\User;
use app\common\services\Constants;
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

    public function actionLogout()
    {
        setcookie(Constants::COOKIE_UNIFIED_LOGIN, null, time() - 1000, "/", Constants::COOKIE_DOMAIN);
        $loginUrl = Yii::$app->params["ucDomain"] . "/user/login?redirect=" . Yii::$app->request->getHostInfo();
        Yii::$app->getResponse()->redirect($loginUrl);
    }

    public function actionDepositIdealMoney()
    {
        $params = Yii::$app->request->post();
        $result = User::depositIdealMoney($params['userId'], $params['idealMoney']);
        $result ?
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, ['result' => $result]) :
            $this->jsonReturnError(Constants::CODE_FAILED);
    }
}
