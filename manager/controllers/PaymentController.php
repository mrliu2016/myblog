<?php

namespace app\manager\controllers;

use app\common\services\Constants;
use yii\web\Controller;
use app\common\models\TestPayment;
use app\common\models\TestOrder;

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
        $params = array('price' => 0.01, 'goodsid' => 55, 'userid' => 65);
        $dat = TestOrder::queryAll($params);
        if ($dat['code'] == 1) {
            //微信支付
            $data = array(
                'price' => $dat['price'],
                'orderIdAlias' => $dat['orderIdAlias'],
                'goodsid' => $dat['goodsid']
            );
            $code_url = TestPayment::WeiXinNativePay($data);
            if (!empty($code_url)) {
                return $this->render('placeanorder', [
                    'codeurl' => $code_url,
                ]);
            }
        }
        echo "二维码获取失败";
    }
}