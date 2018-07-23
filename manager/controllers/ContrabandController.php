<?php

namespace app\manager\controllers;

use app\common\components\RedisClient;
use app\common\models\Contraband;
use app\common\services\BroadcastService;
use app\common\services\Constants;
use Yii;

class ContrabandController extends BaseController
{

    const PAGE_SIZE = 10;

    //违禁词管理
    public function actionIndex()
    {

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $params['isDelete'] = 0;
        $list = Contraband::queryInfo($params);
        $count = Contraband::queryInfoNum($params);

        return $this->render('index', [
            'itemList' => $list,
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count,
            'page' => BroadcastService::pageBanner('/contraband/index', $pageNo + 1, $count, self::PAGE_SIZE, 5, 's-gift-page-hover')
        ]);
    }

    //违禁词编辑
    public function actionEditWord()
    {
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if (Contraband::editWord($params)) {
                $this->jsonReturnSuccess(0, '编辑成功.');
            } else {
                $this->jsonReturnError(-1, '编辑失败.');
            }
        }
    }

    //违禁词删除
    public function actionDeleteWord()
    {
        $id = Yii::$app->request->post('id');
        $id = Contraband::deleteWord($id);
        if ($id) {
//            Yii::$app->getResponse()->redirect('/contraband/list');
            $this->jsonReturnSuccess(0, '删除成功.');
        } else {
            $this->jsonReturnError(-1, '删除失败.');
        }
    }

    //违禁词编辑
    public function actionWordSubmit()
    {
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if (Contraband::editWord($params)) {
                $this->jsonReturnSuccess(0, '编辑成功.');
            } else {
                $this->jsonReturnError(-1, '编辑失败.');
            }
        }
    }

    //新增
    public function actionAddWord()
    {
        return $this->render('add-word');
    }

    //导入Excel
    public function actionBatchWord()
    {
        if (Yii::$app->request->isPost) {
            $filename = $_FILES['name']['tmp_name'];
            $reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            $PHPExcel = $reader->load($filename); // 载入excel文件

            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
//            $highestColumm = $sheet->getHighestColumn(); // 取得总列数
            /** 循环读取每个单元格的数据 */
            for ($row = 2; $row <= $highestRow; $row++) {//行数是以第1行开始
                for ($column = 'A'; $column <= 'A'; $column++) {//列数是以A列开始
                    $dataset[] = $sheet->getCell($column . $row)->getValue();
                }
            }
            $result = Contraband::batchWord($dataset);
            if ($result['code'] == 0) {//成功
                $this->redirect('/contraband/list');
            }
        } else {
            return $this->render('list');
        }
    }

    //新增保存
    public function actionAddSave()
    {
        $params = Yii::$app->request->post();
        if (Contraband::addWord($params)) {
            $this->jsonReturnSuccess(0, 'add success');
        } else {
            $this->jsonReturnError(-1, 'add fail');
        }
    }

    //下载模板
    public function actionDownloadTemplate()
    {

        $str = "违禁词\n";
        $title = "违禁词模板";
        $queryTime = date('Y-m-d', $_SERVER['REQUEST_TIME']);
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $title . $queryTime . '.xls');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF) . chr(0xBB) . chr(0xBF));
        echo $str;
        exit();
    }

    //刷新redis
    public function actionRefresh()
    {
        $result = Contraband::refreshRedis();
        if (isset($result) && $result['code'] == 0) {
            $this->jsonReturnSuccess(0);
        } else {
            $this->jsonReturnError(-1);
        }
    }

}
