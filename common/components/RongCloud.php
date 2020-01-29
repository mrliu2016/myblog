<?php
/**
 * 融云 Server API PHP 客户端
 * create by kitName
 * create datetime : 2017-02-09
 *
 * v2.0.1
 */

namespace app\common\components;

use app\common\extensions\RongCloud\RongCloud as RongCloudExtensions;
use Yii;

class RongCloud
{
    private static $rongCloud = null;

    /**
     * 获取融云token
     *
     * @param $userId
     * @param $nickName
     * @param null $avatar
     * @return mixed
     */
    public static function getToken($userId, $nickName, $avatar = null)
    {
        $config = Yii::$app->params['rongCloud'];
        static::$rongCloud = new RongCloudExtensions($config['appKey'], $config['appSecret']);
        $result = static::$rongCloud->user()
            ->getToken($userId, $nickName, $avatar);
        return $result;
    }
}

