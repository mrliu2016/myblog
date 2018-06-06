<?php
namespace app\manager\controllers;

use app\common\models\Order;
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

        foreach ($list as $key => &$val){
            //收到礼物
            $receiveValue = Order::queryReceiveGiftByUserId($val['id'],true);
            if(empty($receiveValue) || empty($receiveValue['totalPrice'])){
                $val['receiveValue'] = 0;
            }
            else{
                $val['receiveValue'] = $receiveValue['totalPrice'];
            }
            //送出礼物
            $sendValue = Order::queryReceiveGiftByUserId($val['id'],false);
            if(empty($sendValue) || empty($sendValue['totalPrice'])){
                $val['sendValue'] = 0;
            }
            else{
                $val['sendValue'] = $sendValue['totalPrice'];
            }
        }

//        print_r($list);die;
        $count  = User::queryUserInfoNum($params);
        return $this->render('list', [
            'itemList' => $list,
            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count
        ]);
    }
    //机器人详情
    public function actionDetail(){

        $params = Yii::$app->request->get();
        $id = intval($params['id']);
        $user = User::queryById($id);
        //送出礼物
        $receiveValue = Order::queryReceiveGiftByUserId($id,true);
        if(empty($receiveValue) || empty($receiveValue['totalPrice'])){
            $user['receiveValue'] = 0;
        }
        else{
            $user['receiveValue'] = $receiveValue['totalPrice'];
        }
        //收到礼物
        $sendValue = Order::queryReceiveGiftByUserId($id,false);
        if(empty($sendValue) || empty($sendValue['totalPrice'])){
            $user['sendValue'] = 0;
        }
        else{
            $user['sendValue'] = $sendValue['totalPrice'];
        }
        return $this->render('detail',[
            'item'=>$user
        ]);
    }

    //编辑机器人
    public function actionEditRobot(){

        if(Yii::$app->request->post()){//编辑保存
            $params = Yii::$app->request->post();
            if(User::editRobot($params)){
//                Yii::$app->getResponse()->redirect('/robot/list');
                $this->jsonReturnSuccess(0,'编辑成功');
            }
            else{
                $this->jsonReturnError(-1,'编辑失败');
            }
        }
        else{
            $params = Yii::$app->request->get();
            $id = $params['id'];
            return $this->render('edit-robot',[
                'id'=>$id
            ]);
        }
    }
    //删除
    public function actionDeleteRobot(){
        $id = Yii::$app->request->get('id');
        $id = User::deleteRobot($id);
        if ($id) {
            Yii::$app->getResponse()->redirect('/robot/list');
        }
    }
    //新增
    public function actionAddRobot(){
        return $this->render('add-robot');
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
                for ($column = 'A'; $column <= 'J'; $column++) {//列数是以A列开始
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
            $result = User::batchInsertRobotInfo($insertList);
            $this->redirect('/robot/batch-add');
        } else {
            return $this->render(
                'batch-add',
                [
                ]
            );
        }
    }
    //下载模板
    public function actionDownloadTemplate(){

        $str = "昵称,性别,所在地,关注数,粉丝数,收到礼物,送出礼物,个性签名\n";
        $title = "批量新增机器人模板";
        $queryTime = date('Y-m-d',$_SERVER['REQUEST_TIME']);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $title . $queryTime . '.xls');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF) . chr(0xBB) . chr(0xBF));
        echo $str;
        exit();
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