<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\GiftService;
use Yii;

class GiftController extends BaseController
{
    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    /**
     * 礼物列表
     */
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $result = GiftService::getGiftList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    public function actionContribution()
    {
        $params = Yii::$app->request->get();
    }
}