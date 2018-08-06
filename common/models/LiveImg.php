<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use Yii;

class LiveImg extends ActiveRecord
{

    public static function tableName()
    {
        return 't_live_img';
    }

    /**
     * @param string $sql
     * @return array
     */
    public static function queryBySQLCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    /**
     * 根据sql更新表数据
     * @param string $sql
     * @return int
     */
    public static function updateBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->execute();
    }

    public static function queryInfoNum($params)
    {
        $find = static::find();
        $find = self::buildParams($find, $params);
        return $find->count();
    }

    private static function buildParams($find, $params)
    {
        if (!empty($params['id'])) {
            $find->andWhere('id=' . $params['id']);
        }
        if (!empty($params['name'])) {
            $find->andWhere(['like', 'name', addslashes($params['name'])]);
        }
        $find->andWhere('isDelete=0');
        return $find;
    }

    public static function queryInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildParams($find, $params);
        $result = $find->asArray()
            ->orderBy('created desc')
            ->offset($offset)->limit($params['defaultPageSize'])->all();
        return $result;
    }

    //新建礼物
    public static function created($params)
    {
        $model = new self();
        $model->name = $params['name'];
        $model->imgSrc = $params['imgSrc'];
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model->id;
    }

    //删除图片
    public static function deleteImg($id)
    {
        $model = static::find()->andWhere(['id' => $id])->one();
        $model->isDelete = 1;
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        return $model->id;
    }

    public static function queryById($id, $isObject = false)
    {
        if ($isObject) {
            return static::find()->where(['id' => $id])->one();
        } else {
            return static::find()->where(['id' => $id])->asArray()->one();
        }
    }
}