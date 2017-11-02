<?php

namespace app\common\models;

use yii\db\ActiveRecord;

class UserTag extends ActiveRecord
{
    public static function tableName()
    {
        return 't_user_tag';
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
        $result = $find->asArray()->orderBy('created desc')->offset($offset)->limit($params['defaultPageSize'])->all();
        foreach ($result as $key => $value) {
            $tag = Tag::queryById($value['tagId']);
            unset($result[$key]);
            $result[$key]['tagId'] = $tag['id'];
            $result[$key]['name'] = $tag['name'];
            $result[$key]['remark'] = $tag['remark'];
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
        return $find;
    }
}