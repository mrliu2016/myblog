<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class KeyWord extends ActiveRecord
{
    public static function tableName()
    {
        return 't_key_word';
    }

    public static function queryById($id, $isObject = false)
    {
        if ($isObject) {
            return static::find()->where(['id' => $id])->one();
        } else {
            return static::find()->where(['id' => $id])->asArray()->one();
        }
    }

    public static function queryInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->asArray()->offset($offset)->limit($params['defaultPageSize'])->all();
    }

    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
    }

    private static function buildParams($find, $params)
    {
        if (!empty($params['id'])) {
            $find->andWhere('id=' . $params['id']);
        }
        return $find;
    }

    public static function queryAllKeyWords()
    {
        $data = array();
        $result = static::find()->asArray()->select('name')->all();
        foreach ($result as $value) {
            $data[] = $value['name'];
        }
        return $data;
    }
}

