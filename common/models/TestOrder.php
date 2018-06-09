<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class TestOrder extends ActiveRecord
{

    public static function tableName()
    {
        return "order";
    }

    public static function queryAll($params)
    {
        if (isset($params['orderid']) && !empty($params['orderid'])) {
            $model = static::findOne(['id' => $params['orderid']]);
            if (empty($model)) {
                return ['code' => -1, 'message' => '订单号不存在'];
            }
            $model->orderIdAlias = static::orderIdAlias();
            $model->updated = time();
            $model->save();
        } else {
            $model = new self();
            $model->userid = $params['userid'];
            $model->price = $params['price'];
            $model->goodsid = $params['goodsid'];
            $model->orderIdAlias = time() . rand(10000, 99999);
            $model->source = 1;
            $model->status = 1;
            $model->orderCreateTime = time();
            $model->created = time();
            $model->updated = time();
            $model->save();
        }
        return [
            'code' => 1,
            'message' => '',
            'price' => $model->price,
            'orderIdAlias' => $model->orderIdAlias,
            'goodsid'=>$model->goodsid
        ];
    }

    /**
     * 订单别名
     *
     * @return string
     */
    public static function orderIdAlias()
    {
        return time() . rand(10000, 99999);
    }

}