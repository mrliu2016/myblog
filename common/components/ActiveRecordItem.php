<?php
namespace app\common\components;

use yii\db\ActiveRecord;

class ActiveRecordItem extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get("dbItem");
    }
}