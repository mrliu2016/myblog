<?php

namespace app\manager\controllers;

use app\common\components\AHelper;
use app\common\extensions\RongCloud\RongCloud;
use app\common\models\User;
use Yii;
use yii\data\Pagination;

class MessageController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    const PAGE_SIZE = 5;
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

    //消息推送首页
    public function actionIndex()
    {
        $user = User::queryAllUserId();
        $userStr = '';
        foreach ($user as $val){
            $userStr .= $val['id'].',';
        }
        $userStr = trim($userStr,',');
        return $this->render('index', [
            'userStr'=>$userStr
            ]);
    }


    //消息推送
    public function actionMessage(){

        $params = Yii::$app->request->post();

        $data = $params['data'];
        $roomId = $params['roomId'];
        $url = Yii::$app->params['shareUrl'].'/server/location?roomId='.$roomId;//http://dev.api.customize.3ttech.cn/server/location
        $result = AHelper::curl_get($url);
        $result = json_decode($result,true);

        if(!empty($result) && !empty($result['data']['roomServer'])){
            $roomServer = $result['data']['roomServer'];
            $host = $roomServer['host'];
            $port = $roomServer['port'];
            $url = 'http://'.$host.':'.$port;
            $data = array(
                'message' => $params['message'],
            );
            AHelper::curlPost($url,json_encode($data));
            $this->jsonReturnSuccess(0);
        }
        else{
            $this->jsonReturnError(-1);
        }
    }
    //选择用户测试
//    public function actionSelectPage(){
//
//        return $this->render('select-page');
//
//    }
    //分页
    public function actionPage(){

        $params = Yii::$app->request->post();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $page = $params['page'];
        $list = User::queryMessageUserInfo($params);
        $count = User::queryUserInfoNum($params);
//        print_r($count);die;
        $pageBanner = $this->pageBanner($page,$count,10,5,'handPaging','select');
        $data = array();
        $data['pageBanner'] = $pageBanner;
        $data['list'] = $list;
        $data['pageNo'] = $page;
        $data['count'] = $count;
        $this->jsonReturnSuccess(0,$data);

    }

    public function pageBanner($page,$total,$pagesize = 10,$showPage = 5,$method,$act){

        $total_pages   = ceil($total/$pagesize);//总页数
        $pageBanner = '';
        if($page>1){
            /*$pageBanner.= "<a class='c-btn s-gift-page' href='javascript:".$method."(".($page-1).")'>首页</a>";*/
            $pageBanner.= "<a class='c-btn s-gift-page s-gift-prepage' href='javascript:".$method."(".($page-1).")'>.</a>";
        }
        else{
            $pageBanner.= "<a disabled class='c-btn s-gift-page s-gift-prepage' href='javascript:".$method."(".($page-1).")'>.</a>";
        }
        //计算偏移量
        $pageoffset=($showPage-1)/2;
        //初始化数据
        $start=1;
        $end=$total_pages;

        if($total_pages>$showPage){
            if($page>$pageoffset+1){
                $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$start.")'>".$start."</a>";
                //$pageBanner.="<a href='javascript:".$method."(".$start.")'>".$start."</span>";
                $pageBanner.="<a>...</a>";
            }
            if($page>$pageoffset){
                $start=$page-$pageoffset;
                $end=$total_pages>$page+$pageoffset?$page+$pageoffset:$total_pages;
            }
            else {
                $start=1;
                $end=$total_pages>$showPage?$showPage:$total_pages;
            }
            if($page+$pageoffset>$total_pages){
                $start=$start-($page+$pageoffset-$end);
            }
        }
        for($i=$start;$i<=$end-1;$i++){
            if($page==$i){
                $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$i.")'  class='".$act."'><span>".$i."</span></a>";
            }
            else{
                $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$i.")'>".$i."</a>";
            }
        }
        //尾部省略
        if($total_pages>$showPage&& $total_pages>$page+$pageoffset){
            $pageBanner.="<a>...</a>";
        }
        if($page<$total_pages){
            $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$total_pages.")'>".$total_pages."</a>";
            $pageBanner.="<a class='c-btn s-gift-page s-gift-nextpage' href='javascript:".$method."(".($page+1).")'>.</a>";
            /*$pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$total_pages.")'>尾页</a>";*/

        }
        if($page==$total_pages){
            $pageBanner.="<a class='c-btn s-gift-page' href='javascript:".$method."(".$total_pages.")' class='".$act."'><span>".$total_pages."</span></a>";
        }
        return $pageBanner;
    }

    //查询
    public function actionSearch(){

        $params = Yii::$app->request->post();
        $params['defaultPageSize'] = 20;
        $list = User::queryMessageUserInfo($params);

        $count = User::queryUserInfoNum($params);
        $data = array();
        $data['list'] = $list;
//        $data['pageNo'] = $page;
        $data['count'] = $count;
        $this->jsonReturnSuccess(0,$data);
    }

    //发送推送消息
    public function actionSendMessage(){
        $params = Yii::$app->request->post();
        if(empty($params) && !isset($params['data']) && !isset($params['message'])){
            return $this->jsonReturnError(-1,'请求参数错误.');
        }

        if(empty($params['data'])){
            $this->jsonReturnError(-3,'未选择推送用户!');
        }
        $toUserIds = explode(',',$params['data']);

        if(!empty($toUserIds) && count($toUserIds) > 100){
            $this->jsonReturnError(-2,'选择的用户多余100人,推送失败！');
        }
        unset($params['data']);
        $config = Yii::$app->params['rongCloud'];
        $rongCloud = new RongCloud($config['appKey'], $config['appSecret']);
        $message = $rongCloud->message();
        //添加一个100
        $fromUserId = 'admin';
//        $toUserId=  array('800010','800042');
        $toUserId=  $toUserIds;
        $objectName = 'RC:TxtMsg';
        $content = array(
            'content' => $params['message']
        );
        $content = json_encode($content);
        $pushContent = 'thisisapush';
        $push = array(
            'pushData' => $params['message']
        );
        $pushData = json_encode($push);

        $result = $message->PublishSystem($fromUserId, $toUserId, $objectName, $content,$pushContent,$pushData,1,1);
//        content={\"content\":\"c#hello\"}&fromUserId=2191&toUserId=2191&toUserId=2192&objectName=RC:TxtMsg&pushContent=thisisapush&pushData={\"pushData\":\"hello\"}

        $result = json_decode($result,true);
        if(isset($result) && $result['code'] == 200){
            $this->jsonReturnSuccess(0);
        }
        else{
            $this->jsonReturnError(-1);
        }
    }
}
