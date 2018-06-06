<?php
namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class UserNoplay extends ActiveRecord{

    public static function tableName()
    {
        return 't_user_noplay';
    }

    /**
     * @param string $sql
     * @return array
     */
    public static function queryBySQLCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    /**
     * 根据sql更新表数据
     * @param string $sql
     * @return int
     */
    public static function updateBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->execute();
    }

    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
    }

    private static function buildParams($find, $params)
    {
        if (!empty($params['userId'])) {
            $find->andWhere('userId=' . $params['userId']);
        }
        if (!empty($params['roomId'])) {
            $find->andWhere('roomId=' . $params['roomId']);
        }
        return $find;
    }

    //禁播记录
    public static function operateNoplay($params){

        $model = static::find()->andWhere(['userId' => $params['userId']])->one();
        if(!empty($model)){
            $model->type = intval($params['type']);
            $model->message = trim($params['message']);
//            $model->updated = $_SERVER['REQUEST_TIME'];
        }
        else{
            $model = new self();
            $model->userId = $params['userId'];
            $model->roomId = $params['roomId'];
            $model->type = intval($params['type']);
            $model->message = trim($params['message']);
            $model->created = $_SERVER['REQUEST_TIME'];
        }
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        return $model->id;
    }
}