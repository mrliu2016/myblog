<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class ReportOptions extends ActiveRecord
{
    public static function tableName()
    {
        return 't_report_options'; // TODO: Change the autogenerated stub
    }

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
        $find = self::buildParams($find, [['id' => $id]]);
        if ($isObject) {
            return $find->one();
        } else {
            return $find->asArray()->one();
        }
    }

    /**
     * @param $params
     * @param null $fields
     * @return mixed
     */
    public static function queryInfo($params, $fields = null)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        if (!empty($fields)) {
            $find->select($fields);
        }
        return $find->asArray()->offset($offset)
            ->orderBy('created desc')
            ->limit($params['defaultPageSize'])->all();
    }

    /**
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
     * @param $find
     * @param $params
     * @return mixed
     */
    private static function buildParams($find, $params)
    {
        if (isset($params['id'])) {
            $find->andWhere('id=' . $params['id']);
        }
        return $find;
    }
}