<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class Gift extends ActiveRecord
{
    public static function tableName()
    {
        return 't_gift';
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
        $result = $find->asArray()->orderBy('created desc')->offset($offset)->limit($params['defaultPageSize'])->all();
        return $result;
    }

    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
    }

    public static function buildParams($find, $params)
    {
        if (!empty($params['name'])) $params['name'] = trim($params['name']);
        if (!empty($params['name'])) {
            $find->andWhere(['like', 'name', $params['name']]);
        }
        if (isset($params['content'])) {
            if (ctype_digit($params['content']) && !empty($params['content'])) {
                $find->andWhere(['id' => $params['content']]);
            } else {
                $find->andWhere(['like', 'name', $params['content']]);
            }
        }
        return $find;
    }

    public static function deleteGift($id)
    {
        return static::find()->andWhere(['id' => $id])->one()->delete();
    }

    public static function created($params)
    {
        $model = new self();
        $model->name = $params['name'];
        $model->imgSrc = $params['imgSrc'];
        $model->price = $params['price'] * 100;
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model->id;
    }
}