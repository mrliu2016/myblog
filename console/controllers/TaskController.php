<?php

namespace app\console\controllers;

use app\common\components\RedisClient;
use app\common\models\Income;
use app\common\models\Task;
use app\common\models\TaskDetail;
use app\common\models\TaskMember;
use app\common\services\Constants;
use yii\console\Controller;

class TaskController extends Controller
{
    /**
     * 任务开始
     */
    public function actionProcessTaskStart()
    {
        $redis = RedisClient::getInstance();
        $list = Task::getProcessTaskStart();
        foreach ($list as $task) {
            $cnt = TaskMember::getMemberCount($task->id);
            $memberList = TaskMember::queryAllByTaskId($task->id);
            //发起任务失败（任务团未达5人下限）的情况
            if ($cnt < 5) {
                //所有人的状态改为失败
                foreach ($memberList as $member) {
                    TaskMember::updateStatus($member, Constants::TASK_STATUS_FAILED);
                    //推送消息
                    Task::weChatMessage($redis, $task, $member->unionId, $taskType = "任务拼团失败", $remark = "到达任务开始时间时，最低拼团人数失败");
                }
                //任务状态改为失败
                $task->status = Constants::TASK_STATUS_FAILED;
                /*
                 发起任务失败（任务团未达5人下限）的情况下
                 1）发起者使用体验金，参与者使用现金：发起者的体验金不返还，参与者的现金原路返还；
                 2）发起者使用现金，参与者使用现金：发起者和参与者的现金都原路返还；
                 */
            } else {
                foreach ($memberList as $member) {
                    //支付成功的状态改为成功
                    if ($member->payStatus == Constants::TASK_STATUS_SUCCESS) {
                        TaskMember::updateStatus($member, Constants::TASK_STATUS_SUCCESS);
                        //推送消息
                        Task::weChatMessage($redis, $task, $member->unionId, $taskType = "任务拼团成功", $remark = "每日打卡，完成任务可坐享收益");
                    } else {
                        //未支付的状态改为失败
                        TaskMember::updateStatus($member, Constants::TASK_STATUS_FAILED);
                        //推送消息
                        Task::weChatMessage($redis, $task, $member->unionId, $taskType = "任务拼团失败", $remark = "未支付契约金");
                    }
                }
                $task->status = Constants::TASK_STATUS_SUCCESS;
            }
            TaskMember::updateStatus($task, $task->status);
        }
    }

    //任务打卡
    public function actionProcessTaskPunch()
    {
        $redis = RedisClient::getInstance();
        $taskList = Task::getOnlineTaskList();
        foreach ($taskList as $task) {
            $memberList = TaskMember::getOnlineMemberList($task->id);
            foreach ($memberList as $member) {
                if (!TaskDetail::queryPunchByYesterday($task->id, $member->unionId)) {
                    var_dump($member->nickName);
                    //昨日未打卡，出局
                    TaskMember::updateStatus($member, Constants::TASK_STATUS_FAILED);
                    //推送消息
                    Task::weChatMessage($redis, $task, $member->unionId, $taskType = "打卡失败 ", $remark = "每天做任务打卡失败");
                }
            }
        }
    }

    //任务结束
    public function actionProcessTaskEnd()
    {
        $redis = RedisClient::getInstance();
        $list = Task::getProcessTaskEnd();
        foreach ($list as $task) {
            //契约金
            $price = $task->price;
            //总金额
            $sumPrice = $task->sumPriceReal - $task->sumPriceCoupon;
            $originator = TaskMember::getOriginatorByTaskId($task->id);
            //成功的参数者列表
            $participantList = TaskMember::getOnlineParticipantMemberList($task->id);
            $participantCnt = count($participantList);
            //发起者失败，体验金不返还且不被平分，现金返还且不被平分
            if ($originator->status == Constants::TASK_STATUS_FAILED) {
                if (($originator->priceReal - $originator->priceCoupon) > 0) {
                    //总金额=减去发起者现金
                    $sumPrice = $sumPrice - $originator->priceReal;
                    //@todo 发起者契约金退回
                }
                if ($participantCnt > 0) {
                    //总金额=减去参与成功者的契约金
                    $sumPrice = $sumPrice - $price * $participantCnt;
                    //任务均分收益
                    $priceReal = $sumPrice / $participantCnt;
                    foreach ($participantList as $participant) {
                        Income::create($task->id, $task->name, $task->startTime, $task->endTime, $participant->unionId, $priceReal, $task->price);
                        //推送消息
                        Task::weChatMessage($redis, $task, $originator->unionId, $taskType = "任务成功 ", $remark = " 到达任务截止时间，任务成功");
                    }
                } else {
                    //@todo 参与者都失败,参与者契约金退回
                }
            } else {
                //发起者成功，发起者的体验金不返还，但是奖励等额体验金
                if (($originator->priceReal - $originator->priceCoupon) > 0) {
                    //总金额=减去发起者现金
                    $sumPrice = $sumPrice - $originator->priceReal;
                }
                if ($participantCnt > 0) {
                    //总金额=减去参与成功者的契约金
                    $sumPrice = $sumPrice - $price * $participantCnt;
                    //发起者分配失败参与者金额总额的三分之一
                    $originatorPriceReal = $sumPrice / 3;
                    Income::create($task->id, $task->name, $task->startTime, $task->endTime, $originator->unionId, $originatorPriceReal, $task->price);
                    //推送消息
                    Task::weChatMessage($redis, $task, $originator->unionId, $taskType = "任务成功 ", $remark = " 到达任务截止时间，任务成功");
                    //剩余金额，其他成功的参与者平分
                    $sumPrice = $sumPrice - $originatorPriceReal;
                    //任务均分收益
                    $priceReal = $sumPrice / $participantCnt;
                    foreach ($participantList as $participant) {
                        Income::create($task->id, $task->name, $task->startTime, $task->endTime, $participant->unionId, $priceReal, $task->price);
                        //推送消息
                        Task::weChatMessage($redis, $task, $participant->unionId, $taskType = "任务成功 ", $remark = " 到达任务截止时间，任务成功");
                    }
                }else{
                    //如果只有发起者成功了，那么参与者的现金全部归发起者所有
                    Income::create($task->id, $task->name, $task->startTime, $task->endTime, $originator->unionId, $sumPrice, $task->price);
                    //推送消息
                    Task::weChatMessage($redis, $task, $originator->unionId, $taskType = "任务成功 ", $remark = " 到达任务截止时间，任务成功");
                }
            }
            //更新任务状态为结束
            TaskMember::updateStatus($task, Constants::TASK_STATUS_CLOSE);
        }
    }
}