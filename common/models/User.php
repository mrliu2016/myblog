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

    public static function veriFied($params)
    {
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->idCard = $params['idCard'];
        $model->realName = $params['realName'];
        $model->save();
        return $model->id;
    }

    public static function setPassworld($params)
    {
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->password = $params['password'];
        $model->save();
        return $model->id;
    }

    public static function bindPhone($params)
    {
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->mobile = $params['mobile'];
        $model->save();
        return $model->id;
    }
}