<?php

use app\backend\models\Menu;

$menus = (new Menu())->getMenu();

$admin_name = \Yii::$app->session['admin_name'];
?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台管理系统</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>
    <script src="/lib/layui/layui.js" charset="utf-8"></script>

    <script type="text/javascript" src="/js/xadmin.js?a=11"></script>
    <script type="text/javascript" src="/js/cookie.js?a=11"></script>
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>

    <style>
        .layui-layer-iframe {
            width: 680px !important;
            height: 450px !important;
        }
    </style>
</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="/">后台管理系统</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <!--    <ul class="layui-nav left fast-add" lay-filter="">-->
    <!--        <li class="layui-nav-item">-->
    <!--            <a href="javascript:;">+新增</a>-->
    <!--            <dl class="layui-nav-child"> <!-- 二级菜单 -->-->
    <!--                <dd><a onclick="x_admin_show('资讯','https://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>-->
    <!--                <dd><a onclick="x_admin_show('图片','https://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>-->
    <!--                <dd><a onclick="x_admin_show('用户 最大化','https://www.baidu.com','','',true)"><i-->
    <!--                                class="iconfont">&#xe6b8;</i>用户最大化</a></dd>-->
    <!--                <dd><a onclick="x_admin_add_to_tab('在tab打开','https://www.baidu.com',true)"><i-->
    <!--                                class="iconfont">&#xe6b8;</i>在tab打开</a></dd>-->
    <!--            </dl>-->
    <!--        </li>-->
    <!--    </ul>-->
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:void(0);"><?= $admin_name ?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('个人信息','/admin/info')">个人信息</a></dd>
                <!--                <dd><a onclick="x_admin_show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>-->
                <dd><a href="/user/logout">退出</a></dd>
            </dl>
        </li>
        <!--        <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>-->
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<?php $this->beginContent('@app/views/layouts/left_nav.php'); ?>
<?php $this->endContent(); ?>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
        </ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd>
            </dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='/index/desktop' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<?php $this->beginContent('@app/views/layouts/footer.php'); ?>
<?php $this->endContent(); ?>
<!-- 底部结束 -->
<script>
    //百度统计可去掉
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>
