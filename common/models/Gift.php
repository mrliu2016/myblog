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
        $result = $find->asArray()
            ->orderBy('created desc')
            ->offset($offset)->limit($params['defaultPageSize'])->all();
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
            $find->andWhere(['like', 'name', addslashes($params['name'])]);
        }
        if(!empty($params['id'])){
            $find->andWhere('id='.intval($params['id']));
        }
        if (isset($params['isFire'])) {
            $find->andWhere('isFire='.$params['isFire']);
        }
        if (isset($params['isDelete'])) {
            $find->andWhere('isDelete='.$params['isDelete']);
        }
        return $find;
    }

    public static function deleteGift($id)
    {
        $model = static ::find()->andWhere(['id' => $id])->one();
        $model->isDelete = 1;
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        return $model->id;
    }

    //新建礼物
    public static function created($params)
    {
        $model = new self();
        $model->name = $params['name'];
        $model->imgSrc = $params['imgSrc'];
        $model->price = $params['price'];
        $model->isFire = $params['fire'];
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model->id;
    }

    //编辑礼物
    public static function editGift($params){
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->name    = $params['name'];
        $model->imgSrc = $params['imgSrc'];
        $model->price   = $params['price'];
        $model->isFire  = $params['isFire'];
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        return $model->id;
    }
}