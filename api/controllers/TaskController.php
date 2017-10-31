<?php

namespace app\api\controllers;

use app\common\services\Constants;
use app\common\services\TaskService;
use Yii;

class TaskController extends BaseController
{
    /**
     * 创建任务
     */
    public function actionCreateTask()
    {
        $params = Yii::$app->request->post();
        $result = TaskService::createTask($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 加入任务
     */
    public function actionJoinTask()
    {
        $params = Yii::$app->request->post();
        $result = TaskService::joinTask($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 任务成员列表
     */
    public function actionTaskMember()
    {
        $params = Yii::$app->request->get();
        $result = TaskService::taskMemberList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 我的任务列表
     */
    public function actionMyTask(){
        $params = Yii::$app->request->get();
        $result = TaskService::myTaskList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 我的收益
     */
    public function actionMyIncome(){
        $params = Yii::$app->request->get();
        $result = TaskService::myIncomeList($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 任务详情
     */
    public function actionTaskDetail(){
        $params = Yii::$app->request->get();
        $result = TaskService::taskDetail($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }

    /**
     * 文章打分
     */
    public function actionArticleGrade(){
        $params = Yii::$app->request->post();
        $result = TaskService::articleGrade($params);
        if ($result['code'] == Constants::CODE_FAILED) {
            $this->jsonReturnError(Constants::CODE_FAILED, $result['msg'], []);
        }
        $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result['msg'], $result['data']);
    }
}