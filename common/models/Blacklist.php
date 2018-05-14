<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class Blacklist extends ActiveRecord
{
    public static function tableName()
    {
        return 't_blacklist'; // TODO: Change the autogenerated stub
    }

    /**
     * 拉黑
     *
     * @param $params
     * @return bool
     */
    public static function pullBlacklist($params)
    {
        $model = static::find()
            ->where(
                [
                    'userId' => $params['userId'],
                    'blacklistUserId' => $params['blacklistUserId'],
//                    'status' => 1
                ]
            )->one();
        if (empty($model)) {
            $model = new self();
            $model->userId = $params['userId'];
            $model->blacklistUserId = $params['blacklistUserId'];
            $model->status = 1;
            $model->created = time();
        } else {
            switch ($model->status) {
                case 0: // 取消拉黑
                    $model->status = 1;
                    break;
                case 1: // 拉黑
                    $model->status = 0;
                    break;
            }
        }
        $model->updated = time();
        return $model->save();
    }

    /**
     * 取消拉黑
     *
     * @param $params
     * @return bool
     */
    public static function cancelBlacklist($params)
    {
        $model = static::find()
            ->where(
                [
                    'userId' => $params['userId'],
                    'blacklistUserId' => $params['blacklistUserId'],
                ])->one();
        $model->status = 0;
        $model->updated = time();
        return $model->save();
    }

    /**
     * 是否被拉黑
     *
     * @param $userId
     * @param $blacklistUserId
     * @return mixed
     */
    public static function isPullBlacklist($userId, $blacklistUserId)
    {
        $model = static::find()->where(
            [
                'userId' => $userId,
                'blacklistUserId' => $blacklistUserId,
            ]
        )->one();
        if (empty($model)) {
            return false;
        }
        return $model->status ? true : false;
    }
}