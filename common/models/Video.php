<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class Video extends ActiveRecord
{
    const TYPE_LIVE = 1;
    const TYPE_RECORD = 2;

    public static function tableName()
    {
        return 't_video';
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
        $result = $find->andWhere('imgSrc<>""')->asArray()
            ->orderBy('created desc')
            ->offset($offset)
            ->limit($params['defaultPageSize'])
            ->all();
        return $result;
    }

    public static function queryHot($params)
    {
        $offset = 0;
        $userId = '';
        $userInfo = [];
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->select('id,userId,roomId,startTime,imgSrc,remark as title,isLive')
            ->asArray()
            ->orderBy('startTime desc')
            ->offset($offset)
            ->limit($params['defaultPageSize'])
            ->all();
        foreach ($result as $key => $value) {
            $userId .= $value['userId'] . ',';
        }
        if (!empty($userId)) {
            $sql = 'select id,avatar,nickName,level,description from '
                . User::tableName() . ' where id in(' . trim($userId, ',') . ')';
            $userInfo = static::queryBySQLCondition($sql);
        }
        foreach ($result as $key => $value) {
            $result[$key]['startTime'] = date('Y.m.d H:i', $value['startTime']);
            $flag = true;
            foreach ($userInfo as $userKey => $userValue) {
                if ($value['userId'] == $userValue['id']) {
                    $result[$key]['avatar'] = $userValue['avatar'];
                    $result[$key]['nickName'] = $userValue['nickName'];
                    $result[$key]['level'] = intval($userValue['level']);
                    $result[$key]['description'] = $userValue['description'];
                    $flag = false;
                }
            }
            if ($flag) {
                $result[$key]['avatar'] = '';
                $result[$key]['nickName'] = '';
                $result[$key]['level'] = 0;
                $result[$key]['description'] = '';
            }
        }
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
        if (!empty($params['type']) && $params['type'] == self::TYPE_LIVE) {
            $find->andWhere('videoSrc=""');
        }
        if (!empty($params['type']) && $params['type'] == self::TYPE_RECORD) {
            $find->andWhere('videoSrc<>""');
        }
        if (!empty($params['userId'])) {
            $find->andWhere(['userId' => $params['userId']]);
        }
        if (isset($params['isLive'])) {
            $find->andWhere(['isLive' => $params['isLive']]);
        }
        return $find;
    }

    public static function findLastRecord($userId, $roomId)
    {
        return static::find()->where(['userId' => $userId, 'roomId' => $roomId])->andWhere(['>', 'endTime', time() - 60])->orderBy('endTime desc')->one();
    }

    //直播开始
    public static function create($userId, $roomId, $remark = '', $imgSrc = '')
    {
        $model = new self();
        $model->userId = $userId;
        $model->roomId = $roomId;
        $model->startTime = time();
        $model->endTime = time();
        $model->imgSrc = $imgSrc;
        $model->remark = $remark;
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model->id;
    }

    //心跳更新直播结束时间
    public static function updateEndTime($video)
    {
        $video->endTime = time();
        $video->updated = time();
        $video->save();
    }

    //更新视频录制地址和视频封面
    public static function updateVideoInfo($roomId, $videoSrc, $imgSrc)
    {
        $video = static::find()->where(['roomId' => $roomId])->one();
        if (!empty($video)) {
            $video->videoSrc = $videoSrc;
            $video->imgSrc = $imgSrc;
            $video->updated = time();
            $video->save();
        }
    }

    /**
     * @param $liveId
     * @param $userId
     * @return int
     * @throws \yii\db\Exception
     */
    public static function terminationLive($liveId, $userId)
    {
        $sql = 'update ' . static::tableName()
            . ' set isLive = 0,endTime=' . time() . ',updated=' . time() . ' where id = ' . $liveId;
        return static::updateBySqlCondition($sql);
    }

    /**
     * @param $userId
     * @return array
     * @throws \yii\db\Exception
     */
    public static function isLive($userId)
    {
        $sql = 'select id,userId,isLive from ' . static::tableName()
            . ' where userId = ' . $userId . ' and isLive = 1';
        return static::queryBySQLCondition($sql);
    }

    /**
     * 更新用户余额
     * @param $userId
     * @param $balance
     */
    public static function updateUserBalance($userId, $balance)
    {
        $sql = 'update ' . User::tableName()
            . ' set balance = balance + ' . intval($balance)
            . ' where id = ' . $userId . ' and balance>0';
        return static::updateBySqlCondition($sql);
    }

    /**
     * @param string $sql
     * @return int
     * @throws \yii\db\Exception
     */
    public static function updateBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->execute();
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