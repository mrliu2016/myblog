<?php
/**
 * 以下新增属性均和permissionId属性同级
 * strict=1表示该权限为严格权限，必须申请才能查看，‘/’权限不包含此权限
 * hidden=1表示该权限只用于验证，在菜单栏不显示
 */
return [
    /*[
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '设置', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '个人信息', 'href' => '/user/index'],
            ['permissionId' => '/application/index', 'name' => '公共设置', 'href' => '/user/index'],
            ['permissionId' => '/application/index', 'name' => '私密设置', 'href' => '/user/index'],
            ['permissionId' => '/application/index', 'name' => '机器人设置', 'href' => '/user/index'],
            ['permissionId' => '/application/index', 'name' => '清除缓存', 'href' => '/user/index'],
        ],
    ],*/
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '用户管理', 'icon' => 'navicon.svg', 'href' => '/user/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '用户详情', 'href' => '/user/index'],
        ],
    ],
    /*[
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '等级管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '经验等级', 'href' => '/user/index'],
            ['permissionId' => '/application/index', 'name' => '主播等级', 'href' => '/user/index'],
            ['permissionId' => '/application/index', 'name' => '等级提现', 'href' => '/user/index'],
        ],
    ],*/
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '直播管理', 'icon' => 'navicon.svg', 'href' => '/live/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '直播管理', 'href' => '/live/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '直播记录', 'icon' => 'navicon.svg', 'href' => '/live/live-record'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '直播记录', 'href' => '/live/live-record'],
        ],
    ],
   /* [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '财务管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '充值记录', 'href' => '/deposit/index'],
            ['permissionId' => '/application/index', 'name' => '提现审核', 'href' => '/deposit/withdraw'],
            ['permissionId' => '/application/index', 'name' => '提现记录', 'href' => '/deposit/withdraw'],
            ['permissionId' => '/application/index', 'name' => '消费记录', 'href' => '/deposit/withdraw'],
            ['permissionId' => '/application/index', 'name' => '充值规则', 'href' => '/deposit/withdraw'],
            ['permissionId' => '/application/index', 'name' => '手动充值', 'href' => '/deposit/withdraw'],
        ],
    ],*/
 /*   [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '靓号管理', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '靓号列表', 'href' => '/deposit/index'],
        ],
    ],*/
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '礼物管理', 'icon' => 'navicon.svg', 'href' => '/gift/template'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '礼物列表', 'href' => '/gift/template'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '鉴黄管理', 'icon' => 'navicon.svg', 'href' => '/live/yellow'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '鉴黄详情', 'href' => '/live/yellow?type=3'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '举报管理', 'icon' => 'navicon.svg', 'href' => '/report/report'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '举报管理', 'href' => '/report/report'],
//            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '消息推送', 'icon' => 'navicon.svg', 'href' => '/message/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '消息推送', 'href' => '/message/index'],
//            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '违禁词管理', 'icon' => 'navicon.svg', 'href' => '/contraband/list'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '违禁词管理', 'href' => '/contraband/list'],
//            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '机器人管理', 'icon' => 'navicon.svg', 'href' => '/robot/list'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '机器人管理', 'href' => '/robot/list'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '分享管理', 'icon' => 'navicon.svg', 'href' => '/share/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '分享管理', 'href' => '/robot/list'],
        ],
    ],

];
