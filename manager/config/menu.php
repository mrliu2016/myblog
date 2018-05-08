<?php
/**
 * 以下新增属性均和permissionId属性同级
 * strict=1表示该权限为严格权限，必须申请才能查看，‘/’权限不包含此权限
 * hidden=1表示该权限只用于验证，在菜单栏不显示
 */
return [
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '设置', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '设置', 'href' => '/user/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '用户管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '用户', 'href' => '/user/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '等级管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '等级', 'href' => '/user/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '直播管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '直播管理', 'href' => '/live/index?type=1'],
            ['permissionId' => '/application/index', 'name' => '直播回放', 'href' => '/live/record?type=2'],
            ['permissionId' => '/application/index', 'name' => '鉴黄', 'href' => '/live/yellow?type=3'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '财务管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '充值历史', 'href' => '/deposit/index'],
            ['permissionId' => '/application/index', 'name' => '提现审核', 'href' => '/deposit/withdraw'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '靓号管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '靓号列表', 'href' => '/deposit/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '礼物管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '模版', 'href' => '/gift/template'],
            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '扩展工具', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '备份管理', 'href' => '/message/index'],
            ['permissionId' => '/application/index', 'name' => '文件存储', 'href' => '/message/key'],
            ['permissionId' => '/application/index', 'name' => '后台菜单', 'href' => '/message/key'],
            ['permissionId' => '/application/index', 'name' => '幻灯片', 'href' => '/message/key']
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '内容管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '用户反馈', 'href' => '/message/index'],
            ['permissionId' => '/application/index', 'name' => '页面管理', 'href' => '/message/key'],
            ['permissionId' => '/application/index', 'name' => '分类管理', 'href' => '/message/key'],
            ['permissionId' => '/application/index', 'name' => '文章管理', 'href' => '/message/key']
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
