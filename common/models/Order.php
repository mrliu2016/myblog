<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 't_order'; // TODO: Change the autogenerated stub
    }

    //购买礼物
    public static function create($giftId, $userId, $userIdReceive, $price, $num)
    {
        $model = new self();
        $model->giftId = $giftId;
        $model->price = $price;
        $model->priceReal = $price;
        $model->num = $num;
        $model->userId = $userId;
        $model->userIdReceive = $userIdReceive;
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model;
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
}