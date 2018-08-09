<?php

namespace app\manager\controllers;

use app\common\services\Constants;
use app\common\services\ServerResourcesService;
use Yii;

class IMController extends BaseController
{
    /**
     * 服务器状态
     *
     * @return string
     */
    public function actionServer()
    {
        $serverResource = ServerResourcesService::serverResource();
        return $this->render('server', [
            'serverResource' => $serverResource
        ]);
    }

    /**
     * 定时获取服务器状态
     */
    public function actionAjaxGetServer()
    {
        $serverResource = ServerResourcesService::serverResource();
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $serverResource);
    }

    /**
     * 开启、关闭 调度分配
     */
    public function actionDispatchLocation()
    {
        $params = Yii::$app->request->post();
        if (ServerResourcesService::openCloseDispatchLocation($params)) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS);
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }

    /**
     * 调整服务器负载
     */
    public function actionResetLoadAvg()
    {
        $params = Yii::$app->request->post();
        $result = ServerResourcesService::resetLoadAvg($params);
        if ($result['code'] == Constants::CODE_SUCCESS) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $params);
        }
        $this->jsonReturnError(Constants::CODE_FAILED, $result['message']);
    }

    public function actionScript()
    {
        $script = ServerResourcesService::script();
        return $this->render('script', [
            'script' => []
        ]);
    }
}