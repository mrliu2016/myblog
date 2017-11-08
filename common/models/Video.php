<?php

namespace app\common\models;

use yii\db\ActiveRecord;

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
        if (!empty($params['type']) && $params['type'] == self::TYPE_LIVE) {
            $find->andWhere('videoSrc=""');
        }
        if (!empty($params['type']) && $params['type'] == self::TYPE_RECORD) {
            $find->andWhere('videoSrc<>""');
        }
        if (!empty($params['userId'])) {
            $find->andWhere(['userId'=>$params['userId']]);
        }
        return $find;
    }

    public static function findLastRecord($userId, $roomId)
    {
        return static::find()->where(['userId' => $userId, 'roomId' => $roomId])->andWhere(['>', 'endTime', time() - 60])->orderBy('endTime desc')->one();
    }

    //直播开始
    public static function create($userId, $roomId, $remark = '')
    {
        $video = new self();
        $video->userId = $userId;
        $video->roomId = $roomId;
        $video->startTime = time();
        $video->endTime = time();
        $video->videoSrc = '';
        $video->imgSrc = '';
        $video->remark = $remark;
        $video->created = time();
        $video->updated = time();
        $video->save();
        return $video;
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
}