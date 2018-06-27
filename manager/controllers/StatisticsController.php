<?php

namespace app\manager\controllers;

use app\common\models\Video;
use app\common\services\Constants;
use app\common\services\StatisticsService;
use Yii;
use yii\data\Pagination;

class StatisticsController extends BaseController
{
    const PAGE_SIZE = 10;
    public $enableCsrfValidation = false;

    private static function pagination($pageNo, $count)
    {
        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $count,
        ]);
        $pagination->setPage($pageNo);
        return $pagination;
    }

    /**
     * 统计直播
     *
     * @return string
     */
    public function actionStatistics()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $params['isLive'] = Constants::CODE_LIVE;
        $itemList = Video::queryInfo($params);
        $itemList = StatisticsService::processLiveRoom($itemList);
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('statistics',
            [
                'itemList' => $itemList,
                'connectionNum' => StatisticsService::getConnectionNum(),
                'pagination' => self::pagination($pageNo, $count),
                'params' => Yii::$app->request->getQueryParams(),
                'count' => $count,
            ]
        );
    }
}