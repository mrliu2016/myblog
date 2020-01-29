<?php

namespace app\backend\models\unified;

use app\common\components\AHelper;
use app\common\components\ParseUtils;
use app\common\services\Constants;
use Yii;
use yii\base\ActionFilter;
use yii\web\Cookie;

class UnifiedLoginFilter extends ActionFilter
{
    const LOG_FILE = 'unified_login_filter.log';

    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        //当前域名
        $host = ParseUtils::removePort($_SERVER['HTTP_HOST']);
        $ticketFromCookie = $request->getCookies()->getValue('unifiedLoginTicket');
//        $loginUrl = Yii::$app->params["ucDomain"] . "/user/login?redirect=" . urlencode($request->absoluteUrl);
//        if ($ticketFromCookie == null) {
//            Yii::$app->getResponse()->redirect($loginUrl);
//            return false;
//        }

        //反序列化用户信息
//        $loginUser = unserialize(base64_decode($ticketFromCookie));
//        //用户平台权限
//        $platList = explode('#', $loginUser->platAccess);
//        $loginUser->role = self::getPlatformRole($platList, $loginUser->roleMapping, $host);
//        Yii::$app->controller->user = $loginUser;
//        //有效的平台域名
//        $platDomainList = self::getPlatDomain($platList);
//


        return parent::beforeAction($action);
    }

    //身份鉴定失败移除cookie中的信息
    private static function removeCookie($platList)
    {
        Yii::$app->getResponse()->cookies->add(new Cookie([
            'name' => Constants::COOKIE_UNIFIED_LOGIN,
            'value' => '',
            'expire' => time() - 1000,
            'domain' => Constants::COOKIE_DOMAIN
        ]));
        foreach ($platList as $plat) {
            $key = Constants::COOKIE_PLAT_DOMAIN_ACCESS . $plat;
            $cookie = new Cookie([
                'name' => $key,
                'value' => '',
                'expire' => time() - 1000,
                'domain' => Constants::COOKIE_DOMAIN
            ]);
            Yii::$app->response->cookies->add($cookie);
        }
    }

    //从cookie中解析有有权限的平台域名
    public static function getPlatDomain($platList)
    {
        $platDomainList = [];
        foreach ($platList as $plat) {
            $key = Constants::COOKIE_PLAT_DOMAIN_ACCESS . $plat;
            $ticketFromCookie = Yii::$app->request->getCookies()->getValue($key);
            if (!empty($ticketFromCookie)) {
                $list = explode(',', $ticketFromCookie);
                foreach ($list as $host) {
                    $platDomainList[] = ParseUtils::removePort($host);
                }
            }
        }
        return $platDomainList;
    }

    /***
     * 获取平台角色
     * @param $roleMapping
     * @param $host
     * @return string
     */
    public static function getPlatformRole($platformList, $roleMapping, $host)
    {
        $platform = self::getPlatform($platformList, $host);
        $role = empty($roleMapping[$platform]) ? '' : $roleMapping[$platform];
        /*
         * 本地环境因为域名相同会出现权限问题
         * 测试环境&预发&生产环境正常
         * 本地环境可以直接修改角色返回值设置平台角色
         * */
//        return Constants::ROLE_ADMIN;
        return $role;
    }

    /***
     * 根据域名获取平台
     * @param $platformList
     * @param $host
     * @return string
     */
    private static function getPlatform($platformList, $host)
    {
        foreach ($platformList as $plat) {
            $key = Constants::COOKIE_PLAT_DOMAIN_ACCESS . $plat;
            $ticketFromCookie = Yii::$app->request->getCookies()->getValue($key);
            if (!empty($ticketFromCookie)) {
                $list = explode(',', $ticketFromCookie);
                if (in_array($host, $list)) return $plat;
            }
        }
        return '';
    }
}