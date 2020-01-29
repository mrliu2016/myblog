<?php

namespace app\common\services;

use app\common\models\ReleaseInfo;
use app\common\models\StickyCode;

class PlatformService
{
    /**
     * 更新置顶状态
     * 异步更新
     */
    public static function updateInfoStatus()
    {
        try {
            $list = ReleaseInfo::queryStickInfo();
            if (!empty($list)) {
                $timeStamp = $_SERVER['REQUEST_TIME'];
                $infoId = [];
                foreach ($list as $key => $val) {
                    if ($val['valid_time'] <= $timeStamp) {
                        $infoId[] = $val['id'];
                    }
                }
                $ids = implode(',', $infoId);
                //更新
                ReleaseInfo::updateStickStatus($ids);
            }
            exit('finish');
        } catch (\Exception $e) {
            ll($e->getMessage(), 'updateInfoStatus.log');
        }
    }

    /**
     * 更新置顶码状态
     */
    public static function updateCodeStatus()
    {
        try {
            $list = StickyCode::queryCodeInfo();
            if (!empty($list)) {
                $timeStamp = $_SERVER['REQUEST_TIME'];
                $codeId = [];
                foreach ($list as $key => $val) {
                    if ($val['valid_time'] <= $timeStamp) {
                        $codeId[] = $val['id'];
                    }
                }
                $ids = implode(',', $codeId);
                //更新
                StickyCode::updateCodeStatus($ids);
            }
            exit('finish');
        } catch (\Exception $e) {
            ll($e->getMessage(), 'updateCodeStatus.log');
        }
    }
}