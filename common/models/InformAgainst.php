<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class InformAgainst extends ActiveRecord
{
    /**
     * 表名称
     *
     * @return string
     */
    public static function tableName()
    {
        return 't_inform_against'; // TODO: Change the autogenerated stub
    }

    /**
     * 举报
     *
     * @param $params
     * @return bool
     */
    public static function informAgainst($params)
    {
        $model = new InformAgainst();
        $model->userId = $params['userId'];
        $model->informAgainstUserId = $params['informAgainstUserId'];
        $model->roomId = $params['roomId'];
        $model->reason = $params['reason'];
        $model->created = time();
        $model->updated = time();
        return $model->save();
    }
}