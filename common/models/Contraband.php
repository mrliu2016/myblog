<?php
namespace app\common\models;

use app\common\components\RedisClient;
use app\common\services\Constants;
use yii\db\ActiveRecord;
use Yii;

//违禁词
class Contraband extends ActiveRecord{

    public static function tableName()
    {
        return 't_contraband';
    }

    public static function queryById($id, $isObject = false)
    {
        if ($isObject) {
            return static::find()->where(['id' => $id])->one();
        } else {
            return static::find()->where(['id' => $id])->asArray()->one();
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
        $result = $find->asArray()
            ->orderBy('updated desc')
            ->offset($offset)->limit($params['defaultPageSize'])->all();
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

        if (!empty($params['word'])) $params['word'] = trim($params['word']);
        if (!empty($params['word'])) {
            $find->andWhere(['like', 'word', addslashes($params['word'])]);
        }
        if(!empty($params['id'])){
            $find->andWhere('id='.intval($params['id']));
        }
        if(isset($params['isDelete'])){
            $find->andWhere('isDelete='.$params['isDelete']);
        }
        return $find;
    }

    //删除违禁词
    public static function deleteWord($id){
        $model = static::find()->andWhere(['id' => $id])->one();
        $model->isDelete = 1;
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        Contraband::refreshRedis();
        return $model->id;
    }

    //编辑违禁词
    public static function editWord($params){
        $model = static::find()->andWhere(['id' => $params['id']])->one();
        $model->word    = $params['word'];
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        Contraband::refreshRedis();
        return $model->id;
    }

    //新增违禁词
    public static function addWord($params){
        $model = new self();
        $model->word = trim($params['word']);
        $model->created = $_SERVER['REQUEST_TIME'];
        $model->updated = $_SERVER['REQUEST_TIME'];
        $model->save();
        return $model->id;
    }

    public static function executeBySqlCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->execute();
    }

    public static function queryBySQLCondition($sql = '')
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    //批量导入违禁词
    public static function batchWord($data){

        if(!empty($data)){
            $sql = 'INSERT INTO ' . static::tableName() . ' (`word`, `created`, `updated`) values ';
            $value = '';
            foreach ($data as $key => $val){
                $value .= '(';
                $value .= '\''.$val.'\',';
                $value .= $_SERVER['REQUEST_TIME'].',';
                $value .= $_SERVER['REQUEST_TIME'];
                $value  .= '),';

            }
            $sql .= trim($value, ',');
            static ::executeBySqlCondition($sql);
            Contraband::refreshRedis();
            return ['code'=>0];
        }
        else{
            return ['code'=>-1];
        }
    }

    //查询出所有的违禁词
    public static function queryAllInfo(){

        $sql = "SELECT word FROM ".static ::tableName() ." WHERE isDelete = 0";
        $result = static ::queryBySQLCondition($sql);
        return $result;
    }
    /*
     * @author  lhz
     * @date 2018.6.8
     * @des 在redis中更新违禁词
     */
    public static function refreshRedis(){
        $data = Contraband::queryAllInfo();
        if(!empty($data)){
            $word = array();
            foreach ($data as $v){
                $word[] = $v['word'];
            }
            $redis = RedisClient::getInstance();
            $keyWords = array_filter($word);
            $keyWords = array_unique($keyWords);
            $redis->set(Constants::WS_BANNED_WORD,base64_encode(json_encode($keyWords)));
//            $redis->expire(Constants::WS_BANNED_WORD,-1);
            return ['code' => 0];
        }
        return ['code' => -1];
    }
}
