<?php

namespace app\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public $enableCsrfValidation = false;

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

    protected function jsonReturnSuccess($code = 0, $message = '', $data = [])
    {
        return $this->jsonReturn([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function jsonReturnError($code = -1, $message = '', $data = [])
    {
        return $this->jsonReturn([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}