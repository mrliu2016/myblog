<?php

namespace app\manager\controllers;

use app\common\components\AHelper;
use app\common\models\User;
use app\common\models\Video;
use app\common\models\VideoRecord;
use app\common\services\Constants;
use Yii;
use yii\data\Pagination;

class LiveController extends BaseController
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

    /**
     * 直播管理  直播查看   昵称查询最好去掉
     * 先查询数据，再过滤nickName
     * 按照昵称查询的  先查询用户表，再查询直播表
     * 昵称为空的，先查询直播表，再查询用户表
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $params['isLive'] = 1;
        $list = array();
        //通过昵称查询
        if(!empty($params['nickName'])){
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v){
                $params['userId'] = $v['id'];
                $list = Video::queryInfo($params);
                foreach ($list as &$elem){
                    $elem['nickName'] = $v['nickName'];
                }
            }
        }
        else{
            $list = Video::queryInfo($params);
            foreach ($list as &$val){
                $userInfo = User::queryById($val['userId']);
                $val['nickName'] = $userInfo['nickName'];
            }
        }

        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    //直播记录
    public function actionLiveRecord(){
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $params['isLive'] = 2;
        $list = array();
        //通过昵称查询
        if(!empty($params['nickName'])){
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v){
                $params['userId'] = $v['id'];
                $list = VideoRecord::queryInfo($params);
                foreach ($list as &$elem){
                    $elem['nickName'] = $v['nickName'];
                }
            }
        }
        else{
            $list = VideoRecord::queryInfo($params);
            foreach ($list as &$val){
                $userInfo = User::queryById($val['userId']);
                $val['nickName'] = $userInfo['nickName'];
            }
        }

        $count = VideoRecord::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('live-record', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionHot()
    {
        $params['defaultPageSize'] = $size = intval(!empty($params['size']) ? $params['size'] : self::PAGE_SIZE);
        $params['page'] = intval(!empty($params['page']) ? $params['page'] : 0);
        $params['isLive'] = 1;
        $result = Video::queryHot($params);
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $this->layout = '@app/views/layouts/wenlian.php';
        return $this->render('hot', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionRecord()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Video::queryInfo($params);
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('record', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    public function actionYellow(){
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $params['type'] = 3;
        $list = array();
        //通过昵称查询
        if(!empty($params['nickName'])){
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v){
                $params['userId'] = $v['id'];
                $list = Video::JianYellow($params);
                foreach ($list as &$elem){
                    $elem['nickName'] = $v['nickName'];
                }
            }
        }
        else{
            $list = Video::JianYellow($params);
            foreach ($list as &$val){
                $userInfo = User::queryById($val['userId']);
                $val['nickName'] = $userInfo['nickName'];
            }
        }
//        $result = Video::JianYellow($params);
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('yellow',[
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }
    //鉴黄查看
    public function actionYellowCheck(){

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $id = $params['id'];
        //通过id获取鉴黄信息
        $result = Video::JianYellowById($id);

        return $this->render('yellow-check',[
            'itemList' => $result,
            'yellowurl' => $result['yellowurl'],
            'information' => $result['information'],
        ]);

    }

     public function actionList()
    {
        $this->layout = '@app/views/layouts/wenlian.php';
        return $this->render('list');
    }


    //查看  获取视频的地址
    public function actionCheck(){
        $params = Yii::$app->request->getQueryParams();
        $id = $params['id'];
        $result = Video::queryById($id);
        $videoSrc = $result['videoSrc'];
        return $this->render('check',[
            'videoSrc'=>$videoSrc
        ]);

    }

    //查看回访
    public function actionPlayBack(){
        $params = Yii::$app->request->get();
        $id = $params['id'];
        $result = VideoRecord::queryById($id);
        $videoSrc = $result['videoSrc'];
        return $this->render('play-back',[
            'videoSrc'=>$videoSrc
        ]);
    }

    //禁播
    public function actionNoplay(){
        $params = Yii::$app->request->post();

//        print_r($params);die;
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
            $isLive = $params['isLive'];
//            if($isLive){//是直播
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
//            }
//            else{//发送系统消息
//
//            }
            $this->jsonReturnSuccess(0);
        }
        else{
            $this->jsonReturnError(-1);
        }
    }
}
