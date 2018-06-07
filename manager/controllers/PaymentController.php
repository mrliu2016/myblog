<?php

namespace app\manager\controllers;

use app\common\services\Constants;
use yii\web\Controller;
use app\common\models\TestPayment;

class PaymentController extends Controller
{
    /**
     * 模拟live的
     * */
    public function actionWeixinAppPay()
    {
        $params = array(
            'userid' => 10020,
            'price' => 45.5,
            'goodsid' => 12,
            //'orderid'=>1
        );
        $data = TestPayment::weiXinPay($params);
        echo json_encode($data);
    }

    /**
     * 模拟space的二维码
     *
     * */
    public function actionWeixinNativePay()
    {
        $this->layout = false;
        $params = array('price' => 21.05, 'goodsid' => 55, 'userid' => 65);
        //微信支付
        TestPayment::WeiXinNativePay($params);

        die;
        return $this->render('placeanorder', [

        ]);
    }


}