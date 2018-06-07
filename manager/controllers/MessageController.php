<?php

namespace app\manager\controllers;

use app\common\components\AHelper;
use app\common\models\KeyWord;
use app\common\models\Message;
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

    const PAGE_SIZE = 10;
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
        if (Yii::$app->request->isPost) {
            if (!empty($_POST['userId'])) {
                Message::send($_POST['type'], $_POST['userId'], $_POST['message']);
            }
        }
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Message::queryInfo($params);
        $count = Message::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);

        return $this->render('index', []);
    }

    public function actionKey()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = KeyWord::queryInfo($params);
        $count = KeyWord::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('key', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    //消息推送
    public function actionMessage(){

        $params = Yii::$app->request->post();

        $data = $params['data'];


        foreach ($data as $key => $val){

        }

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
    public function actionSelectPage(){

//        $params = Yii::$app->request->getQueryParams();
//        $params['defaultPageSize'] = self::PAGE_SIZE;
//        $result = User::queryMessageUserInfo($params);
//        $count = User::queryUserInfoNum($params);
//
//        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
//
//        return $this->render('select-page', [
//            'itemList' => $result,
//            'pagination' => self::pagination($pageNo, $count),
//            'params' => Yii::$app->request->getQueryParams(),
//            'pageNo'=>$pageNo,
//            'count' => $count
//        ]);

        return $this->render('select-page');

    }

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
}
