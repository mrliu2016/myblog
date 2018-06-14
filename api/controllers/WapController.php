<?php

namespace app\api\controllers;

use app\common\components\CdnUtils;
use app\common\models\User;
use app\common\services\Constants;
use app\common\services\LiveService;
use app\common\services\VideoService;
use Yii;

class WapController extends BaseController
{
    /**
     * 分享
     *
     * @return string
     */
    public function actionIndex()
    {
        $masterUserInfo = [];
        $this->layout = '@app/views/layouts/wap.php';
        $params = Yii::$app->request->get();
        $result = VideoService::queryByStreamId($params['streamId']);
        if (!empty($result)) {
            $masterUserInfo = User::queryById($result['userId']);
        }
        $webSocket = LiveService::serverInfo(['roomId' => $params['streamId']]);
        return $this->render('index', [
            'masterUserInfo' => [
                'userId' => $masterUserInfo['userId'],
                'balance' => intval($masterUserInfo['balance']),
                'income' => $masterUserInfo['income'],
                'expenditure' => $masterUserInfo['expenditure'],
                'avatar' => !empty($masterUserInfo['avatar']) ? $masterUserInfo['avatar'] : Yii::$app->params['defaultAvatar'],
                'nickName' => $masterUserInfo['nickName'],
                'roomId' => $masterUserInfo['roomId'],
                'masterLevel' => 1
            ],
            'pullWapStream' => CdnUtils::getPullUrl($result['id'], false),
            'webSocket' => 'wss://' . $webSocket['roomServer-wss']['host'] . ':' . $webSocket['roomServer-wss']['port'],
            'liveInfo' => $result,
            'streamId' => $params,
            'userInfo' => [
                'userId' => 300001,
                'avatar' => Yii::$app->params['defaultAvatar'],
                'balance' => intval(1000000),
                'role' => 0,
                'level' => 1,
                'nickName' => __FUNCTION__
            ],
            'shareUrl' => Yii::$app->params['shareUrl'] . '/wap/index?streamId=' . $result['id']
        ]);
    }

    /**
     * 分享
     *
     * @return string
     */
    public function actionMpWeb()
    {
        return $this->render('mp-web', [

        ]);
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }
}