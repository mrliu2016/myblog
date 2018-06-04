<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

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

    public static function queryInfo($params, $field = '')
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->asArray()
            ->select($field)
            ->orderBy('created desc')->offset($offset)
            ->limit($params['defaultPageSize'])->all();
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
            ->andWhere(['userIdFollow' => $userId])
            ->andWhere(['status' => 1])
            ->asArray()->one();
        return $model ? true : false;
    }

    /**
     * 关注
     *
     * @param $userId
     * @param $userIdFollow
     */
    public static function attention($userId, $userIdFollow)
    {
        $model = static::find()->where(['userId' => $userId, 'userIdFollow' => $userIdFollow])->one();
        if (empty($model)) {
            $model = new self();
            $model->userId = $userId;
            $model->userIdFollow = $userIdFollow;
            $model->status = 1;
            $model->created = time();
        } else {
            switch ($model->status) {
                case 0:
                    $model->status = 1;
                    break;
                case 1:
                    $model->status = 0;
                    break;
            }
        }
        $model->updated = time();
        $model->save();
    }

    /**
     * 取消关注
     *
     * @param $userId
     * @param $userIdFollow
     * @return bool
     */
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

    /**
     * 关注列表-直播
     *
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getUserFollowLive($userId, $page, $size)
    {
        $offset = ($page - 1) * $size;
//        $sql = "select a.* from t_follow a,t_user b where a.userIdFollow=b.id and a.userId=" . $userId . " and a.status=1";
        $sql = 'select a.id,a.userId,a.userIdFollow,a.updated,v.roomId as roomId,v.id as streamId,v.imgSrc,v.remark as title,v.viewerNum,v.isLive,v.startTime from '
            . static::tableName() . ' a,' . User::tableName() . ' b,' . Video::tableName() . ' v'
            . " where a.userIdFollow = b.id and a.userId = " . $userId . " and a.status = 1 and v.userId = a.userIdFollow and v.isLive = 1";
        $sql .= ' limit ' . $offset . ',' . $size;
        $result = Video::queryBySQLCondition($sql);
        return $result;
    }

    /**
     * 获取数量
     *
     * @param $userId
     * @return array
     * @throws \yii\db\Exception
     */
    public static function followLiveCount($userId)
    {
        $sql = 'select count(*) as count from '
            . static::tableName() . ' a,' . User::tableName() . ' b,' . Video::tableName() . ' v'
            . " where a.userIdFollow = b.id and a.userId = " . $userId . " and a.status = 1 and v.userId = a.userIdFollow and v.isLive = 1";
        return static::queryBySQLCondition($sql);
    }

    /**
     * @param string $sql
     * @return array
     * @throws \yii\db\Exception
     */
    public static function queryBySQLCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }
}