<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台登录</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>
    <script type="text/javascript" src="/js/cookie.js"></script>

</head>
<body class="login-bg">

<div class="login layui-anim layui-anim-up">
    <div class="message">管理登录</div>
    <div id="darkbannerwrap"></div>

    <!--    <form method="post" class="layui-form">-->
    <div class="layui-form">
        <input id="username" placeholder="用户名" type="text" lay-verify="required" class="layui-input" autocomplete="off">
        <hr class="hr15">
        <input id="password" lay-verify="required" placeholder="密码" type="password" class="layui-input"
               autocomplete="off">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="button" onclick="login()">
        <hr class="hr20">
    </div>

    <!--    </form>-->
</div>
<?php $this->beginContent('@app/views/layouts/footer.php'); ?>
<?php $this->endContent(); ?>

<script>
    /*分类-删除*/
    function login() {
        // layer.confirm('确认要删除吗？', function (index) {
        //发异步删除数据
        // $(obj).parents("tr").remove();
        var params = {};
        params.username = $("#username").val();
        params.password = $("#password").val();

        $.ajax({
            url: "/user/login",
            type: "post",
            cache: false,
            data: params,
            dataType: "json",
            success: function (response) {
                switch (parseInt(response.code)) {
                    case 0:
                        layer.msg('登录成功!', {icon: 1, time: 1000});
                        window.location.href = '/';
                        break;
                    case -1:
                        layer.msg('账号或密码错误!', {icon: 1, time: 1000});
                        break;
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.msg('系统繁忙');
            }
        });

        // });
    }


</script>

<!-- 底部结束 -->
<script>
    //百度统计可去掉
    // var _hmt = _hmt || [];
    // (function () {
    //     var hm = document.createElement("script");
    //     hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
    //     var s = document.getElementsByTagName("script")[0];
    //     s.parentNode.insertBefore(hm, s);
    // })();
</script>
</body>
</html>