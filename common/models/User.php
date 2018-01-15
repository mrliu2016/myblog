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

    public static function queryById($id, $isObject = false)
    {
        if ($isObject) {
            return static::find()->where(['id' => $id])->one();
        } else {
            return static::find()->where(['id' => $id])->asArray()->one();
        }
    }

    public static function checkLogin($mobile, $password)
    {
        return static::find()->andWhere(['mobile' => $mobile])->andWhere(['password' => $password])->asArray()->one();
    }

    public static function Register($mobile, $password)
    {
        $model = new self();
        $model->mobile = $mobile;
        $model->password = $password;
        $model->userName = $mobile;
        $model->nickName = substr($mobile, 0, 3) . "****" . substr($mobile, 7, 4);
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model->id;
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
     * @return array|bool
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
                return false;
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
            . ' where id = ' . $userId . ' and balance>0';
        return static::updateBySqlCondition($sql);
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
            'userId' => intval($result[Constants::CODE_SUCCESS]['id']),
            'roomId' => intval($result[Constants::CODE_SUCCESS]['roomId']),
            'nickName' => $result[Constants::CODE_SUCCESS]['nickName'],
            'avatar' => $result[Constants::CODE_SUCCESS]['avatar'],
            'mobile' => !empty($result[Constants::CODE_SUCCESS]['mobile']) ? $result[Constants::CODE_SUCCESS]['mobile'] : '',
            'level' => !empty($result[Constants::CODE_SUCCESS]['level']) ? intval($result[Constants::CODE_SUCCESS]['level']) : Constants::CODE_SUCCESS,
            'token' => Token::generateToken($result[Constants::CODE_SUCCESS]['id']),
            'balance' => $result[Constants::CODE_SUCCESS]['balance']
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
        $model = static::find()->andWhere(['mobile' => $params['mobile']])->one();
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
        $information->avatar = $params['avatar'];
        $information->nickName = $params['nickName'];
        $information->province = $params['province'];
        $information->city = $params['city'];
        $information->description = $params['description'];
        $information->save();
        return $information->id;
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

    //心跳更新直播时间
    public static function updateLiveTime($userId)
    {
        $user = self::queryById($userId, true);
        if (!empty($user)) {
            $user->liveTime = time();
            $user->updated = time();
            $user->save();
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
        return $find->asArray()->offset($offset)->limit($params['defaultPageSize'])->all();
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
        return $find;
    }

    public static function SearchUser($content)
    {
        $sql = "SELECT id,userName,avatar,nickName,mobile,description,is_attention,level FROM t_user WHERE nickName LIKE '%$content%' or mobile LIKE '%$content%' OR userName LIKE '%$content%'";
        $row = static::findBySql($sql)->asArray()->all();
        return $row;
    }

    public static function getHotList($page, $size)
    {
        $offset = ($page - 1) * $size;
        $cn = \Yii::$app->db;
        $sqlCnt = "select count(id) as cnt";
        $sql = "select id as userId,avatar,nickName,level,description";
        $sqlStr = " from " . self::tableName() . " where 1=1";
        $sql .= $sqlStr;
        $sql .= ' limit ' . $offset . ',' . $size . '';
        $list = $cn->createCommand($sql)->queryAll();
        $sqlCnt = $sqlCnt . $sqlStr;
        $total_cnt = $cn->createCommand($sqlCnt)->queryOne();
        $total_cnt = (int)$total_cnt['cnt'];
        $page_cnt = ceil($total_cnt / $size);
        return compact('total_cnt', 'page', 'size', 'page_cnt', 'list');
    }
}

