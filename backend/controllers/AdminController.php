<?php

namespace app\backend\controllers;

use app\common\models\Admin;
use app\common\services\Constants;
use yii\data\Pagination;

class AdminController extends BaseController
{
    public function afterAction($action, $result)
    {
        $admin_name = \Yii::$app->session['admin_name'];
        $admin_id = \Yii::$app->session['admin_id'];
//        $this->layout = true;
        if (empty($admin_name) || empty($admin_id)) {
            $this->redirect('/user/login');
        }
        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }


    const PAGE = 1;
    const PAGE_SIZE = 10;

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
     * 管理员列表
     */
    public function actionList()
    {
        $params = \Yii::$app->request->getQueryParams();
        $this->layout = false;

        $params['page'] = empty($params['page']) ? self::PAGE : $params['page'];
        $params['pageSize'] = empty($params['pageSize']) ? self::PAGE_SIZE : $params['pageSize'];

        $list = Admin::queryList($params);
        $count = Admin::count($params);
        $pageNo = $params['page'] - 1;
        $index = $pageNo * $params['pageSize'] + 1;

        return $this->render('list', [
            'params' => $params,
            'pagination' => self::pagination($pageNo, $count),
            'index' => $index,
            'list' => $list
        ]);
    }

    /**
     * 添加管理员
     */
    public function actionAdd()
    {
        if (\Yii::$app->request->isGet) {
            $this->layout = false;
            return $this->render('add');
        } else {
            $params = \Yii::$app->request->post();
            if (empty($params['username']) || empty($params['nickname']) || empty($params['password'])) {
                $this->jsonReturnError(Constants::CODE_FAILED, '缺少参数！');
            }
            $result = Admin::add($params);
            if ($result) {
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result, 'success');
            }
            $this->jsonReturnError(Constants::CODE_FAILED, 'fail');
        }
    }

    /**
     * 编辑
     */
    public function actionEdit()
    {
        if (\Yii::$app->request->isGet) {
            $params = \Yii::$app->request->getQueryParams();
            $this->layout = false;
            $admin = Admin::queryById($params['id']);
            return $this->render('edit', [
                'params' => $params,
                'admin' => $admin
            ]);
        } else {//编辑保存
            $params = \Yii::$app->request->post();
            $result = Admin::edit($params);
            if ($result) {
                $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result, 'success');
            }
            $this->jsonReturnError(Constants::CODE_FAILED, 'fail');
        }
    }

    /**
     * 删除管理员
     */
    public function actionDel()
    {
        $params = \Yii::$app->request->getQueryParams();
        $result = Admin::del($params['id']);
        if ($result) {
            $this->jsonReturnSuccess(Constants::CODE_SUCCESS, $result, 'success');
        }
        $this->jsonReturnError(Constants::CODE_FAILED, 'fail');
    }

    /**
     * 管理员信息
     */
    public function actionInfo()
    {
        $this->layout = true;
        $adminId = \Yii::$app->session['admin_id'];
        $admin = Admin::queryById($adminId);
        return $this->render('info', [
            'admin' => $admin
        ]);
    }

}