<?php

namespace app\api\controllers;

use app\common\components\UploadUtils;
use app\common\services\Constants;

class UploadFileController extends BaseController
{
    /**
     * 上传单张图片
     */
    public function actionUploadSingle()
    {
        $result = UploadUtils::multiUploadPicture();
        $imgSrc = UploadUtils::getUploadFileUrlByOne();
        if ($result === true) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '上传成功',
                [
                    'imgSrc' => $imgSrc
                ]
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED, '上传失败');
    }
}