<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class Base extends ActiveRecord
{
    /**
     * 通过ID查询
     *
     * @param $id
     * @param bool $isObject
     * @return mixed
     */
    public static function queryById($id, $isObject = false)
    {
        $find = static::find();
        $find = static::buildParams($find, [['id' => $id]]);
        if ($isObject) {
            return $find->one();
        } else {
            return $find->asArray()->one();
        }
    }

    /**
     * @param $params
     * @return mixed
     */
    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = static::buildParams($find, $params);
        return $find->count();
    }

    /**
     * 根据sql更新表数据
     *
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

    /**
     * @param $find
     * @param $params
     * @return mixed
     */
    protected static function buildParams($find, $params)
    {
        return $find;
    }
}