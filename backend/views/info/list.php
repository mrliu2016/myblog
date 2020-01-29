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
    .active a {
        background-color: #0d2b23 !important;
    }

    #dateSelect {
        width: 200px;
    }

    .layui-layer-iframe {
        width: 680px !important;
        height: 400px !important;
    }
</style>

<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">信息管理</a>
        <a>
          <cite>信息列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <div class="layui-inline">
                <label class="layui-form-label">日期范围</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="dateSelect" name="dateSelect" placeholder=" - "
                           value="<?= isset($params['dateSelect']) ? $params['dateSelect'] : '' ?>"
                           autocomplete="off">
                </div>
            </div>
            <div class="layui-input-inline">
                <select name="categoryId">
                    <?php if (!empty($categoryList)): ?>
                        <option value="">全部</option>
                        <?php foreach ($categoryList as $key => $val): ?>
                            <?php if (!empty($params['categoryId']) && $params['categoryId'] == $key): ?>
                                <option value="<?= $key ?>" selected><?= $val ?></option>
                            <?php else: ?>
                                <option value="<?= $key ?>"><?= $val ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">全部</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="top">
                    <?php if ($params['top'] == ""): ?>
                        <option value="" selected>全部</option>
                        <option value="1">置顶</option>
                        <option value="0">未置顶</option>
                    <?php elseif ($params['top'] == 0): ?>
                        <option value="">全部</option>
                        <option value="1">置顶</option>
                        <option value="0" selected>未置顶</option>
                    <?php elseif ($params['top'] == 1): ?>
                        <option value="">全部</option>
                        <option value="1" selected>置顶</option>
                        <option value="0">未置顶</option>
                    <?php endif; ?>
                </select>
            </div>
            <input type="text" name="content" placeholder="请输入消息名称" autocomplete="off" class="layui-input"
                   style="width: 300px;">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <!--    <xblock>-->
    <!--        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>-->
    <!--        <button class="layui-btn" onclick="x_admin_show('发布消息','./admin-add.html')"><i class="layui-icon"></i>发布消息-->
    <!--        </button>-->
    <!--        <span class="x-right" style="line-height:40px">共有数据：--><? //= $count ?><!-- 条</span>-->
    <!--    </xblock>-->
    <table class="layui-table">
        <thead>
        <tr>
            <!--            <th>-->
            <!--                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i-->
            <!--                            class="layui-icon">&#xe605;</i></div>-->
            <!--            </th>-->
            <th>序号</th>
            <th>内容</th>
            <th>分类</th>
            <th>置顶</th>
            <th>发布时间</th>
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
                    <td><?= $key + $index ?></td>
                    <td style="width: 68%;"><?= $val['content'] ?></td>
                    <td><?= $val['category'] ?></td>
                    <th><?= empty($val['top']) ? '否' : '是'; ?></th>
                    <td><?= $val['created'] ?></td>
                    <td class="td-manage">
                        <a title="编辑" onclick="x_admin_show('编辑','/info/edit?id=<?= $val['id'] ?>')"
                           href="javascript:;">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" onclick="member_del(this,<?= $val['id'] ?>)" href="javascript:;">
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
        <!--        <div>-->
        <!--            <a class="prev" href="">&lt;&lt;</a>-->
        <!--            <a class="num" href="">1</a>-->
        <!--            <span class="current">2</span>-->
        <!--            <a class="num" href="">3</a>-->
        <!--            <a class="num" href="">489</a>-->
        <!--            <a class="next" href="">&gt;&gt;</a>-->
        <!--        </div>-->
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

    /*用户-停用*/
    function member_stop(obj, id) {
        layer.confirm('确认要停用吗？', function (index) {

            if ($(obj).attr('title') == '启用') {

                //发异步把用户状态进行更改
                $(obj).attr('title', '停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!', {icon: 5, time: 1000});

            } else {
                $(obj).attr('title', '启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!', {icon: 5, time: 1000});
            }

        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $(obj).parents("tr").remove();

            var params = {};
            params.id = id;
            $.ajax({
                url: "/info/del",
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

    layui.use('laydate', function () {
        var laydate = layui.laydate;
        //日期范围
        laydate.render({
            elem: '#dateSelect'
            , range: true
        });
    });

</script>

