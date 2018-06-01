<?php
namespace app\manager\controllers;

use app\common\models\User;
use Yii;
use yii\data\Pagination;

class RobotController extends BaseController{

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

    public function actionList(){
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        $params['type'] = 1;
        $list = User::queryUserInfo($params);
        $count  = User::queryUserInfoNum($params);

        return $this->render('list', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }

    //新增
    public function actionAddRobot(){


        return $this->render('add-robot');
    }

    //批量新增机器人信息
    public function actionBatchAdd(){
        $filename = dirname(__FILE__).'/index.xls';
        $reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
        $PHPExcel = $reader->load($filename); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        /** 循环读取每个单元格的数据 */
        for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
            for ($column = 'A'; $column <= 'H'; $column++) {//列数是以A列开始
                $dataset[$column][] = $sheet->getCell($column.$row)->getValue();
            }
        }

        if(!empty($dataset) && isset($dataset)){
            $insertList = array();
            foreach ($dataset as $key => $val){
                foreach ($val as $k => $v){
                    $insertList[$k][] = $v;
                }
            }
        }
        echo 111;die;
        $result = User::batchInsertRobotInfo($insertList);

        print_r($result);die;


    }

    //新增机器人提交
    public function actionAddSubmit(){
        if (Yii::$app->request->post()) {
            $params = Yii::$app->request->post();
            if (User::addRobotInfo($params)) {
                $this->jsonReturnSuccess(0, '新增成功.');
            } else {
                $this->jsonReturnError('-1', '新增失败.');
            }
        }
    }

}