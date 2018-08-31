<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\LiveService;
use app\common\services\ServerResourcesService;
use Yii;

class ServerController extends BaseController
{
    public function actionLocation()
    {
        $params = Yii::$app->request->get();
        if (isset($params['appId'])) {
            if ($params['appId'] == '555e189bf1278119c78f6b1753bfe4b5') {
                $this->jsonReturnSuccess(
                    Constants::CODE_SUCCESS,
                    '获取成功',
                    LiveService::serverInfo($params)
                );
            } else {
                $result = ServerResourcesService::getWebSocketServer($params, null, true);
                if ($result['code'] == Constants::CODE_SUCCESS) {
                    ServerResourcesService::roomServerList($params, $result);
                    ServerResourcesService::setAppIdRoomMap($result, $params);
                    $this->jsonReturnSuccess(
                        Constants::CODE_SUCCESS,
                        '获取成功',
                        [
                            'roomServer' => [
                                'ip' => $result['ip'],
                                'host' => $result['domain'],
                                'port' => $result['wsPort'],
                            ],
                            'roomServer-wss' => [
                                'ip' => $result['ip'],
                                'host' => $result['domain'],
                                'port' => $result['wssPort'],
                            ]
                        ]
                    );
                }
            }
        } else {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '获取成功',
                LiveService::serverInfo($params)
            );
        }
        $this->jsonReturnError(Constants::CODE_SYSTEM_BUSY, '系统繁忙，请稍后重试!');
    }
}