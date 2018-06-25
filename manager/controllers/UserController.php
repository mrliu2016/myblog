<?php

namespace app\manager\controllers;

use app\common\components\AHelper;
use app\common\models\Order;
use app\common\models\Report;
use app\common\models\User;
use app\common\models\Video;
use app\common\services\BroadcastService;
use app\common\services\Constants;
use Yii;

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

    const PAGE_SIZE = 10;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $params['isDelete'] = 0;
        $result = User::queryUserInfo($params);
        if(empty($params['id'])){
            unset($params['id']);
        }
        //先判断状态
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
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count,
            'isAuth'=> empty($params['isAuth'])?0:$params['isAuth'],
            'page'=>BroadcastService::pageBanner('/user/index',$pageNo+1,$count,self::PAGE_SIZE,5,'s-gift-page-hover')
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

    //禁播
    public function actionNoplay(){
        $params = Yii::$app->request->post();
        $type = $params['type'];
        $messageType = '';
        $message = '';
        switch ($type){
            case 1:
                $messageType = Constants::MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_REQ ;
                $message = '禁播24h';
                break;
            case 2:
                $messageType = Constants::MESSAGE_TYPE_PROHIBIT_LIVE_30_DAYS_REQ ;
                $message = '禁播30天';
                break;
            case 3:
                $messageType = Constants::MESSAGE_TYPE_PERPETUAL_PROHIBIT_LIVE_REQ;
                $message = '永久禁播';
                break;
            case 4:
                $messageType = Constants::MESSAGE_TYPE_PROHIBIT_ACCOUNT_NUMBER_REQ;
                $message = '封禁账号';
                break;
        }
        $params['message'] = $message;
        if(User::operateNoplay($params)){
            //直播
            $liveResult = Video::isLive($params['userId']);
            if(!empty($liveResult)){//是直播
                $roomId = $params['roomId'];
                //推送信息
                $data = array(
                    'messageType'=>$messageType,
                    'data'=>array(
                        'userId'=>$params['userId'],
                        'roomId'=>$params['roomId'],
                        'message'=>$message,
                    ),
                );
                $url = Yii::$app->params['shareUrl'].'/server/location?roomId='.$roomId;//http://dev.api.customize.3ttech.cn/server/location
                $result = AHelper::curl_get($url);
                $result = json_decode($result,true);
                $roomServer = $result['data']['roomServer'];
                $host = $roomServer['host'];
                $port = $roomServer['port'];
                $url = 'http://'.$host.':'.$port;
                $result = AHelper::curlPost($url,json_encode($data));
            }

            $this->jsonReturnSuccess(0);
        }
        else{
            $this->jsonReturnError(-1);
        }
    }

    //启用
    public function actionRecovery(){
        $params = Yii::$app->request->post();
        $params['type'] = 0;

        if(User::operateRecovery($params)){
            $this->jsonReturnSuccess(0);
        }
        else{
            $this->jsonReturnError(-1);
        }
    }
}
