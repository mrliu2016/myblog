<?php

namespace app\manager\controllers;

use app\common\models\Deposit;
use Yii;
use yii\data\Pagination;

class DepositController extends BaseController
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
     * 充值记录
     */
    public function actionRecord()
    {
        $params = Yii::$app->request->getQueryParams();
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $params['defaultPageSize'] = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;
        $result = Deposit::depositRecord($params,
            'id,price,orderIdAlias,source,status,FROM_UNIXTIME(orderCreateTime) as created,orderPayTime');
        $count = Deposit::queryInfoNum($params);
        return $this->render('deposit_record', [
            'itemList' => $result['data']['list'],
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionWithdraw()
    {
        return $this->render('withdraw', []);
    }
}