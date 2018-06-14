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
                'avatar' => $masterUserInfo['avatar'],
                'nickName' => $masterUserInfo['nickName'],
                'roomId' => $masterUserInfo['roomId']
            ],
            'pullWapStream' => CdnUtils::getPullUrl($result['id'], false),
            'webSocket' => 'wss://' . $webSocket['roomServer-wss']['host'] . ':' . $webSocket['roomServer-wss']['port'],
            'liveInfo' => $result,
            'streamId' => $params,
            'userInfo' => [
                'userId' => 10000,
                'balance' => intval(1000000)
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