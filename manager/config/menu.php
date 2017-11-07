<?php
/**
 * 以下新增属性均和permissionId属性同级
 * strict=1表示该权限为严格权限，必须申请才能查看，‘/’权限不包含此权限
 * hidden=1表示该权限只用于验证，在菜单栏不显示
 */
return [
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '用户管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '用户', 'href' => '/user/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '直播管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '直播管理', 'href' => '/live/index'],
            ['permissionId' => '/application/index', 'name' => '直播回放', 'href' => '/live/record'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '充值&提现', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '充值历史', 'href' => '/deposit/record'],
            ['permissionId' => '/application/index', 'name' => '提现审核', 'href' => '/deposit/withdraw'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '礼物', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '模版', 'href' => '/gift/template'],
            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '消息', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '消息', 'href' => '/message/index'],
            ['permissionId' => '/application/index', 'name' => '关键字', 'href' => '/message/key'],
        ],
    ],
];
