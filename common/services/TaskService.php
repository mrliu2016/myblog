<?php

namespace app\common\services;


use app\common\models\Application;
use app\common\models\Income;
use app\common\models\Task;
use app\common\models\TaskCounter;
use app\common\models\TaskDetail;
use app\common\models\TaskMember;
use app\common\models\User;

class TaskService
{
    /**
     * 创建任务
     * @param $params
     * @return array
     */
    public static function createTask($params)
    {
        $data = array();
        if (!isset($params['unionId']) || !isset($params['price']) || !isset($params['startTime']) || !isset($params['endTime'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $aid = $params['aid'];
        $unionId = $params['unionId'];
        $name = $params['name'];
        $price = $params['price'] * Constants::CENT;
        $startTime = $params['startTime'];
        $endTime = $params['endTime'];
        $days = isset($params['days']) ? $params['days'] : 0;
        $description = isset($params['description']) ? $params['description'] : '';
        //创建任务
        $task = Task::create($aid, $name, $price, $startTime, $endTime, $days, $description);
        $data["taskId"] = $task->id;
        //创建任务成员
        TaskMember::create($task->id, $unionId, 1);
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }

    /**
     * 加入任务
     * @param $params
     * @return array
     */
    public static function joinTask($params)
    {
        $data = array();
        if (!isset($params['unionId']) || !isset($params['taskId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $unionId = $params['unionId'];
        $taskId = $params['taskId'];
        $task = Task::queryById($taskId);
        if (!empty($task)) {
            //创建任务成员
            TaskMember::create($taskId, $unionId);
            return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
        } else {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'Task does not exist'];
        }
    }

    /**
     * 任务成员列表
     * @param $params
     * @return array
     */
    public static function taskMemberList($params)
    {
        $data = array();
        if (!isset($params['taskId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $taskId = $params['taskId'];
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
        //创建任务成员
        $list = TaskMember::queryAllByTaskIdWithPager($taskId, $page, $pageSize);
        $data = $list;
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }

    /**
     * 我的任务列表
     * @param $params
     * @return array
     */
    public static function myTaskList($params)
    {
        $data = array();
        if (!isset($params['unionId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $unionId = $params['unionId'];
        $role = isset($params['role']) ? (int)$params['role'] : 1; //1发起 0参与
        $type = isset($params['type']) ? (int)$params['type'] : 1; //0未开始，1进行中，2已结束
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
        //我的任务列表
        $myTaskList = Task::myTaskListWithPager($unionId, $role, $type, $page, $pageSize);
        $list = array();
        foreach ($myTaskList['list'] as $key => $task) {
            $item = array();
            $item['id'] = $task['id'];
            $item['name'] = $task['name'];
            $originator = TaskMember::getOriginatorByTaskId($task['id']);
            $item['originator'] = $originator['nickName'];
            $item['startTime'] = date('Y-m-d H:i:s', $task['startTime']);
            $item['endTime'] = date('Y-m-d H:i:s', $task['endTime']);
            $taskStatus = "";
            $failReason = "";
            $CurrMember = TaskMember::getMemberByUnionId($task['id'], $unionId);
            if ($CurrMember['status'] == 1) {
                $taskStatus = "成功";
            } else if ($CurrMember['status'] == -1) {
                $taskStatus = "失败";
                if ($task['startTime'] < time()) {
                    //任务失败，任务开始时间小于当前时间，提示：人数不足
                    $failReason = "人数不足";
                }
            }
            $item['taskStatus'] = $taskStatus;
            $item['failReason'] = $failReason;
            $item['sumMember'] = (int)TaskMember::getMemberCount($task['id']);
            //今日打卡
            //$signed = TaskDetail::findSignedByToDay($task['id'], $unionId);
            $list[] = $item;
        }
        $myTaskList['list'] = $list;
        //任务列表
        $data['task'] = $myTaskList;

        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }

    /**
     * 我的任务列表
     * @param $params
     * @return array
     */
    public static function myIncomeList($params)
    {
        if (!isset($params['unionId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
        $unionId = $params['unionId'];
        $data = Income::queryAllByUnionIdWithPager($unionId, $page, $pageSize);
        $list = array();
        foreach ($data['list'] as $key => $income) {
            $item = array();
            $item['taskType'] = "发起";
            $item['taskId'] = $income['taskId'];
            $item['taskName'] = $income['taskName'];
            $item['startTime'] = date('Y-m-d H:i:s', $income['startTime']);
            $item['endTime'] = date('Y-m-d H:i:s', $income['endTime']);
            $item['priceReal'] = $income['priceReal'];
            $list[] = $item;
        }
        $data['list'] = $list;

        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }

    /**
     * 任务详情
     * @param $params
     * @return array
     */
    public static function taskDetail($params)
    {
        $data = array();
        if (!isset($params['taskId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $taskId = $params['taskId'];
        $unionId = isset($params['unionId']) ? $params['unionId'] : '';
        $task = Task::queryById($taskId);
        if (!empty($task)) {
            $data['applicationId'] = $task['applicationId'];
            $application = Application::queryById($task['applicationId']);
            $data['qrCode'] = $application['qrCode'];
            $data['name'] = $task['name'];
            $data['startTime'] = date('Y-m-d', $task['startTime']);
            $data['endTime'] = date('Y-m-d', $task['endTime']);
            $data['days'] = (int)$task['days'];
            $data['remainTime'] = ceil(($task['endTime'] - time()) / (60 * 60 * 24));
            $data['price'] = $task['price'] / 100;
            $data['sumPriceReal'] = ($task['sumPriceReal'] - $task['sumPriceCoupon']) / 100;
            $data['description'] = $task['description'];
            $data['pushQrCode'] = \Yii::$app->params['pushQrCode'];
            $taskUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/user/info?state=--flag__4--taskid__' . $task['id'];
            $data['taskQrCode'] = "http://qr.liantu.com/api.php?w=640&m=5&text=" . $taskUrl;
            //报名状态 0 报名 1已报名 2变灰（发起人）
            $registerStatus = 0;
            if (!empty($unionId)) {
                $CurrMember = TaskMember::getMemberByUnionId($taskId, $unionId);
                if (!empty($CurrMember)) {
                    $registerStatus = 1;
                    if ($CurrMember->role == 1) {
                        $registerStatus = 2;
                    }
                }
            }
            $data['registerStatus'] = $registerStatus;
            $data['sumMember'] = (int)TaskMember::getMemberCount($taskId);
            $memberList = TaskMember::queryAllByTaskIdWithPager($taskId, 1, 5);
            $memberList = $memberList['list'];
            $data['originator'] = TaskMember::getOriginatorByTaskId($taskId);
            $data['memberList'] = $memberList;
            return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
        } else {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'Task does not exist'];
        }
    }

    /**
     * 文章打分
     * @param $params
     * @return array
     */
    public static function articleGrade($params)
    {
        $data = array();
        if (!isset($params['unionId']) || !isset($params['aid']) || !isset($params['tid']) || !isset($params['point'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        }
        $unionId = $params['unionId'];
        $aid = $params['aid'];
        $tid = $params['tid'];
        $point = $params['point'];
        $taskIds = TaskMember::queryAllTaskIdByUnionIdAndAid($unionId, $aid);
        if (!empty($taskIds)) {
            foreach ($taskIds as $taskId) {
                //记录打分详情
                TaskDetail::create($taskId, $unionId, $aid, $tid, $point);
                //更新文章计数器平均分值
                TaskCounter::updateAvePoint($aid, $tid, $point);
            }
            $data['taskId'] = $taskIds[0];
        } else {
            //没有任务，也计入打分详情，taskId = 0
            //记录打分详情
            TaskDetail::create(0, $unionId, $aid, $tid, $point);
            //更新文章计数器平均分值
            TaskCounter::updateAvePoint($aid, $tid, $point);
        }
        return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $data];
    }
}