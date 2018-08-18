<?php

namespace app\common\services;

use app\common\components\IPUtils;
use app\common\components\RedisClient;
use app\common\components\ServerResources;

class ServerResourcesService
{
    /**
     * 注册服务器
     */
    public static function registerServer()
    {
        $redis = RedisClient::getInstance();
        $ip = IPUtils::getServerIP();
        if ($redis->hexists(Constants::WS_REGISTER_SERVER, $ip)) {
            return true;
        }
        $result = [
            'ip' => $ip,
            'domain' => $ip,
            'wssPort' => Constants::WEB_SOCKET_PORT_SSL,
            'wsPort' => Constants::WEB_SOCKET_PORT_WS,
            'udpPort' => Constants::WEB_SOCKET_PORT_UDP,
            'loadAvgNum' => Constants::WS_WEB_SOCKET_MAX_CONNECTION,
            'connectionNum' => intval(false),
            'isOnline' => intval(true),
        ];
        $redis->hset(Constants::WS_REGISTER_SERVER, $ip, json_encode($result));
        $redis->expire(Constants::WS_REGISTER_SERVER, Constants::DEFAULT_EXPIRES);
    }

    /**
     * 获取注册服务器
     *
     * @return array
     */
    public static function getRegisterServer()
    {
        $redis = RedisClient::getInstance();
        $result = [];
        if ($redis->exists(Constants::WS_REGISTER_SERVER)) {
            $server = $redis->hGetAll(Constants::WS_REGISTER_SERVER);
            foreach ($server as $key => $value) {
                $result[] = json_decode($value, true);
            }
        }
        return $result;
    }

    /**
     *
     * 获取 Web Socket 服务器
     *
     * hset
     * ServerLoadAvg ip {ip,port,loadAvgNum,connectionNum,isOnline}
     *
     * @param $params
     * @param null $server
     * @param bool $flag
     * @return array
     */
    public static function getWebSocketServer($params, $server = null, $flag = false)
    {
        $serverList = [];
        $redis = $server ? $server->redis : RedisClient::getInstance();
        if ($redis->exists(Constants::WS_REGISTER_SERVER)) {
            $server = $redis->hGetAll(Constants::WS_REGISTER_SERVER);
            foreach ($server as $key => $value) {
                $item = Base64JsonConvert::jsonDecode($value, true);
                if (!empty($item) && $item['isOnline']) {
                    if ($item['connectionNum'] < $item['loadAvgNum']) {
                        $item['code'] = Constants::CODE_SUCCESS;
                        $serverList[] = $item;
                    }
                }
            }
            if (empty($serverList)) {
                return ['code' => Constants::CODE_FAILED];
            }
            return static::distributeServerNode($params['ip'], $serverList);
        }
        return ['code' => Constants::CODE_FAILED];
    }

    /**
     * 更新对应服务器负载信息
     *
     * @param $server
     * @param $counter
     */
    public
    static function updatedServerLoadAvg($server, $counter)
    {
        if ($server->redis->exists(Constants::WS_REGISTER_SERVER)) {
            $ip = IPUtils::getServerIP();
            $serverItem = Base64JsonConvert::jsonDecode($server->redis->hget(Constants::WS_REGISTER_SERVER, $ip), true);
            if (!empty($serverItem) && (!empty($serverItem['isOnline']) || ($counter <= Constants::WS_WEB_SOCKET_MAX_CONNECTION))) {
                $serverItem['connectionNum'] = $counter;
                if ($serverItem['connectionNum'] >= $serverItem['loadAvgNum']) {
                    $serverItem['isOnline'] = intval(false);
                } else {
                    $serverItem['isOnline'] = intval(true);
                }
                $server->redis->hset(Constants::WS_REGISTER_SERVER, $ip, json_encode($serverItem));
            }
        }
    }

    /**
     * cpu 资源
     *
     * @return array
     */
    public static function cpuResource()
    {
        return ServerResources::getCpuLoadAvg();
    }

    /**
     * 内存资源
     *
     * @return array
     */
    public static function memoryResource()
    {
        return ServerResources::getMemoryLoadAvgFromMemInfo();
    }

    /**
     * 磁盘资源
     *
     * @return mixed
     */
    public static function distResource()
    {
        return ServerResources::getDistLoadAvg();
    }

    /**
     * 带宽资源
     */
    public static function networkResource()
    {
        return ServerResources::getNetworkLoadAvg();
    }

    /**
     * 服务器资源
     *
     * hset ServerResources ip {cpu、mem、disk}
     *
     * @param $resource
     */
    public static function serverResource($resource)
    {
        $redis = RedisClient::getInstance();
        $redis->hset(
            Constants::WS_SERVER_RESOURCES,
            IPUtils::getServerIP(),
            is_array($resource) ? json_encode($resource) : ''
        );
        $redis->expire(Constants::WS_SERVER_RESOURCES, Constants::DEFAULT_EXPIRES);
    }

    /**
     * 服务器心跳
     *
     * hset ServerHeartbeat ip {lastTime}
     *
     * @param $time
     */
    public static function serverHeartbeat($time)
    {
        $redis = RedisClient::getInstance();
        $redis->hset(
            Constants::WS_SERVER_HEARTBEAT,
            IPUtils::getServerIP(),
            $time
        );
        $redis->expire(Constants::WS_SERVER_HEARTBEAT, Constants::DEFAULT_EXPIRES);
    }

    /**
     * 分配服务器节点
     *
     * @param $crcIndex
     * @param $nodes
     * @return mixed
     */
    private
    static function distributeServerNode($crcIndex, $nodes)
    {
        $buckets = []; // 节点的hash字典
//        $ascBuckets = [];
        /**
         * 生成节点字典 —— 使节点分布在单位区间[0,1)的圆上
         */
        $index = 1;
        foreach ($nodes as $key => $value) {
            // 每个节点的复制的个数
//            for ($index = 1; $index <= Constants::WEB_SOCKET_NODE_REPLICAS; $index++) {
//                $crc = crc32($value['ip'] . ' . ' . $index) / pow(2, 32); // CRC値
//                $buckets[] = ['index' => $crc, 'node' => $value];
//            }
            $buckets[$index] = $value;
            ++$index;
        }
        // 冒泡排序 升序
        $count = count($buckets);
        for ($index = 1; $index <= $count; $index++) {
            for ($ascIndex = 1; $ascIndex <= $index; $ascIndex++) {
                if ($buckets[$ascIndex]['connectionNum'] > $buckets[$index]['connectionNum']) {
                    $tmp = $buckets[$ascIndex];
                    $buckets[$ascIndex] = $buckets[$index];
                    $buckets[$index] = $tmp;
                }
            }
        }
        // 返回连接人数最少的节点
        return $buckets[intval(true)];

        sort($buckets); // 根据索引进行排序

        /**
         * 对每个 crcIndex 进行hash计算，找到其在圆上的位置，然后在该位置开始依顺时针方向找到第一个服务节点
         */
        $flag = false;
        $crc = crc32($crcIndex) / pow(2, 32); // 计算 crcIndex 的hash值
        $bucketsCount = count($buckets);
        for ($index = 0; $index < $bucketsCount; $index++) {
            if ($buckets[$index]['index'] > $crc) {
                /*
                 * 因为已经对buckets进行了排序
                 * 所以第一个index大于key的hash值的节点即是要找的节点
                 */
                return $buckets[$index]['node'];
            }
        }
        // 未找到，则使用 buckets 中的第一个服务节点
        if (!$flag) {
            return $buckets[0]['node'];
        }
    }

    /**
     *
     * 房间用户服务器分布
     *
     * hset RoomServerList:appId:roomId ip udpPort 房间用户服务器分布
     *
     * @param $params
     * @param $server
     * @param null $webSocket
     */
    public
    static function roomServerList($params, $server, $webSocket = null)
    {
        $redis = $webSocket ? $webSocket->redis : RedisClient::getInstance();
        $key = Constants::WS_ROOM_SERVER_LIST . ':' . $params['appId'] . ':' . $params['roomId'];
        if (!($redis->hexists($key, $server['ip']))) {
            $redis->hset($key, $server['ip'], $server['udpPort']);
            $redis->expire($key, Constants::WS_DEFAULT_EXPIRE);
        }
    }

    /**
     * 设置 应用、房间 映射关系
     *
     * AppIdRoomMap:roomId roomId appId
     *
     * api 接口调用
     *
     * @param $server
     * @param $message
     * @param null $webSocket
     */
    public static function setAppIdRoomMap($server, $message, $webSocket = null)
    {
        $redis = $webSocket ? $webSocket->redis : RedisClient::getInstance();
        $key = Constants::WS_APPLICATION_ID_ROOM_MAP;
        if (!($redis->hexists($key, $message['roomId']))) {
            $redis->hset($key, $message['roomId'], $message['appId']);
            $redis->expire($key, Constants::WS_DEFAULT_EXPIRE);
        }
    }

    /**
     * 获取服务器资源
     *
     * @return array
     */
    public static function getServerResource()
    {
        $default = 0.0;
        $redis = RedisClient::getInstance();
        $registerServer = static::getRegisterServer();
        foreach ($registerServer as $ip => $value) {
            $registerServer[$ip]['heartbeat'] = date('Y-m-d H:i:s', $redis->hget(Constants::WS_SERVER_HEARTBEAT, $value['ip']));
            $resource = json_decode($redis->hget(Constants::WS_SERVER_RESOURCES, $value['ip']), true);
            foreach ($resource as $key => $item) {
                switch ($key) {
                    case 'cpu':
                        $registerServer[$ip]['idle'] = $item['idle'];
                        $registerServer[$ip]['us'] = $item['us'];
                        $registerServer[$ip]['sy'] = $item['sy'];
                        $registerServer[$ip]['wa'] = $item['wa'];
                        $registerServer[$ip]['hi'] = $item['hi'];
                        $registerServer[$ip]['si'] = $item['si'];
                        $registerServer[$ip]['st'] = $item['st'];
                        break;
                    case 'memory':
                        $registerServer[$ip]['memTotal'] = $item['memTotal'];
                        $registerServer[$ip]['memFree'] = $item['memFree'];
                        $registerServer[$ip]['memBuffers'] = $item['memBuffers'];
                        $registerServer[$ip]['memCached'] = $item['memCached'];
                        $registerServer[$ip]['memUsed'] = $item['memUsed'];
                        $registerServer[$ip]['memPercent'] = $item['memPercent'];
                        $registerServer[$ip]['memRealUsed'] = $item['memRealUsed'];
                        $registerServer[$ip]['memRealFree'] = $item['memRealFree'];
                        $registerServer[$ip]['memRealPercent'] = $item['memRealPercent'];
                        $registerServer[$ip]['memCachedPercent'] = $item['memCachedPercent'];
                        break;
                    case 'disk':
                        $registerServer[$ip]['total'] = $item['total'];
                        $registerServer[$ip]['free'] = $item['free'];
                        $registerServer[$ip]['used'] = $item['used'];
                        $registerServer[$ip]['usage'] = $item['usage'];
                        break;
                    case 'network':
                        $registerServer[$ip]['incoming'] = $item['incoming'];
                        $registerServer[$ip]['outgoing'] = $item['outgoing'];
                        break;
                }
            }
            if (empty($resource)) {
                $registerServer[$ip]['idle'] = $default;
                $registerServer[$ip]['us'] = $default;
                $registerServer[$ip]['sy'] = $default;
                $registerServer[$ip]['wa'] = $default;
                $registerServer[$ip]['hi'] = $default;
                $registerServer[$ip]['si'] = $default;
                $registerServer[$ip]['st'] = $default;
                $registerServer[$ip]['memTotal'] = $default;
                $registerServer[$ip]['memFree'] = $default;
                $registerServer[$ip]['memBuffers'] = $default;
                $registerServer[$ip]['memCached'] = $default;
                $registerServer[$ip]['memUsed'] = $default;
                $registerServer[$ip]['memPercent'] = $default;
                $registerServer[$ip]['memRealUsed'] = $default;
                $registerServer[$ip]['memRealFree'] = $default;
                $registerServer[$ip]['memRealPercent'] = $default;
                $registerServer[$ip]['memCachedPercent'] = $default;
                $registerServer[$ip]['total'] = $default;
                $registerServer[$ip]['free'] = $default;
                $registerServer[$ip]['used'] = $default;
                $registerServer[$ip]['usage'] = $default;
                $registerServer[$ip]['incoming'] = $default;
                $registerServer[$ip]['outgoing'] = $default;
            }
        }
        return $registerServer;
    }

    /**
     * 开启、关闭 服务器调度
     *
     * @param $params
     * @return bool
     */
    public static function openCloseDispatchLocation($params)
    {
        $redis = RedisClient::getInstance();
        $key = Constants::WS_REGISTER_SERVER;
        if ($redis->hExists($key, $params['ip'])) {
            $result = json_decode($redis->hget($key, $params['ip']), true);
            $result['isOnline'] = intval($params['type']);
            $redis->hset($key, $params['ip'], json_encode($result));
            return true;
        }
        return false;
    }

    /**
     * 调整服务器负载
     *
     * @param $params
     * @return array|bool
     */
    public static function resetLoadAvg($params)
    {
        $redis = RedisClient::getInstance();
        $key = Constants::WS_REGISTER_SERVER;
        if ($redis->hExists($key, $params['ip'])) {
            $result = json_decode($redis->hget($key, $params['ip']), true);
            if (intval(false) === intval($params['loadAvg'])) {
                return ['code' => Constants::CODE_FAILED, 'message' => '负载数不能小于0'];
            }
            if (intval($params['loadAvg']) < $result['connectionNum']) {
                return ['code' => Constants::CODE_FAILED, 'message' => '负载数不能小于当前已连接数'];
            }
            if (intval($params['loadAvg']) > Constants::WEB_SOCKET_MAX_CONNECTION) {
                return ['code' => Constants::CODE_FAILED, 'message' => '负载数不能大于当前系统文件打开数'];
            }
            $result['loadAvgNum'] = $params['loadAvg'];
            $result['isOnline'] = intval(true);
            $redis->hset($key, $params['ip'], json_encode($result));
            return ['code' => Constants::CODE_SUCCESS];
        }
        return ['code' => Constants::CODE_FAILED, 'message' => $params['ip'] . ' 服务器未运行'];
    }

    /**
     * 脚本详情
     *
     * @return array
     */
    public static function script()
    {
        $result = [];
        $redis = RedisClient::getInstance();
        $script = $redis->hGetAll(Constants::SCRIPT_MONITOR);
        foreach ($script as $key => $value) {
            $key = explode(':', $key);
            $value = explode('_', $value);
            $result[] = [
                'ip' => $key[0],
                'index' => str_replace('.', '-', $key[0]),
                'name' => $key[1],
                'heartbeat' => date('Y-m-d H:i:s', $value[0]),
                'timeConsuming' => $value[1],
            ];
        }
        return $result;
    }
}