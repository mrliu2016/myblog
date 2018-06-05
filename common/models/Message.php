<?php

namespace app\common\models;

use app\common\components\RedisClient;
use app\common\services\Constants;
use yii\db\ActiveRecord;
use Yii;

class Message extends ActiveRecord
{
    public static function tableName()
    {
        return 't_message';
    }
    public static function queryInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $list = $find->asArray()->offset($offset)->limit($params['defaultPageSize'])->all();
        foreach ($list as &$item) {
            if ($item['type'] == Constants::WS_MESSAGE_TYPE_WARNING) $item['type'] = '警告';
            if ($item['type'] == Constants::WS_MESSAGE_TYPE_CLOSE) $item['type'] = '强制退出';
        }
        return $list;
    }

    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
    }

    private static function buildParams($find, $params)
    {
//        if (!empty($params['id'])) {
//            $find->andWhere('id=' . $params['id']);
//        }
        return $find;
    }

    //发送警告或强制退出消息
    public static function send($type, $userId, $content)
    {
        $message = new Message();
        $message->userId = intval($userId);
        $message->type = intval($type);
        $message->message = $content;
        $message->created = time();
        $message->updated = time();
        $message->save();
        if ($type == Constants::WS_MESSAGE_TYPE_WARNING) {
            $redis = RedisClient::getInstance();
            $redis->hset(Constants::WS_KEY_WARNING, $userId, $content);
        }
        if ($type == Constants::WS_MESSAGE_TYPE_CLOSE) {
            $redis = RedisClient::getInstance();
            $redis->hset(Constants::WS_KEY_CLOSE, $userId, $content);
        }
    }
}

