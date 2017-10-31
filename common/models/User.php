<?php

namespace app\common\models;

use yii\db\ActiveRecord;
use app\common\services\Constants;
use app\common\models\Coupon;
use Yii;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 't_user';
    }

    public static function addUser($unionId, $openId, $nickName, $avatar)
    {
        $user = self::queryByUnionId($unionId);
        if (!empty($user)) return;

        $user = new User();
        $user->unionId = $unionId;
        $user->openId = $openId;
        $user->nickNameEnCode = base64_encode($nickName);
        $user->nickName = $nickName;
        $user->avatar = $avatar;
        $user->created = time();
        $user->updated = time();
        $user->save();
    }

    public static function queryByUnionId($unionId, $isObject = false)
    {
        if ($isObject) {
            return static::find()->where(['unionId' => $unionId])->one();
        } else {
            return static::find()->where(['unionId' => $unionId])->asArray()->one();
        }

    }

    public static function queryInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildren($find, $params);
        $list = $find->select("unionId,openId,nickName,avatar,name,phone,idCard,priceReal")->offset($offset)->limit($params['defaultPageSize'])->asArray()->all();
        foreach ($list as $key => $value) {
            $list[$key]['coupon'] = Coupon::getPrice($value['unionId'])[0]['price'] / 100;
        }
        return $list;
    }

    public static function buildren($find, $params)
    {
        if (!empty($params['unionId'])) {
            $find->andWhere(['unionId' => $params['unionId']]);
        }
        return $find;
    }

    public static function queryBynum($params)
    {
        $find = static::find();
        $find = self::buildren($find, $params);
        return $find->count();
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

    public static function queryBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getPriceReal()
    {
        return static::find()->select("id,avatar,(priceReal / 100) as priceReal")->orderBy("priceReal desc")->limit(10)->asArray()->all();
    }

    public static function getAvatar($unionId)
    {
        return static::find()->select("avatar")->andWhere(['unionId' => $unionId])->asArray()->one();
    }

    public static function authentication($params)
    {
        if (empty($params['unionId'])) {
            return ['code' => Constants::CODE_FAILED, 'msg' => 'parameter error'];
        } else {
            $find = static::find();
            $find = self::buildren($find, $params);
            $model = $find->one();
            $model->name = $params['name'];
            $model->phon = $params['phone'];
            $model->idCard = $params['idCard'];
            $model->save();
            $list['id'] = $model->id;
            return ['code' => Constants::CODE_SUCCESS, 'msg' => 'success', 'data' => $list];
        }


    }

    public static function savePricereal($unionid, $priceReal)
    {
        $model = static::find()->andWhere(['unionId' => $unionid])->one();
        $model->priceReal = $priceReal * 100;
        $model->save();
        return $model->id;
    }

}