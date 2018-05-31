<?php

namespace app\common\models;

use app\common\components\Token;
use app\common\services\Constants;
use app\common\services\VideoService;
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
            return static::find()
                ->where(['id' => $id])->one();
        } else {
            return static::find()
                ->select('id as userId,userName,avatar,nickName,sex,birth,mobile,
                balance,level,description,isValid,idCard,realName,roomId,income,province,city,region,profession')
                ->where(['id' => $id])
                ->asArray()
                ->one();
        }
    }

    public static function queryByPhone($mobile)
    {
        return static::find()
            ->select('id,applicationId,nickName,mobile,isValid,balance')
            ->andWhere(['mobile' => $mobile])->asArray()->one();
    }

    /**
     * 个人信息
     *
     * @param $userId
     * @param $observerUserId
     * @return array|bool|null|ActiveRecord
     * @throws \yii\db\Exception
     */
    public static function profile($userId, $observerUserId)
    {
        $userInfo = static::queryById($userId);
        if (empty($userInfo)) {
            return false;
        }
        if(isset($userInfo['birth']) && !empty($userInfo['birth'])){//年龄
            $userInfo['age']    = intval(date('Y',$_SERVER['REQUEST_TIME'])-date('Y',$userInfo['birth']));
        }
        else{
            $userInfo['age'] = 0;
        }
        $userInfo['roomId'] = intval($userInfo['roomId']);
        $userInfo['income'] = intval($userInfo['income']);
        $userInfo['avatar'] = !empty($userInfo['avatar']) ? $userInfo['avatar'] : Yii::$app->params['defaultAvatar'];
        $userInfo['balance'] = !empty($userInfo['balance']) ? $userInfo['balance'] : 0;
        $userInfo['followees_cnt'] = VideoService::computeUnit(intval(Follow::queryInfoNum(['userId' => $userId]))); // 我的关注
        $userInfo['followers_cnt'] = VideoService::computeUnit(intval(Follow::queryInfoNum(['userIdFollow' => $userId]))); // 关注我的
        $userInfo['isAttention'] = intval(Follow::isAttention($userId, $observerUserId) ? 1 : 0);
        $userInfo['isLive'] = intval(Video::isLive($userId) ? 1 : 0);
        $userInfo['isBlacklist'] = intval(Blacklist::isPullBlacklist($observerUserId, $userId));

        if(isset($userId)){
            $launchT = Order::queryReceiveGiftByUserId($userId,true);
            if(!empty($launchT)){ //送出的T币
                $userInfo['launchT'] = intval($launchT['totalPrice']);
            }
            $receivedT = Order::queryReceiveGiftByUserId($userId,false);
            if(!empty($receivedT)){//收到的T币
                $userInfo['receivedT'] = intval($receivedT['totalPrice']);
            }
        }
        return $userInfo;
    }

    public static function checkLogin($mobile, $password, $isVerifyCode = false)
    {
        if ($isVerifyCode) {
            return static::find()
                ->andWhere(['mobile' => $mobile])
                ->asArray()->one();
        }
        return static::find()
            ->andWhere(['mobile' => $mobile])
            ->andWhere(['password' => $password])
            ->asArray()->one();
    }

    /**
     * 手机号注册
     *
     * @param $mobile
     * @param $password
     * @return mixed
     */
    public static function Register($mobile, $password)
    {
        $model = new self();
        $model->mobile = $mobile;
        $model->password = $password;
        $model->userName = $mobile;
//        $model->nickName = substr($mobile, 0, 3) . "****" . substr($mobile, 7, 4);
        $model->nickName = Yii::$app->params['defaultNickName'] . date('mdHis', time());
        $model->roomId = static::generateId();
        $model->created = time();
        $model->updated = time();
        $model->save();
        return $model->id;
    }

    public static function generateId()
    {
        return rand(100000, 999999) . substr(time(), -2, 2);
//        return rand(100000, 999999) . substr(time(), -2, 2) . rand(1000, 9999);
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
                return [];
                break;
        }
    }

    /**
     * @param $params
     * @return array|bool
     */
    public static function weiXin($params)
    {
        $querySql = 'select id,id as roomId,nickName,userName,avatar,mobile,balance,level from '
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
            'balance' => !empty($result[Constants::CODE_SUCCESS]['balance'])
                ? $result[Constants::CODE_SUCCESS]['balance'] : 0
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
        $sqlStr = " from " . self::tableName() . " where id!={$userId} and liveTime>" . (time() - 30) . " and latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}";
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

    private static function buildParams($find, $params)
    {
        if (!empty($params['id'])) {
            $find->andWhere('id=' . $params['id']);
        }
        if (isset($params['content'])) {
            if (ctype_digit($params['content']) && is_numeric($params['content'])) {
                $find->andWhere('roomId =' . $params['content']);
            } else {
                $find->andWhere('nickName like \'%' . $params['content'] . '%\'');
            }
        }
        return $find;
    }

    public static function SearchUser($content, $observerUserId, $params)
    {
//        $sql = "SELECT id,userName,avatar,nickName,mobile,description,level FROM " . static::tableName();
////            . static::tableName() . " WHERE nickName LIKE '%$content%' or mobile LIKE '%$content%' OR userName LIKE '%$content%'";
//        if (ctype_digit($content) && is_numeric($content)) {
//            $sql .= " where roomId = $content";
//        } else {
//            $sql .= " where nickName LIKE '%$content%'";
//        }
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = static::buildParams($find, $params);
        $result = $find->select('id,id as userId,roomId,userName,avatar,nickName,mobile,description,level')
            ->asArray()
            ->offset($offset)
            ->limit($params['defaultPageSize'])
            ->all();

//        $result = static::findBySql($sql)->asArray()->offset($offset)->limit($params['defaultPageSize'])->all();
        foreach ($result as $key => $value) {
            $result[$key]['isAttention'] = intval(Follow::isAttention($value['id'], $observerUserId) ? 1 : 0);
            $result[$key]['avatar'] = !empty($value['avatar']) ? $value['avatar'] : Yii::$app->params['defaultAvatar'];
        }
        return $result;
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

    /**
     * 更新虚拟货币
     *
     * @param $userId
     * @param $idealMoney
     * @return int
     */
    public static function depositIdealMoney($userId, $idealMoney)
    {
        $sql = 'update ' . User::tableName()
            . ' set idealMoney = idealMoney + ' . intval($idealMoney)
            . ' where id = ' . $userId;
        return static::updateBySqlCondition($sql);
    }

    /**
     * 编辑用户信息
     * 2018.5.23
     */
    public static function updateUserInfoByUserId($params){

        $userId = $params['userId'];
        unset($params['userId']);
        //如果没有其他更新字段，不做update
        if(!empty($params)){
            $field = '';
            if(!empty($params['avatar'])){//头像
                $field .= '`avatar`="'.$params['avatar'].'",';
            }
            if(!empty($params['nickName'])){//昵称
                $field .= '`nickName`="'.$params['nickName'].'",';
            }
            if(isset($params['sex'])){ //0:女 1:男
                $field .= '`sex`='.intval($params['sex']).',';
            }
            if(!empty($params['birth'])){//生日 时间戳
                $field .= '`birth`='.intval($params['birth']).',';
            }
            if(!empty($params['address'])){//签名
                $address = explode(',',$params['address']);
                $field .= '`province`="'.$address[0].'",`city`="'.$address[1].'",`region`="'.$address[2].'",';
            }
            if(!empty($params['description'])){//签名
                $field .= '`description`="'.$params['description'].'",';
            }
            if(!empty($params['profession'])) {//职业
                $field .= '`profession`="' . $params['profession'] . '",';
            }
            if(empty($field)){
                return ['code'=>0];
            }
            $updated = $_SERVER['REQUEST_TIME'];
            $sql = "UPDATE `". User::tableName() ."` SET ".$field."`updated`={$updated} WHERE `id`={$userId}";
            if(static::updateBySqlCondition($sql)){
                return ['code'=>0];
            }
            else{
                return ['code'=>-1];
            }
        }
        else{
            return ['code'=>-1];
        }
    }
    /**
     * 检验用户信息是否认证
     */
    public static function checkUserCredentials($params){
        $userId = $params['userId'];
        $sql = "SELECT realName,idCard,mobile FROM ".User::tableName()." WHERE id=".$userId;
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($result) && !empty($result['realName']) && !empty($result['idCard'])){
            return ['code'=>0];
        }
        else{
            return ['code'=>-1];
        }
    }

    //查询用户信息
    public static function queryUserInfo($params)
    {
        $offset = 0;
        if (!empty($params['page']) && !empty($params['defaultPageSize'])) {
            $offset = ($params['page'] - 1) * $params['defaultPageSize'];
        }
        $find = static::find();
        $find = self::buildUserParams($find, $params);
        $result = $find->asArray()
            ->offset($offset)
            ->limit($params['defaultPageSize'])
            ->all();
        return $result;
    }

    //查询用户信息的绑定
    private static function buildUserParams($find, $params)
    {
        if (!empty($params['id'])) {
            $find->andWhere('id=' . trim($params['id']));
        }
        if(!empty($params['nickName'])){
            $find->andWhere('nickName like ' . trim($params['nickName']) . '%');
        }
        if(!empty($params['roomId'])){
            $find->andWhere('roomId=' . trim($params['roomId']));
        }
        if(!empty($params['mobile'])){
            $find->andWhere('mobile like ' . trim($params['mobile']) . '%');
        }
        //注册时间
        if(!empty($params['startTime'])){
            $find->andWhere(['>=','created',$params['startTime']]);
        }
        if(!empty($params['endTime'])){
            $find->andWhere(['<=','created',$params['endTime']]);
        }

        if (isset($params['content'])) {
            if (ctype_digit($params['content']) && is_numeric($params['content'])) {
                $find->andWhere('roomId =' . $params['content']);
            } else {
                $find->andWhere('nickName like \'%' . $params['content'] . '%\'');
            }
        }
        return $find;
    }

    public static function queryUserInfoNum($params)
    {
        $find = static::find();
        $find = self::buildUserParams($find, $params);
        return $find->count();
    }

    //通过用户昵称获取用户信息
    public static function queryInfoByNickName($nickName){
        $sql = "SELECT id,nickName FROM `".static ::tableName() ."` WHERE nickName LIKE '".trim($nickName) ."%' LIMIT 1";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }
}

