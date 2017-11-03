<?php

namespace app\common\models;

use app\common\components\Token;
use app\common\services\Constants;
use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 't_user';
    }

    public static function queryById($id)
    {
        $cn = \Yii::$app->db;
        $sql = 'select * from ' . self::tableName() . ' where id= ' . $id;
        return $cn->createCommand($sql)->queryOne();
    }

    public static function checkLogin($mobile, $password)
    {
        return static::find()->andWhere(['mobile' => $mobile])->andWhere(['password' => $password])->asArray()->one();
    }

    /**
     * @param $params
     * @return array
     */
    public static function authorizeLogin($params)
    {
        $params['loginType'] = isset($params['loginType']) ? $params['loginType'] : '';
        switch ($params['loginType']) {
            case Constants::LOGIN_TYPE_WEI_XIN:
                return static::weiXin($params);
                break;
            case Constants::LOGIN_TYPE_QQ:
                return static::QQ($params);
                break;
            case Constants::LOGIN_TYPE_WEI_BO:
                return static::weiBo($params);
                break;
            default:
                return ['code' => Constants::CODE_FAILED, 'message' => '登陆失败!'];
                break;
        }
    }

    /**
     * @param $params
     * @return array
     */
    public static function weiXin($params)
    {
        $querySql = 'select id,id as roomId,nickName,userName,avatar,mobile,level from '
            . static::tableName()
            . ' where wxOpenId = \'' . $params['openId'] . '\'';
        $result = static::queryBySQLCondition($querySql);
        if (empty($result)) {
            $insertSql = 'insert into ' . static::tableName() . '(userName,avatar,nickName,wxOpenId,wxUnionId,province,city,created,updated)'
                . ' values(\'' . $params['nickname'] . '\',\'' . $params['avatar'] . '\',\'' . $params['nickname']
                . '\',\'' . $params['openId'] . '\',\'' . $params['unionId'] . '\',\'' . $params['province']
                . '\',\'' . $params['city'] . '\',' . time() . ',' . time() . ')';
            if (!static::updateBySqlCondition($insertSql)) {
                return [
                    'code' => Constants::CODE_FAILED,
                    'message' => '登陆失败!'
                ];
            }
            $result = static::queryBySQLCondition($querySql);
        }
        return static::processLoginInfo($result);
    }

    public static function QQ($params)
    {

    }

    public static function weiBo($params)
    {

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
            . ' where id = ' . $userId;
        static::updateBySqlCondition($sql);
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
     * @param $result
     * @return array
     */
    private static function processLoginInfo($result)
    {
        return [
            'code' => Constants::CODE_SUCCESS,
            'message' => '登陆成功!',
            'data' => [
                'userId' => intval($result[Constants::CODE_SUCCESS]['id']),
                'roomId' => intval($result[Constants::CODE_SUCCESS]['roomId']),
                'nickName' => $result[Constants::CODE_SUCCESS]['nickName'],
                'avatar' => $result[Constants::CODE_SUCCESS]['avatar'],
                'mobile' => !empty($result[Constants::CODE_SUCCESS]['mobile']) ? $result[Constants::CODE_SUCCESS]['mobile'] : '',
                'level' => !empty($result[Constants::CODE_SUCCESS]['level']) ? intval($result[Constants::CODE_SUCCESS]['level']) : Constants::CODE_SUCCESS,
                'token' => Token::generateToken($result[Constants::CODE_SUCCESS]['id'])
            ]
        ];
    }

    public static function veriFied($params)
    {
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->idCard = $params['idCard'];
        $model->realName = $params['realName'];
        $model->save();
        return $model->id;
    }

    public static function setPassworld($params)
    {
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->password = $params['password'];
        $model->save();
        return $model->id;
    }

    public static function bindPhone($params)
    {
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->mobile = $params['mobile'];
        $model->save();
        return $model->id;
    }


    public static function informationUpdate($params)
    {
        $information = static::find()->andWhere(['id' => $params['id']])->one();

    }
    public static function updateUserLocation($userId, $longitude, $latitude)
    {
        $model = static::find()->andWhere(['id' => $userId])->one();
        $model->longitude = $longitude;
        $model->latitude = $latitude;
        $model->save();
    }

    public static function getNearUserList($userId, $squares, $lng, $lat, $page, $size)
    {
        $offset = ($page - 1) * $size;
        $cn = \Yii::$app->db;
        $sqlCnt = "select count(id) as cnt";
        $sql = "select id as userId,avatar,nickName,level,description,latitude,longitude";
        $sqlStr = " from " . self::tableName() . " where id!={$userId} and latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}";
        $sql .= $sqlStr . ' order by abs(longitude -' . $lng . ')+abs(latitude -' . $lat . ')';
        $sql .= ' limit ' . $offset . ',' . $size . '';
        $list = $cn->createCommand($sql)->queryAll();
        $sqlCnt = $sqlCnt . $sqlStr;
        $total_cnt = $cn->createCommand($sqlCnt)->queryOne();
        $total_cnt = (int)$total_cnt['cnt'];
        $page_cnt = ceil($total_cnt / $size);
        return compact('total_cnt', 'page', 'size', 'page_cnt', 'list');
    }
}