<?php
/**
 * 以下新增属性均和permissionId属性同级
 * strict=1表示该权限为严格权限，必须申请才能查看，‘/’权限不包含此权限
 * hidden=1表示该权限只用于验证，在菜单栏不显示
 */
return [
    [
        'level1' => ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '基础功能', 'icon' => 'grid.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '应用信息', 'href' => '/config/application'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '运营商', 'href' => '/config/operator'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '地区', 'href' => '/config/province'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '操作日志', 'href' => '/config/operation-log'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '获取陌陌Id', 'href' => '/report/momoid'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => 'mcu策略组', 'href' => '/strategy/get-strategy-list'],
        ]
    ],
    [
        'level1' => ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '服务器配置', 'icon' => 'radio-waves.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '服务器信息', 'href' => '/server/index'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '区域覆盖配置', 'href' => '/server/route'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '区域覆盖配置查看', 'href' => '/server/route-detail'],
            ['permissionId' => [\app\common\services\Constants::ROLE_USER], 'name' => '白名单', 'href' => '/config/white-list'],
        ]
    ],
];