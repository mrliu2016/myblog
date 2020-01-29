<?php

use yii\widgets\LinkPager;

?>
<link rel="stylesheet" href="/css/font.css">
<link rel="stylesheet" href="/css/xadmin.css">
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/xadmin.js"></script>
<script type="text/javascript" src="/js/cookie.js"></script>
<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
<script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
    .layui-layer-iframe {
        width: 500px !important;
        height: 300px !important;
    }
</style>

<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">分类管理</a>
        <a>
          <cite>分类列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <input type="text" name="name" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <xblock>
        <!--        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>-->
        <button class="layui-btn" onclick="x_admin_show('添加分类','/cate/add')"><i class="layui-icon"></i>添加分类
        </button>
        <!--        <span class="x-right" style="line-height:40px">共有数据：--><? //= $count ?><!-- 条</span>-->
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <!--            <th>-->
            <!--                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i-->
            <!--                            class="layui-icon">&#xe605;</i></div>-->
            <!--            </th>-->
            <th>ID</th>
            <th>名称</th>
            <th>创建时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php if (!empty($list)): ?>
            <?php foreach ($list as $key => $val): ?>
                <tr>
                    <!--                    <td>-->
                    <!--                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='2'><i-->
                    <!--                                    class="layui-icon">&#xe605;</i>-->
                    <!--                        </div>-->
                    <!--                    </td>-->
                    <td>
                        <?= $val['id'] ?>
                    </td>
                    <td><?= $val['name'] ?></td>
                    <td><?= date('Y-m-d H:i:s', $val['created']) ?></td>
                    <td class="td-manage">
                        <a title="编辑"
                           onclick="x_admin_show('编辑','/cate/edit?id=<?= $val['id'] ?>&name=<?= $val['name'] ?>')"
                           href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" onclick="cate_del(this,<?= $val['id'] ?>)" href="javascript:;">
                            <i class="layui-icon">&#xe640;</i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        </tbody>
    </table>
    <div class="page">
        <nav class="text-center">
            <table>
                <tr>
                    <td> <?= LinkPager::widget(['pagination' => $pagination]) ?></td>
                    <td>共<?= $pagination->totalCount ?> 条</td>
                </tr>
            </table>
        </nav>
    </div>

</div>
<script>
    layui.use('laydate', function () {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });


    /*分类-删除*/
    function cate_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $(obj).parents("tr").remove();

            var params = {};
            params.id = id;
            $.ajax({
                url: "/cate/del",
                type: "get",
                cache: false,
                data: params,
                dataType: "json",
                success: function (response) {
                    switch (parseInt(response.code)) {
                        case 0:
                            layer.msg('已删除!', {icon: 1, time: 1000});
                            window.location.href = window.location.href;
                            break;
                        case -1:
                            break;
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg('系统繁忙');
                }
            });

        });
    }


    function delAll(argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？' + data, function (index) {
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
    }
</script>
<script>var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>

