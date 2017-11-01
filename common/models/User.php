<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 't_user';
    }

    public static function queryById($id)
    {
        $cn = \Yii::$app->db;
        $sql = 'select * from ' . self::tableName() . ' where id= ' . $id;
        return $cn->createCommand($sql)->queryOne();
    }

    public static function checkLogin($mobile, $password)
    {
        return static::find()->andWhere(['mobile' => $mobile])->andWhere(['password' => $password])->asArray()->one();
    }
}