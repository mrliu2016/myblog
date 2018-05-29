<?php

namespace app\manager\controllers;

use app\common\models\Gift;
use app\common\models\Order;
use Yii;
use yii\data\Pagination;
use app\common\components\OSS;
use app\common\extensions\OSS\OssClient;

class GiftController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    const PAGE_SIZE = 15;
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

    public function actionTemplate()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Gift::queryInfo($params);
        $count = Gift::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('template', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionGiftDelete()
    {
        $id = Yii::$app->request->get('id');
        $id = Gift::deleteGift($id);
        if ($id) {
            Yii::$app->getResponse()->redirect('/gift/template');
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            if (!empty($_FILES['imgSrc']['tmp_name'])) {

                $src = (new OSS())->upload($_FILES['imgSrc']['tmp_name'], $_FILES['imgSrc']['name'], 'gift');
            }
            $params = Yii::$app->request->post();
            $params['imgSrc'] = $src;
            if (Gift::created($params)) {
                Yii::$app->getResponse()->redirect('/gift/template');
            }
        } else {
            return $this->render('create');
        }
    }

    public function actionOrder()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Order::queryInfo($params);
        $count = Order::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('order', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }
    //礼物编辑
    public function actionGiftEdit(){
        $params = Yii::$app->request->getQueryParams();

        $id = $params['id'];
        return $this->render('gift-edit',['id'=>$id]);
    }

    //礼物详情
    public function actionDetail(){
        $params = Yii::$app->request->getQueryParams();
        //通过id查询礼物的详情
        $id = $params['id'];
        $result = Gift::queryById($id,false);
        return $this->render('detail',[
            'list'=>$result
        ]);
    }
}
