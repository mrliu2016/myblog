<?php
namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class UserFeedback extends ActiveRecord{

    public static function tableName()
    {
        return 't_user_feedback'; // TODO: Change the autogenerated stub
    }

    public static function executeBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->execute();
    }
    //插入反馈内容
    public static function insertUserFeedback($params){

        $sql = "INSERT INTO " . static::tableName() . "(userId,content,created,updated)"
            . " VALUES(".intval($params['userId']).",'".$params['content']."',".$_SERVER['REQUEST_TIME'].",".$_SERVER['REQUEST_TIME'].")";
        if (static::executeBySqlCondition($sql)) {
            return ['code'=>0];
        }
        else{
            return ['code'=>-1];
        }
    }
}