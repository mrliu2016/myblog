<?php

namespace app\api\controllers;

use app\common\models\Video;
use app\common\models\VideoRecord;
use app\common\services\Constants;

class TranscribeController extends BaseController
{

    /**
     * 阿里云视频录制通知
     *
     * @throws \yii\db\Exception
     */
    public function actionTranscribe()
    {
        $params = file_get_contents("php://input");
        if (!empty($params)) {
            $params = json_decode($params, true);
        } else {
            $params = [];
        }
        VideoRecord::transcribe($params);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, 'ok');
    }

    /**
     * 腾讯云视频录制通知
     *
     * @throws \yii\db\Exception
     */
    public function actionQcloudTranscribe()
    {
        $params = file_get_contents("php://input");
        if (!empty($params)) {
            $params = json_decode($params, true);
        } else {
            $params = [];
        }
        $result = Video::qCloudTranscribe(array_merge($params, $_POST));
        if ($result) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS);
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }
}