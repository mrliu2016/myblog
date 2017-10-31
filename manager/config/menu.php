<?php
/**
 * 以下新增属性均和permissionId属性同级
 * strict=1表示该权限为严格权限，必须申请才能查看，‘/’权限不包含此权限
 * hidden=1表示该权限只用于验证，在菜单栏不显示
 */
return [
    [
        'level1' => ['permissionId' => 'all-selection-manage', 'name' => '基础信息', 'icon' => 'navicon.svg', 'href' => '#'],
        'level2' => [
            ['permissionId' => '/application/index', 'name' => '用户', 'href' => '/index/index'],
        ],
    ],
];
