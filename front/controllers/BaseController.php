<?php

namespace app\front\controllers;

use app\common\components\IPUtils;
use app\front\models\unified\UnifiedLoginFilter;
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
        $ip = IPUtils::get_local_ip();
        header('ip: ' . $ip);
        return [
            [
                'class' => UnifiedLoginFilter::className(),
                'except' => ['notify4-record', 'notify4-compress', 'video-list', 'loginout']
            ],
        ];
    }

    //获取参数支持get、post、json
    protected function getParams()
    {
        $params = $_GET;
        $params = array_merge($params, $_POST);
        $contentType = empty($_SERVER['CONTENT_TYPE']) ? '' : $_SERVER['CONTENT_TYPE'];
        if (empty($_POST) && false !== strpos($contentType, 'application/json')) {
            $content = file_get_contents('php://input');
            $params = array_merge($params, (array)json_decode($content, true));
        }
        return $params;
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
//        require(__DIR__ . "/../../common/extensions/xhprof_lib/xhprof_footer.php");
        Yii::$app->end();
    }

    protected function jsonReturnSuccess($code = 0, $data = null, $message = '')
    {
        return $this->jsonReturn([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function jsonReturnError($code, $message = '')
    {
        return $this->jsonReturn([
            'code' => $code,
            'message' => $message
        ]);
    }

    //获取post参数
    protected function post($key = "", $default = "")
    {
        if (empty($key)) {
            return Yii::$app->request->post();
        }
        return Yii::$app->request->post($key, $default);
    }

    //获取get参数
    protected function get($key = "", $default = "")
    {
        if (empty($key)) {
            return Yii::$app->request->get();
        }
        return Yii::$app->request->get($key, $default);
    }

    //判断是否ajax提交
    protected function _isAjax()
    {
        return Yii::$app->request->isAjax;
    }

    //判断是否post提交
    protected function _isPost()
    {
        return Yii::$app->request->isPost;
    }

    //判断是否get提交
    protected function _isGet()
    {
        return Yii::$app->request->isGet;
    }


}