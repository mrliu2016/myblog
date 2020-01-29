<?php

namespace app\console\controllers;

use app\common\services\PlatformService;

class PlatformController
{

    /**
     * 更新置顶状态
     * 异步更新
     */
    public static function updateInfoStatus()
    {
        PlatformService::updateInfoStatus();
    }

    /**
     * 更新置顶码状态
     */
    public static function updateStickStatus()
    {
        PlatformService::updateCodeStatus();
    }

}