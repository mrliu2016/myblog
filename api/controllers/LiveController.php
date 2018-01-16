<?php

namespace app\api\controllers;

use app\common\models\Video;
use app\common\services\Constants;
use app\common\services\UserService;
use Yii;

class LiveController extends BaseController
{
    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    //直播人气列表
    public function actionHot()
    {
        $params = Yii::$app->request->get();
        $result = UserService::hot($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    public function actionStartLive()
    {
        $params = Yii::$app->request->post();
        $result = Video::create($params['userId'], $params['userId'], $params['title'], $params['imgSrc']);
        if (!$result) {
            $this->jsonReturnError(Constants::CODE_FAILED, '开播失败');
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '开播成功', ['liveId' => $result]);
    }

    public function actionTerminationLive()
    {
        $params = Yii::$app->request->post();
        $result = Video::terminationLive($params['liveId'], $params['userId']);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '结束直播');
    }
}