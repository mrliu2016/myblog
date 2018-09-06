<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class ManagerUser extends ActiveRecord
{
    public static function tableName()
    {
        return 't_manager_user';
    }

    //根据用户名验证是否存在用户
    public static function findOne($params){
        return static::find()
            ->select('id,username,nickName')
            ->where(['username' => $params['username']])
            ->where(['password' => md5($params['password'])])
            ->asArray()
            ->one();
    }

}

