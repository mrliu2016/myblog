<?php

namespace app\manager\controllers;

use app\manager\models\unified\UnifiedLoginFilter;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public $user;
    public $specialPermissions = [];
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => UnifiedLoginFilter::className(),
                'except' => ['login', 'logout', 'check', 'key','type-list','inner-add','inner-delete','login-third']
            ],
        ];
    }

    protected function jsonReturn($return)
    {
        $callback = Yii::$app->request->getQueryParam('callback');
        if (empty($callback)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $return;
        } else {
            Yii::$app->response->format = Response::FORMAT_JSONP;
            Yii::$app->response->data = [
                'callback' => $callback,
                'data' => $return,
            ];
        }
        Yii::$app->end();
    }

    protected function jsonReturnSuccess($code = 0, $data = null)
    {
        return $this->jsonReturn(['code' => $code,
            'message' => '',
            'data' => $data]);
    }

    protected function jsonReturnError($code = -1, $message = '')
    {
        return $this->jsonReturn([
            'code' => $code,
            'message' => $message
        ]);
    }

}