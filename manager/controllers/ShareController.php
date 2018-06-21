<?php
namespace app\manager\controllers;

use app\common\models\ShareSlogan;
use Yii;

class ShareController extends BaseController{

    public function actionIndex(){
        $result = ShareSlogan::queryShareSlogan();

        return $this->render('index',[
            'item'=>$result
        ]);
    }

    public function actionTitleSave(){
        $params = Yii::$app->request->post();
        $result = ShareSlogan::udpateShareSlogan($params);
        if($result['code'] == 0){
            $this->jsonReturnSuccess(0,'编辑成功.');
        }
        else{
            $this->jsonReturnError(-1,'编辑错误');
        }
    }
}