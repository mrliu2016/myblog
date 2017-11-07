<?php

namespace app\common\components;

use Redis;
use Yii;

class RedisClient
{

    private static $instances = [];
    private $redis;

    /**
     * @param string $name
     * @return RedisClient
     */
    public static function getInstance($name = 'default')
    {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name);
        }
        return self::$instances[$name];
    }

    private function __construct($name)
    {
        $config = Yii::$app->params['redisServer'][$name];
        $this->redis = new Redis();
        $this->redis->connect($config['host'], $config['port'], 5);
        if (!empty($config['pwd'])) {
            $this->redis->auth($config['pwd']);
            $this->redis->select($config['database']);
        } else {
            $this->redis->select($config['database']);
        }
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value)
    {
        return $this->redis->set($key, $value);
    }

    public function lpush($key, $value)
    {
        return $this->redis->lpush($key, $value);
    }

    public function rpop($key)
    {
        return $this->redis->rpop($key);
    }

    public function lpop($key)
    {
        return $this->redis->lPop($key);
    }

    public function llen($key)
    {
        return $this->redis->llen($key);
    }

    public function incr($key)
    {
        return $this->redis->incr($key);
    }

    public function expire($key, $ttl)
    {
        return $this->redis->expire($key, $ttl);
    }

    public function del($key)
    {
        return $this->redis->del($key);
    }

    public function hset($key, $field, $value)
    {
        return $this->redis->hSet($key, $field, $value);
    }

    public function hLen($key)
    {
        return $this->redis->hLen($key);
    }

    public function hget($key, $field)
    {
        return $this->redis->hGet($key, $field);
    }

    public function hVals($key)
    {
        return $this->redis->hVals($key);
    }

    public function hGetAll($key)
    {
        return $this->redis->hGetAll($key);
    }

    public function hdel($key, $field)
    {
        return $this->redis->hDel($key, $field);
    }

    public function setnx($key, $value)
    {
        return $this->redis->setnx($key, $value);
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function brPop($key, $timeout)
    {
        return $this->redis->brPop($key, $timeout);
    }

    public function lRange($key, $start, $end)
    {
        return $this->redis->lRange($key, $start, $end);
    }

    public function sAdd($key, $value)
    {
        return $this->redis->sAdd($key, $value);
    }

    public function zadd($key, $weight, $value)
    {
        return $this->redis->zAdd($key, $weight, $value);
    }

    public function zrange($key, $start, $end)
    {
        return $this->redis->zRange($key, $start, $end);
    }

    public function keys($key)
    {
        return $this->redis->keys($key);
    }

    public function zscore($key, $member)
    {
        return $this->redis->zScore($key, $member);
    }

    public function zRem($key, $member)
    {
        return $this->redis->zRem($key, $member);
    }

}
