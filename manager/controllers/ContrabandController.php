<?php
namespace app\manager\controllers;

use app\common\components\RedisClient;
use app\common\models\Contraband;
use app\common\services\Constants;
use Yii;
use yii\data\Pagination;

class ContrabandController extends BaseController{

    const PAGE_SIZE = 5;
    private static function pagination($pageNo, $count)
    {
        $pagination = new Pagination([
            'defaultPageSize' => self::PAGE_SIZE,
            'totalCount' => $count,
        ]);
        $pagination->setPage($pageNo);
        return $pagination;
    }

    //违禁词管理
    public function actionList(){

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $list = Contraband::queryInfo($params);
        $count  = Contraband::queryInfoNum($params);

        return $this->render('list', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    //违禁词编辑
    public function actionEditWord(){

//        $id = Yii::$app->request->get('id');
//        return $this->render('edit-word',[
//            'id'=>$id
//        ]);
//
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if(Contraband::editWord($params)){
                $this->jsonReturnSuccess(0,'编辑成功.');
            }
            else{
                $this->jsonReturnError(-1,'编辑失败.');
            }
        }
    }
    //违禁词删除
    public function actionDeleteWord(){
        $id = Yii::$app->request->get('id');
        $id = Contraband::deleteWord($id);
        if ($id) {
            Yii::$app->getResponse()->redirect('/contraband/list');
        }
    }

    public function actionWordSubmit(){
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if(Contraband::editWord($params)){
                $this->jsonReturnSuccess(0,'编辑成功.');
            }
            else{
                $this->jsonReturnError(-1,'编辑失败.');
            }
        }
    }
    //新增
    public function actionAddWord(){

        return $this->render('add-word');
    }
    //导入Excel
    public function actionBatchWord(){
        return $this->render('batch-word');
    }
    //新增保存
    public function actionAddSave(){
        $params = Yii::$app->request->post();
        if(Contraband::addWord($params)){
            $this->jsonReturnSuccess(0,'add success');
        }
        else{
            $this->jsonReturnError(-1,'add fail');
        }
    }
    //下载模板
    public function actionDownloadTemplate(){

        $str = "违禁词\n";
        $title = "违禁词模板";
        $queryTime = date('Y-m-d',$_SERVER['REQUEST_TIME']);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $title . $queryTime . '.xlsx');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF) . chr(0xBB) . chr(0xBF));
        echo $str;
        exit();
    }

    //批量新增机器人信息
    public function actionBatchAdd(){
        if (Yii::$app->request->isPost) {
            $filename = $_FILES['name']['tmp_name'];
            $reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            $PHPExcel = $reader->load($filename); // 载入excel文件
            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数
            /** 循环读取每个单元格的数据 */
            for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
                for ($column = 'A'; $column <= 'A'; $column++) {//列数是以A列开始
//                    $dataset[$column][] = $sheet->getCell($column.$row)->getValue();
                    $dataset[] = $sheet->getCell($column.$row)->getValue();
                }
            }

            $result = Contraband::batchWord($dataset);

            $this->redirect('/contraband/add-word');
        } else {
            return $this->render(
                'batch-add',
                [
                ]
            );
        }
    }

    //刷新redis
    public function actionRefresh(){
        $redis = RedisClient::getInstance();
//        print_r($redis);die;
        $data = Contraband::queryAllInfo();

//        $redis->hget()



    }
}