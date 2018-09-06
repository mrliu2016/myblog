<?php

namespace app\manager\controllers;

use app\common\models\Report;
use app\common\models\ReportOptions;
use app\common\models\User;
use app\common\services\BroadcastService;
use Yii;

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

    const PAGE_SIZE = 10;
    public $enableCsrfValidation = false;

    //举报管理
    public function actionIndex()
    {
        if (empty(Yii::$app->controller->user)) {
            return $this->redirect('/index/login');
        }
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;

        if (!empty($params['startTime']) && strtotime($params['startTime']) <= strtotime('1970-1-1')) {
            $params['startTime'] = '1970-1-1';
        }
        if (!empty($params['endTime']) && strtotime($params['endTime']) <= strtotime('1970-1-1')) {
            $params['endTime'] = '1970-1-1';
        }

        if (!empty($params['endTime'])) {
            $params['endTime'] = date('Y-m-d', strtotime($params['endTime']) + 86400);
        }

        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $listArr = array();
        if (!empty($params['nickName'])) {
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v) {
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
            $end_offset = ($pageNo + 1) * $pageSize;
            $listArr = Report::reportSort($listArr);
            $list = array_slice($listArr, $start_offset, $end_offset);
            $count = count($listArr);
        } else {
            $list = Report::queryInfo($params);
            foreach ($list as $key => &$val) {
                //查询到举报用户 被举报用户的昵称
                $user = User::queryById($val['userId']);
                $val['nickName'] = $user['nickName'];
                $reportUser = User::queryById($val['reportedUserId']);
                $val['reportName'] = $reportUser['nickName'];
            }
            $count = Report::queryInfoNum($params);
        }
//        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $list,
            'params' => $params,
            'count' => $count,
            'page' => BroadcastService::pageBanner('/report/index', $pageNo + 1, $count, self::PAGE_SIZE, 5, 's-gift-page-hover')
        ]);
    }

    //设置举报类型
    public function actionSetType()
    {
        //查询到数据
        $result = ReportOptions::selectReportType();
        $editFlag = 0;
        if (!empty($result) && isset($result)) {
            $editFlag = 1;
        }
        return $this->render('set-type', [
            'editFlag' => $editFlag,
            'list' => $result
        ]);
    }

    //举报类型设置保存
    public function actionSetSave()
    {

        $params = Yii::$app->request->post();
        if (empty($params['id']) || empty($params['content'])) {
            $this->jsonReturnError(-1);
        }
        $ids = explode(',', $params['id']);
        $content = explode(',', $params['content']);
        $item = array();
        $data = array();

        for ($i = 0; $i < 5; $i++) {
            $item['id'] = $ids[$i];
            $item['content'] = $content[$i];
            $data[] = $item;
        }
        $result = ReportOptions::saveReportType($data);
        if ($result['code'] == 0) {
            $this->jsonReturnSuccess(0, 'success');
        } else {
            $this->jsonReturnError(-1);
        }
    }
}
