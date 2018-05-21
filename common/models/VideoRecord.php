<?php

namespace app\common\models;

use app\common\services\Constants;
use yii\db\ActiveRecord;
use Yii;

class VideoRecord extends ActiveRecord
{
    public static function tableName()
    {
        return 't_video_record';
    }

    /**
     * 直播结束录制通知
     * @param $params
     * @return int
     * @throws \yii\db\Exception
     */
    public static function transcribe($params)
    {
        $result = Video::queryById($params['stream']);
        $url = Yii::$app->params['liveUrl'] . '/' . $params['uri'];
        $model = new VideoRecord();
        $model->title = $params['remark'];
        $model->roomId = $result['roomId'];
        $model->streamId = $params['stream'];
        $model->userId = $result['userId'];
        $model->startTime = $params['start_time'];
        $model->content = json_encode($params);
        $model->videoSrc = $url;
        $model->duration = static::_secToTime($params['duration']);
        $model->created = time();
        $model->created = time();
        $model->save();
        $sql = 'update ' . Video::tableName() . ' set videoSrc = \'' . $url . '\'' . ',isLive = 2 where id = ' . $params['stream'];
        return static::executeBySqlCondition($sql);
    }

    /**
     * @param $params
     * @return bool|mixed
     * @throws \yii\db\Exception
     */
    public static function qCloudTranscribe($params)
    {
        $result = true;
        switch ($params['event_type']) {
            case 0: // 断流
                break;
            case 1: // 推流
                break;
            case 100: // 录制
                $streamId = explode('_', $params['stream_id']);
                if (empty($streamId[1]) || is_null($streamId[1])
                    || ($streamId[1] == 'null') || !ctype_digit($streamId[1])
                ) {
                    return true;
                }
                $model = new VideoRecord();
                $model->courseId = $streamId[1];
                $model->content = json_encode($params);
                $model->videoSrc = $streamId[1];
                $model->duration = static::_secToTime($params['duration']);
                $model->created = time();
                $model->created = time();
                $model->save();
                $sql = 'update ' . Video::tableName() . ' set videoSrc = \'' . $params['video_url'] . '\',isLive = 2 where id = ' . $streamId[1];
                static::executeBySqlCondition($sql);
                return $model->id;
                break;
            case 200: // 截图
                break;
        }
        return $result;
    }

    /**
     * 创世云录制通知回调
     *
     * @param $params
     */
    public static function chuangCacheTranscribe($params)
    {
        $messageBody = json_decode($params['messageBody'], true);
        switch ($messageBody['type']) {
            case 'RecordFileGenerated':
                $index = strrpos($messageBody['body']['key'], '/');
                $keyName = substr($messageBody['body']['key'], $index + 1);
                $index = strrpos($keyName, '.');
                $keyName = explode('_', substr($keyName, 0, $index));

                $model = new VideoRecord();
                $model->courseId = $messageBody['stream'];
                $model->startTime = strtotime($keyName[1]);
                $model->content = json_encode($params);
                $model->videoSrc = Yii::$app->params['bcebos'] . $messageBody['body']['key'];
                $model->duration = static::_secToTime(strtotime($keyName[2]) - strtotime($keyName[1]));
                $model->created = time();
                $model->updated = time();
                $model->save();
                break;
        }
    }

    /**
     * 秒数转换为时间
     *
     * @param $times
     * @return string
     */
    private static function _secToTime($times)
    {
        $result = '';
        if ($times > 0) {
            $hour = sprintf('%02s', floor($times / 3600));
            $minute = sprintf('%02s', floor(($times - 3600 * $hour) / 60));;
            $second = sprintf('%02s', floor((($times - 3600 * $hour) - 60 * $minute) % 60));
            if (!empty($hour) && ($hour != '00')) {
                $result .= $hour . ':';
            }
            if (!empty($minute)) {
                $result .= $minute . ':';
            }
            if (!empty($second)) {
                $result .= $second;
            }
        }
        return !empty($result) ? $result : '00:00';
    }

    public static function deleteCourseRecord($params)
    {
        $model = static::find()->where(['id' => $params['id']])->one();
        if (empty($model)) {
            return false;
        }
        $model->isDeleted = Constants::STATUS_DELETE;
        $model->updated = time();
        return $model->save();
    }


    /**
     * 回放地址
     *
     * @param $params
     * @return mixed
     */
    public static function queryBackStreamInfo($params)
    {
        $course = Video::queryOne($params['id']);
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->asArray()
            ->andWhere(['isDeleted' => $params['isDeleted']])
            ->select('id,duration,videoSrc')
            ->offset($offset)
            ->limit($params['defaultPageSize'])
            ->orderBy('created desc')
            ->all();
        foreach ($result as $key => $value) {
            $result[$key]['title'] = $course['title'];
        }
        return $result;
    }

    public static function queryInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->offset($offset)->select('id,userId,roomId,startTime,videoSrc,duration,created,title')
            ->limit($params['defaultPageSize'])
            ->orderBy('startTime desc')
            ->asArray()
            ->all();
        return $result;
    }

    public static function queryCourseInfoNum($params)
    {
        $find = static::find();
        $find->andWhere(['courseId' => $params['id']]);
        return $find->count();
    }

    /**
     * 统计记录数
     *
     * @param $params
     * @return mixed
     */
    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
    }

    /**
     * 查询参数绑定
     *
     * @param $find
     * @param $params
     * @return mixed
     */
    public static function buildParams($find, $params)
    {
        if (!empty($params['id'])) {
            $find->andWhere(['courseId' => $params['id']]);
        }
        if (!empty($params['isDeleted'])) {
            $find->andWhere(['isDeleted' => $params['isDeleted']]);
        }
        if (isset($params['userId'])) {
            $find->andWhere(['userId' => $params['userId']]);
        }
        if (isset($params['queryStartTime'])) {
            $find->andWhere('startTime >= ' . $params['queryStartTime']);
        }
        if (isset($params['queryEndTime'])) {
            $find->andWhere('startTime < ' . $params['queryEndTime']);
        }
        return $find;
    }

    /**
     * 根据sql更新表数据
     *
     * @param string $sql
     * @return int
     * @throws \yii\db\Exception
     */
    public static function executeBySqlCondition($sql = '')
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
    public static function queryBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }
}