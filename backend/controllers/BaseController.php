<?php

namespace app\backend\controllers;

use app\common\components\IPUtils;
use app\backend\models\unified\UnifiedLoginFilter;
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

    //解析参数
    protected function parse4Params($params)
    {
        //处理页面排序字段
        if (!empty($params['sort'])) {
            $arr = explode('00', $params['sort']);
            $params['sortColumn'] = $arr[0];
            $params['sortType'] = $arr[1];
        }
        //时间插件用
        $params['minDate'] = date('Y/m/d 00:00:00', strtotime('-60 days', time()));
        if (!empty($params['datePicker'])) {
            $a = explode(' - ', $params['datePicker']);
            $params['start_time'] = $a[0];
            $params['end_time'] = $a[1];
        } else {
            $params['start_time'] = empty($params['start_time']) ? date('Y-m-d 00:00') : $params['start_time'];
            $params['end_time'] = empty($params['end_time']) ? date('Y-m-d H:i') : $params['end_time'];
        }
        return $params;
    }

}