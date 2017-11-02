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
     * @param $taskId
     * @param $unionId
     * @param int $orderId
     * @param int $priceReal
     * @param string $payType
     * @return array|bool
     */
    public static function createTaskOrderId($taskId, $unionId, $orderId = 0, $priceReal = 0, $payType = Constants::PAY_TYPE_WEI_XIN)
    {
        $model = static::findOne(['taskId' => $taskId, 'unionId' => $unionId]);
        if (empty($model)) {
            return false;
        }
        switch ($model->payStatus) {
            case Constants::TASK_PAY_STATUS_SUCCESS:
                return [
                    'code' => Constants::CODE_FAILED,
                    'msg' => '已支付!'
                ];
                break;
        }
        switch ($payType) {
            case Constants::PAY_TYPE_COUPON:
                $model->priceCoupon = $priceReal;
                break;
        }
        $model->orderId = $orderId;
        $model->payType = $payType;
        $model->priceReal = $priceReal;
        $result = $model->save();
        return [
            'code' => $result ? Constants::CODE_SUCCESS : Constants::CODE_FAILED,
            'msg' => ''
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

    public static function h5UpdateTaskMember($orderId, $transactionId, $timeEnd, $payStatus = Constants::TASK_PAY_STATUS_CREATE)
    {
        $model = static::findOne(['orderId' => $orderId]);
        if (empty($model)) {
            return false;
        }
        if (intval($model->payStatus) == Constants::TASK_PAY_STATUS_SUCCESS) {
            return true;
        }
        if (!empty($transactionId)) {
            $model->transactionId = $transactionId;
        }
        $model->payStatus = $payStatus;
        $model->updated = time();
        $model->save();
        return $model->taskId;
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