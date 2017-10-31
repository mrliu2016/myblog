<?php

namespace app\manager\controllers;

use app\common\components\OSS;
use app\common\extensions\OSS\OssClient;
use app\common\models\Application;
use app\common\models\Banner;
use app\common\models\Coupon;
use app\common\models\CouponTemplate;
use app\common\services\Constants;
use Yii;
use yii\data\Pagination;

class IndexController extends BaseController
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


    public function actionIndex()
    {
//        $application = Application::queryById(1);
//        var_dump($application);
//        exit();
        $userName = $this->user->userName;
        return $this->render('index', array(
            'userName' => $userName
        ));
    }

    public function actionApplication()
    {
        if (Yii::$app->request->isPost) {
            if (!empty($_FILES['file']['tmp_name'])) {
                $src = (new OSS())->upload($_FILES['file']['tmp_name'], $_FILES['file']['name'], 'project_wechat');
                $_POST['src'] = $src;
            }
            Application::updateImg($_POST['id'], $_POST['src'], $_POST['type']);
        }
        $apiUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/user/info?state=--flag__9--aid__';
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;;
        $result = Application::queryInfo($params);
        $count = Application::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('application', [
            'itemList' => $result,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'apiUrl' => $apiUrl,
        ]);
    }

    public function actionApplicationAdd()
    {
        if (!empty($_GET['name'])) {
            Application::add($_GET['name']);
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index/application';
            $this->redirect($url);
        }
    }

    public function actionApplicationDelete()
    {
        if (!empty($_GET['id'])) {
            Application::setDelete($_GET['id']);
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index/application';
            $this->redirect($url);
        }
    }

    public function actionApplicationRecommend()
    {
        if (!empty($_GET['id'])) {
            Application::setRecommend($_GET['id']);
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index/application';
            $this->redirect($url);
        }
    }

    //  轮播图
    public function actionBanner()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $list = Banner::queryInfo($params);
        $count = Banner::queryBynum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('banner', [
                'itemList' => $list,
                'count' => $count,
                'pagination' => self::pagination($pageNo, $count),
                'params' => $params,
            ]
        );
    }

    //轮播图操作
    public function actionBannerOperation()
    {
        $data = array(
            'id' => Yii::$app->request->get('id'),
            'isRecommend' => Yii::$app->request->get('isRecommend'),
        );
        if (Banner::saveStatus($data)) {
            Yii::$app->getResponse()->redirect('/index/banner');
        }
    }

    //轮播图添加
    public function actionBannerAdd()
    {
        if (Yii::$app->request->isPost) {
            if (!empty($_FILES['avatar']['tmp_name'])) {
                $src = (new OSS())->upload($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name'], 'project_wechat');
            }
            $data = array(
                'imgSrc' => $src,
                'url' => Yii::$app->request->post('url'),
            );
            $id = Banner::saveDb($data);
            if ($id) {
                Yii::$app->getResponse()->redirect('/index/banner');
            }
        } else {
            return $this->render('banneradd');
        }

    }

    //轮播图删除
    public function actionBannerDelete()
    {
        $id = Yii::$app->request->get('id');
        if (Banner::saveDelete($id)) {
            Yii::$app->getResponse()->redirect('/index/banner');
        }
    }

    //优惠券
    public function actionCoupon()
    {

        if (Yii::$app->request->post()) {
            $params = array(
                'price' => Yii::$app->request->post('price'),
                'type' => Yii::$app->request->post('type'),
            );
            CouponTemplate::saveDb($params);
        }

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $list = CouponTemplate::queryInfo($params);
        $count = CouponTemplate::queryBynum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('coupon', [
                'itemList' => $list,
                'count' => $count,
                'pagination' => self::pagination($pageNo, $count),
                'params' => $params,
            ]
        );
    }

    //优惠券删除
    public function actionCouponDelete()
    {
        $id = Yii::$app->request->get('id');
        if (CouponTemplate::saveDelete($id)) {
            Yii::$app->getResponse()->redirect('/index/coupon');
        }
    }

    //优惠券修改
    public function actionCouponUpdate()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $params = array(
                'price' => Yii::$app->request->post('price'),
                'type' => Yii::$app->request->post('type'),
            );
            $id = CouponTemplate::savePrice($id, $params);
            Yii::$app->getResponse()->redirect('/index/coupon');
        } else {
            $id = Yii::$app->request->get('id');
            $params = array(
                'price' => Yii::$app->request->get('price'),
                'type' => Yii::$app->request->get('type'),
            );
            return $this->render('couponupdate', [
                'id' => $id,
                'itemList' => $params,
            ]);
        }
    }

    //优惠券查看某个人的
    public function actionCouponOne()
    {
        $unionid = Yii::$app->request->get('unionid');
        $list = Coupon::queryOne($unionid);
        if ($list) {
            $this->jsonReturnSuccess(Constants::STATUS_SUCCESS, $list);
        } else {
            $this->jsonReturnError(Constants::STATUS_DELETED, $list);
        }
    }
}
