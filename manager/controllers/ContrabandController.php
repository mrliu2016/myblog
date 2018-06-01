<?php
namespace app\manager\controllers;

use app\common\models\Contraband;
use Yii;
use yii\data\Pagination;

class ContrabandController extends BaseController{

    const PAGE_SIZE = 5;
    private static function pagination($pageNo, $count)
    {
        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $count,
        ]);
        $pagination->setPage($pageNo);
        return $pagination;
    }

    //违禁词管理
    public function actionList(){

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $list = Contraband::queryInfo($params);
        $count  = Contraband::queryInfoNum($params);

        return $this->render('list', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    //违禁词编辑
    public function actionEditWord(){

        $id = Yii::$app->request->get('id');
        return $this->render('edit-word',[
            'id'=>$id
        ]);
    }
    //违禁词删除
    public function actionDeleteWord(){
        $id = Yii::$app->request->get('id');
        $id = Contraband::deleteWord($id);
        if ($id) {
            Yii::$app->getResponse()->redirect('/contraband/list');
        }
    }

    public function actionWordSubmit(){
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if(Contraband::editWord($params)){
                $this->jsonReturnSuccess(0,'编辑成功.');
            }
            else{
                $this->jsonReturnError(-1,'编辑失败.');
            }
        }
    }

}