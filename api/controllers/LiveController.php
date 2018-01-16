<?php

namespace app\api\controllers;

use app\common\models\Video;
use app\common\services\Constants;
use app\common\services\UserService;
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

    public function actionStartLive()
    {
        $params = Yii::$app->request->post();
        $result = Video::create($params['userId'], $params['userId'], $params['title'], $params['imgSrc']);
        if (!$result) {
            $this->jsonReturnError(Constants::CODE_FAILED, '开播失败');
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '开播成功', ['liveId' => $result]);
    }

    public function actionTerminationLive()
    {
        $params = Yii::$app->request->post();
        ll($params, __FUNCTION__ . '.log');
        $result = Video::terminationLive(intval($params['liveId']), $params['userId']);
        ll($result, __FUNCTION__ . '.log');
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, '结束直播');
    }
}