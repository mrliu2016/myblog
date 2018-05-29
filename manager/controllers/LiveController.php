<?php

namespace app\manager\controllers;

use app\common\models\User;
use app\common\models\Video;
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
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $params['isLive'] = 0;
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
            'params' => Yii::$app->request->getQueryParams(),
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
        $result = Video::queryById($params);
        echo 2222;die;
        $this->jsonReturnSuccess();
    }

    function actionForbid(){
        $params = Yii::$app->request->getQueryParams();
        $result = Video::queryById($params);
        echo 1111;die;
        $this->jsonReturnSuccess();
    }
}
