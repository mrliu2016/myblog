<?php
namespace app\manager\models;

use Yii;

class Menu
{
    public static $allMenus = [];

    public function __construct()
    {
        if (empty(static::$allMenus)) {
            static::$allMenus = include Yii::$app->basePath . '/config/menu.php';
        }
    }

    /**
     * 获取可用菜单
     * @return array|mixed
     */
    public function getMenu()
    {
        $availablePermission = $this->getAvailablePermissionId();
        $menus = [];
        //‘/’是所有权限
        if (in_array('/', $availablePermission)) {
            $menus = static::$allMenus;
        } else {
            foreach (static::$allMenus as $menu) {
                if (in_array($menu['level1']['permissionId'], $availablePermission)) {
                    $menus[] = $menu;
                } else {
                    $level2Permission = array_column($menu['level2'], 'permissionId');
                    $hasPermission = array_intersect($level2Permission, $availablePermission);
                    if (empty($hasPermission)) {
                        continue;
                    }
                    foreach ($menu['level2'] as $k => $level2Value) {
                        if (!in_array($level2Value['permissionId'], $availablePermission)) {
                            unset($menu['level2'][$k]);
                        }
                    }
                    $menus[] = $menu;
                }
            }
        }
        $menus = $this->checkStrict4Menu($menus, $availablePermission);
        $menus = $this->checkHidden4Menu($menus);
        return $menus;
    }


    /**
     * 获取所有权限
     * @return array
     */
    public function getAllPermissionId()
    {
        $permissionIds = [];
        foreach (static::$allMenus as $v) {
            $permissionIds[] = $v['level1']['permissionId'];
            foreach ($v['level2'] as $v2) {
                $permissionIds[] = $v2['permissionId'];
            }
        }
        unset($permissionIds[2]);
        return $permissionIds;
    }

    public function getStrictPermissionId()
    {
        $strictPermissionIds = [];
        foreach (static::$allMenus as $v) {
            if (!empty($v['level1']['strict'])) {
                $strictPermissionIds[] = $v['level1']['permissionId'];
            }
            foreach ($v['level2'] as $v2) {
                if (!empty($v2['strict'])) {
                    $strictPermissionIds[] = $v2['permissionId'];
                }
            }
        }
        return $strictPermissionIds;
    }

    /**
     * 获取可用的权限
     */
    public function getAvailablePermissionId()
    {
//        if (Yii::$app->params['enableUnifiedAuthorization']) {
//            $domain = Yii::$app->controller->user->domain;
//            $permissions = $this->getAllPermissionId();
//            $permissions[] = '/';
//            $data = UnifiedAuthorization::getUserPermissions($domain, $permissions);
//            if ($data['code'] == 1001 && is_array($data['data']) && !empty($data['data'])) {
//                return $data['data'];
//            }
//            return [];
//        }
        return $this->getAllPermissionId();
    }

    public function actionMapLevel1()
    {
        $map = [];
        foreach (static::$allMenus as $menu) {
            foreach ($menu['level2'] as $v) {
                $map[$v['permissionId']] = $menu['level1']['permissionId'];
            }
        }
        return $map;
    }

    public function getLevel1PermissionByAction($action = '')
    {
        if (empty($action)) {
            return '';
        }
        $map = $this->actionMapLevel1();
        return empty($map[$action]) ? '' : $map[$action];
    }

    public function checkStrict4Action($permissions)
    {
        $strictPermissions = $this->getStrictPermissionId();
        $hasStrict = array_intersect($permissions, $strictPermissions);
        if (!empty($hasStrict)) {
            return array_values($hasStrict);
        }
        return $permissions;
    }

    public function checkStrict4Menu($menu, $availablePermission)
    {
        if (Yii::$app->params['enableUnifiedAuthorization']) {
            $return = [];
            foreach ($menu as $v) {
                if (!empty($v['level1']['strict'])) {
                    if (!in_array($v['level1']['permissionId'], $availablePermission)) {
                        continue;
                    }
                }
                foreach ($v['level2'] as $l2 => $level2V) {
                    if (!empty($level2V['strict']) && !in_array($level2V['permissionId'], $availablePermission)) {
                        unset($v['level2'][$l2]);
                    }
                }
                if (!empty($v['level2'])) {
                    $return[] = $v;
                }
            }
            return $return;
        }
        return $menu;
    }

    public function checkHidden4Menu($menus)
    {
        $return = [];
        foreach ($menus as $v) {
            if (!empty($v['level1']['hidden'])) {
                continue;
            }
            foreach ($v['level2'] as $l2 => $level2V) {
                if (!empty($level2V['hidden'])) {
                    unset($v['level2'][$l2]);
                }
            }
            if (!empty($v['level2'])) {
                $return[] = $v;
            }
        }
        return $return;
    }
}