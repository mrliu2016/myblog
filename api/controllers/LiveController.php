<?php

namespace app\api\controllers;

use app\common\components\CdnUtils;
use app\common\models\Follow;
use app\common\models\User;
use app\common\models\Video;
use app\common\services\Constants;
use app\common\services\LiveService;
use Yii;

class LiveController extends BaseController
{
    const PAGE_SIZE = 15;

    public function actionError()
    {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    /**
     * 直播人气列表
     */
    public function actionHot()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $params['isLive'] = 1;
        $list = Video::queryHot($params);
        $totalCount = intval(Video::queryInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '',
            compact('totalCount', 'page', 'size', 'pageCount', 'list')
        );
    }

    /**
     * 开始直播
     */
    public function actionStartLive()
    {
        $params = Yii::$app->request->post();
        $result = Video::create($params['userId'], $params['userId'], $params['title'], $params['imgSrc']);
        if (!$result) {
            $this->jsonReturnError(Constants::CODE_FAILED, '开播失败');
        }
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '开播成功',
            [
                'liveId' => $result,
                'pushRtmp' => CdnUtils::getPushUrl($result)
            ]
        );
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionTerminationLive()
    {
        $params = Yii::$app->request->post();
        Video::terminationLive(intval($params['liveId']), $params['userId']);
        $result = Video::findLastRecord($params['userId'], $params['userId']);
        $userInfo = User::queryById($params['userId']);
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '结束直播',
            [
                'isAttention' => intval(Follow::isAttention($params['userId'], $params['observerUserId']) ? 1 : 0),
                'avatar' => $userInfo['avatar'],
                'nickName' => $userInfo['nickName'],
                'count' => $result->viewerNum
            ]
        );
    }
}