<?php

namespace app\api\controllers;

use app\common\models\Blacklist;
use app\common\services\Constants;
use Yii;

class BlacklistController extends BaseController
{
    /**
     * 拉黑
     */
    public function actionBlacklist()
    {
        $params = Yii::$app->request->post();
        Blacklist::pullBlacklist($params); // 拉黑
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '拉黑成功！');
    }

    /**
     * 取消拉黑
     */
    public function actionCancelBlacklist()
    {
        $params = Yii::$app->request->post();
        Blacklist::cancelBlacklist($params); // 取消拉黑
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '取消拉黑');
    }
}