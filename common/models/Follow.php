<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class Follow extends ActiveRecord
{
    public static function tableName()
    {
        return 't_follow';
    }

    public static function queryById($id)
    {
        $cn = \Yii::$app->db;
        $sql = 'select * from ' . self::tableName() . ' where id= ' . $id;
        return $cn->createCommand($sql)->queryOne();
    }

    public static function queryInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->asArray()->orderBy('updated desc')->offset($offset)->limit($params['defaultPageSize'])->all();
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
        if (!empty($params['userId'])) {
            $find->andWhere(['userId' => $params['userId']]);
        }
        if (!empty($params['userIdFollow'])) {
            $find->andWhere(['userIdFollow' => $params['userIdFollow']]);
        }
        $find->andWhere(['status' => 1]);
        return $find;
    }

    public static function isAttention($userId, $observerUserId)
    {
        $model = static::find()
            ->andWhere(['userId' => $observerUserId])
            ->andWhere(['userIdFollow' => $userId])->asArray()->one();
        return $model ? true : false;
    }

    public static function attention($userId, $userIdFollow)
    {
        $model = static::find()->where(['userId' => $userId, 'userIdFollow' => $userIdFollow, 'status' => 1])->one();
        if(empty($model)){
            $model = new self();
            $model->userId = $userId;
            $model->userIdFollow = $userIdFollow;
            $model->created = time();
            $model->updated = time();
            $model->save();
        }
    }

    public static function updateChannelAttention($userId, $userIdFollow)
    {
        $model = static::find()->where(['userId' => $userId, 'userIdFollow' => $userIdFollow, 'status' => 1])->one();
        if (!$model) {
            return false;
        }
        $model->status = 0;
        $model->updated = time();
        return $model->save();
    }

    //关注列表-直播
    public static function getUserFollowLive($userId, $page, $size)
    {
        $offset = ($page - 1) * $size;
        $sql = "select a.* from t_follow a,t_user b where a.userIdFollow=b.id and a.userId=" . $userId . " and b.liveTime>" . (time() - 30) . "";
        $sql .= ' limit ' . $offset . ',' . $size . '';
        $result = Video::queryBySQLCondition($sql);
        return $result;
    }
}