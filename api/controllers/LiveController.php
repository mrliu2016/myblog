<?php

namespace app\api\controllers;

use app\common\components\CdnUtils;
use app\common\models\Follow;
use app\common\models\User;
use app\common\models\Video;
use app\common\models\VideoRecord;
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
     * 直播人气列表-最热
     *
     * @throws \yii\db\Exception
     */
    public function actionHot()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $params['isLive'] = Constants::CODE_LIVE;
        $list = Video::queryHot($params);
        $totalCount = intval(Video::querySqlInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        if (!empty($list)) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '',
                compact('totalCount', 'page', 'size', 'pageCount', 'list')
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }

    /**
     * 最新直播
     *
     * @throws \yii\db\Exception
     */
    public function actionLatest()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        $params['isLive'] = 1;
        $list = Video::queryLatest($params);
        $totalCount = intval(Video::querySqlInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        if (!empty($list)) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '',
                compact('totalCount', 'page', 'size', 'pageCount', 'list')
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }

    /**
     * 开始直播
     */
    public function actionStartLive()
    {
        $params = Yii::$app->request->post();
        $result = Video::create($params['userId'], $params['roomId'], $params['title'], $params['imgSrc'],
            !empty($params['longitude']) ? $params['longitude'] : 0.0,
            !empty($params['latitude']) ? $params['latitude'] : 0.0);
        if (!$result) {
            $this->jsonReturnError(Constants::CODE_FAILED, '开播失败');
        }
        $this->jsonReturnSuccess(
            Constants::CODE_SUCCESS,
            '开播成功',
            [
                'streamId' => $result,
                'pushRtmp' => CdnUtils::getPushUrl($result),
                'shareUrl' => Yii::$app->params['shareUrl'] . '/wap/index?streamId=' . $result
            ]
        );
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionTerminationLive()
    {
        $params = Yii::$app->request->post();
        Video::terminationLive(intval($params['streamId']), $params['userId']);
        $result = Video::queryById($params['streamId'], true);
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

    /**
     * 我的视频
     *
     * @throws \yii\db\Exception
     */
    public function actionPlayback()
    {
        $params = Yii::$app->request->get();
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $result = VideoRecord::queryInfo($params);
        $list = Video::processLiveInfo($result, true);
        if (!empty($params['type'])) {
            $params['isLive'] = Constants::CODE_LIVE;
            $liveList = Video::queryHot($params);
            if (!empty($liveList) && !empty($list)) {
                foreach ($liveList as $key => $value) {
                    array_unshift($list, $value);
                }
            } else {
                $list = !empty($liveList) ? $liveList : $list;
            }
        }
        $totalCount = intval(VideoRecord::queryInfoNum($params));
        $pageCount = ceil($totalCount / $params['size']);
        $page = intval(!empty($params['page']) ? $params['page'] : 0);
        if (!empty($list)) {
            $this->jsonReturnSuccess(
                Constants::CODE_SUCCESS,
                '',
                compact('totalCount', 'page', 'size', 'pageCount', 'list')
            );
        }
        $this->jsonReturnError(Constants::CODE_FAILED);
    }

    /**
     * 观看次数
     */
    public function actionWatchTime()
    {
        $params = Yii::$app->request->post();
        VideoRecord::watchTime($params);
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS);
    }

    /**
     * 分享流
     */
    public function actionShareStream()
    {
        $params = Yii::$app->request->post();
        if (empty($params['streamId']) || !isset($params['streamId'])) {
            $this->jsonReturnError(-1, '流id为空');
        }
        $shareUrl = Yii::$app->params['shareUrl'] . "/wap/index?streamId=" . $params['streamId'];
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, 'success', ['shareUrl' => $shareUrl]);
    }
}