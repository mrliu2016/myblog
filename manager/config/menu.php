<?php
/**
 * 以下新增属性均和permissionId属性同级
 * strict=1表示该权限为严格权限，必须申请才能查看，‘/’权限不包含此权限
 * hidden=1表示该权限只用于验证，在菜单栏不显示
 */
return [
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '用户管理', 'icon' => 'yonghuguanli.png', 'href' => '/user/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '用户详情', 'href' => '/user/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '直播管理', 'icon' => 'zhiboguanli.png', 'href' => '/live/index'],
        'level2' => [
            ['permissionId' => '/live/test', 'name' => '上传图片', 'href' => '/live/index'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '直播记录', 'icon' => 'zhibojilu.png', 'href' => '/live/live-record'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '直播记录', 'href' => '/live/live-record'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '礼物管理', 'icon' => 'liwuguanli.png', 'href' => '/gift/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '礼物列表', 'href' => '/gift/template'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '鉴黄管理', 'icon' => 'jianhuangguanli.png', 'href' => '/live/yellow'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '鉴黄详情', 'href' => '/live/yellow?type=3'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '举报管理', 'icon' => 'jubaoguanli.png', 'href' => '/report/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '举报管理', 'href' => '/report/report'],
//            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '消息推送', 'icon' => 'xiaoxituisong.png', 'href' => '/message/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '消息推送', 'href' => '/message/index'],
//            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '违禁词管理', 'icon' => 'weijinciguanli.png', 'href' => '/contraband/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '违禁词管理', 'href' => '/contraband/index'],
//            ['permissionId' => '/application/index', 'name' => '赠送', 'href' => '/gift/order'],
        ],
    ],
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '机器人管理', 'icon' => 'jiqirenguanli.png', 'href' => '/robot/index'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '机器人管理', 'href' => '/robot/list'],
        ],
    ],
];
