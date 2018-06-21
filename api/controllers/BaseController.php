<?php

namespace app\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    const PAGE_SIZE = 15;
    public $enableCsrfValidation = false;

    protected function jsonReturn($return)
    {
        $callback = Yii::$app->request->getQueryParam('callback');
        if (empty($callback)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $return;
            if (isset($return['encodeOptions']) && $return['encodeOptions'] && empty($return['data'])) {
                Yii::$app->response->formatters[Response::FORMAT_JSON] = [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'encodeOptions' => JSON_FORCE_OBJECT,
                ];
            }
            unset(Yii::$app->response->data['encodeOptions']);
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
            'data' => $data,
            'encodeOptions' => JSON_FORCE_OBJECT
        ]);
    }

    protected function jsonReturnError($code = -1, $message = '', $data = [])
    {
        return $this->jsonReturn([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'encodeOptions' => JSON_FORCE_OBJECT
        ]);
    }
}