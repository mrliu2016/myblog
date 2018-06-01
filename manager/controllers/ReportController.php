<?php

namespace app\manager\controllers;

use app\common\models\Report;
use app\common\models\User;
use app\common\services\LiveService;
use Yii;
use yii\data\Pagination;

//举报管理
class ReportController extends BaseController
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

    //举报管理
    public function actionReport(){

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;

        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $listArr = array();
        if(!empty($params['nickName'])){
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
//            print_r($result);die;
            unset($params['nickName']);
            foreach ($result as $k => $v){
//                $params['reportedUserId'] = $v['id'];
                $list = Report::queryReportInfoByReportedUserId($v['id']);
                foreach ($list as $elem) {
                    $user = User::queryById($elem['userId']);
                    $elem['nickName'] = $user['nickName'];
                    $elem['reportName'] = $v['nickName'];
                    $listArr[] = $elem;
                }
            }
            //对举报信息排序
            $pageSize = intval($params['defaultPageSize']);
            $start_offset = $pageNo * $pageSize;
            $end_offset = ($pageNo+1) * $pageSize;
            $listArr = LiveService::reportSort($listArr);
            $list = array_slice($listArr,$start_offset,$end_offset);
            $count = count($listArr);
        }
        else{
            $list = Report::queryInfo($params);
            foreach ($list as $key => &$val){
                //查询到举报用户 被举报用户的昵称
                $user = User::queryById($val['userId']);
                $val['nickName'] = $user['nickName'];
                $reportUser = User::queryById($val['reportedUserId']);
                $val['reportName'] = $reportUser['nickName'];
            }
            $count  = Report::queryInfoNum($params);
        }

//        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('report', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

}
