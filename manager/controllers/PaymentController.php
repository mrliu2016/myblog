<?php

namespace app\manager\controllers;

use app\common\services\Constants;
use yii\web\Controller;
use app\common\models\TestPayment;

class PaymentController extends Controller {

    public function actionWeixinAppPay(){
        $params = array(
            'userid'=>10020,
            'price'=>45.5,
            'goodsid'=>12,
            //'orderid'=>1
        );
        $data = TestPayment::weiXinPay($params);
        echo json_encode($data);
    }

}