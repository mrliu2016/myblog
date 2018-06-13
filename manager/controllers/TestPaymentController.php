<?php
/**
 * Created by PhpStorm.
 * User: 左撇子
 * Date: 2018/6/13
 * Time: 21:38
 */
namespace app\manager\controllers;

use Yii;
use yii\data\Pagination;
use app\common\models\TestWeixin;

class TestPaymentController extends BaseController
{

     public function actionWeixinRefund(){
           $params = array(
               'price'=>1,
               'userid'=>55,
           );
           TestWeixin::WeixinRefund($params);

     }


}