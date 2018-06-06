<?php

namespace app\manager\controllers;

use app\common\models\Order;
use app\common\models\Report;
use app\common\models\User;
use app\common\models\Video;
use app\common\services\Constants;
use Yii;
use yii\data\Pagination;

class UserController extends BaseController
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

    public function actionIndex()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = User::queryUserInfo($params);

        if(empty($params['id'])){
            unset($params['id']);
        }
        foreach ($result as $key => &$val){
            if(!empty($val['realName']) && !empty($val['idCard']) && !empty($val['mobile'])){
                $val['isValid'] = 1;
            }
            else{
                $val['isValid'] = 0;
            }
            $params['userId'] = $val['id'];
            $liveCount = Video::queryInfoNum($params);
            $val['liveCount'] = $liveCount;//直播次数
            $reportCount= Report::queryInfoNum($params);
            $val['reportCount'] = $reportCount;
            //收到礼物
            $receiveValue = Order::queryReceiveGiftByUserId($val['id'],true);
            if(empty($receiveValue) || empty($receiveValue['totalPrice'])){
                $val['receiveValue'] = 0;
            }
            else{
                $val['receiveValue'] = $receiveValue['totalPrice'];
            }
            //送出礼物
            $sendValue = Order::queryReceiveGiftByUserId($val['id'],false);
            if(empty($sendValue) || empty($sendValue['totalPrice'])){
                $val['sendValue'] = 0;
            }
            else{
                $val['sendValue'] = $sendValue['totalPrice'];
            }
        }

        $count = User::queryUserInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionLogout()
    {
        setcookie(Constants::COOKIE_UNIFIED_LOGIN, null, time() - 1000, "/", Constants::COOKIE_DOMAIN);
        $loginUrl = Yii::$app->params["ucDomain"] . "/user/login?redirect=" . Yii::$app->request->getHostInfo();
        Yii::$app->getResponse()->redirect($loginUrl);
    }

    public function actionDepositIdealMoney()
    {
        $params = Yii::$app->request->post();
        $result = User::depositIdealMoney($params['userId'], $params['idealMoney']);
        $result ?
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, ['result' => $result]) :
            $this->jsonReturnError(Constants::CODE_FAILED);
    }

    //用户详情
    public function actionDetail(){

        $params = Yii::$app->request->get();
        $id = intval($params['id']);
        $user = User::queryById($id);

        $user['isAuth'] = 0;
        if(!empty($user['realName']) && !empty($user['isCard'])){
            $user['isAuth'] = 1;
        }
        //送出礼物
        $receiveValue = Order::queryReceiveGiftByUserId($id,true);
        if(empty($receiveValue) || empty($receiveValue['totalPrice'])){
            $user['receiveValue'] = 0;
        }
        else{
            $user['receiveValue'] = $receiveValue['totalPrice'];
        }
        //收到礼物
        $sendValue = Order::queryReceiveGiftByUserId($id,false);
        if(empty($sendValue) || empty($sendValue['totalPrice'])){
            $user['sendValue'] = 0;
        }
        else{
            $user['sendValue'] = $sendValue['totalPrice'];
        }
        return $this->render('detail',[
            'item'=>$user
        ]);

    }
}
