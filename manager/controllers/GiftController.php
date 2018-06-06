<?php

namespace app\manager\controllers;

use app\common\models\Gift;
use app\common\models\GiftFire;
use app\common\models\Order;
use Yii;
use yii\data\Pagination;
use app\common\components\OSS;

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

    //礼物编辑
    public function actionGiftEdit(){
        $params = Yii::$app->request->getQueryParams();
        $id = $params['id'];

        $item = Gift::queryById($id);

        return $this->render('gift-edit',['item'=>$item]);
    }

    //礼物详情
    public function actionDetail(){
        $params = Yii::$app->request->getQueryParams();
        //通过id查询礼物的详情
        $id = $params['id'];
        $result = Gift::queryById($id,false);

//        print_r($result);die;
        return $this->render('detail',[
            'item'=>$result
        ]);
    }

    //礼物编辑提交
    public function actionGiftSubmit(){
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if(Gift::editGift($params)){
                $this->jsonReturnSuccess(0,'编辑成功.');
//                Yii::$app->getResponse()->redirect('/gift/template');
            }
            else{
                $this->jsonReturnError(-1,'编辑失败.');
            }
        }
    }

    //连发设置
    public function actionSetting()
    {
        $result = array();
        //查询到数据
        $result = GiftFire::selectGiftFire();
        $editFlag = 0;
        if(!empty($result) && isset($result)){
            $editFlag = 1;
        }
        return $this->render('setting',[
            'editFlag'=>$editFlag,
            'list'=>$result
        ]);
    }

    //连发设置 保存
    public function actionSettingSave(){

        $params = Yii::$app->request->post();
        $ids = explode(',',$params['id']);
        $number = explode(',',$params['number']);
        $meaning = explode(',',$params['meaning']);
        if(count($number)!= 6 || count($meaning) != 6){
            $this->jsonReturnError('-2','输入错误');
        }
        $item = array();
        $data = array();
        for ($i=0;$i<6;$i++){
            $item['id'] = $ids[$i];
            $item['number'] = $number[$i];
            $item['meaning'] = $meaning[$i];
            $data[] = $item;
        }
        $result = GiftFire::batchInsertGiftFire($data);
        if($result['code'] == 0){
            $this->jsonReturnSuccess(0,'success');
        }
        else{
            $this->jsonReturnError(-1);
        }
    }

}
