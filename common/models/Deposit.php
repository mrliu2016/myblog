<?php

namespace app\common\models;

use app\common\services\Constants;
use yii\db\ActiveRecord;

class Deposit extends ActiveRecord
{
    public static function tableName()
    {
        return 't_deposit'; // TODO: Change the autogenerated stub
    }

    /**
     * 创建订单
     * @param $params
     * @param $source
     * @return mixed|string
     */
    public static function createOrder($params, $source)
    {
        $flag = isset($params['orderId']) ? (!empty($params['orderId']) ? true : false) : false;
        if ($flag) {
            $model = static::findOne(['id' => $params['orderId']]);
            if (empty($model)) {
                return ['code' => Constants::CODE_FAILED, 'message' => '订单不存在!'];
            }
            $model->orderIdAlias = static::orderIdAlias();
            $model->save();
        } else {
            $model = new Deposit();
            $model->userId = $params['userId'];
            $model->price = $params['price'] * Constants::CENT;
            $model->orderIdAlias = static::orderIdAlias();
            $model->source = $source;
            $model->status = Constants::ORDER_STATUS_PENDING_PAYMENT;
            $model->orderCreateTime = time();
            $model->created = time();
            $model->updated = time();
            $model->save();
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => '订单存在!',
            'orderId' => $model->orderIdAlias,
            'price' => $model->price / Constants::CENT
        ];
    }

    /**
     * @param $orderId
     * @param $transactionId
     * @param $timeEnd
     * @param string $status
     * @return array | boolean
     */
    public static function updateDeposit($orderId, $transactionId, $timeEnd, $status = Constants::ORDER_STATUS_COMPLETED)
    {
        $model = static::findOne(['orderIdAlias' => $orderId]);
        if (empty($model)) {
            return false;
        }
        if (!empty($transactionId)) {
            $model->transactionId = $transactionId;
        }
        $model->status = $status;
        $model->orderPayTime = $timeEnd;
        $model->updated = time();
        $result = $model->save();
        return $result ? ['userId' => $model->userId, 'price' => $model->price] : [];
    }

    /**
     * @param $params
     * @param string $field
     * @return array
     */
    public static function depositRecord($params,$field = '')
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->select($field)
            ->asArray()->offset($offset)
            ->limit($params['defaultPageSize'])
            ->orderBy('orderCreateTime desc')->all();
        foreach ($result as $key => $value) {
            $result[$key]['id'] = intval($value['id']);
            $result[$key]['price'] = strval($value['price'] / Constants::CENT);
            $result[$key]['orderPayTime'] = !empty($value['orderPayTime'])
                ? date('Y-m-d H:i:s', strtotime($value['orderPayTime'])) : '';
            $result[$key]['source'] = ($value['source'] == 0) ? '微信' : '其他';
        }
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => '订单列表',
            'data' => [
                'page' => intval($params['page']),
                'size' => intval($params['size']),
                'page_cnt' => Constants::CODE_SUCCESS,
                'total_cnt' => Constants::CODE_SUCCESS,
                'list' => $result
            ]
        ];
    }

    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
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

    /**
     * 绑定查询条件
     *
     * @param object $find
     * @param [] $params
     * @return mixed
     */
    public static function buildParams($find, $params)
    {
        if (!empty($params['userId'])) {
            $find->andWhere(['userId' => $params['userId']]);
        }
        return $find;
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