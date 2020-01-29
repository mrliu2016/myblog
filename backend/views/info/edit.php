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
    .layui-edit-btn {
        display: inline-block;
        height: 38px;
        line-height: 38px;
        padding: 0 18px;
        background-color: #009688;
        color: #fff;
        white-space: nowrap;
        text-align: center;
        font-size: 14px;
        border: none;
        border-radius: 2px;
        cursor: pointer;
    }
</style>
<div class="x-body">
    <div class="layui-form">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>内容
            </label>
            <div class="layui-input-inline">
                <textarea rows="11" cols="60" id="content"><?= $info['content'] ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_email" class="layui-form-label">
                <span class="x-red">*</span>分类
            </label>
            <div class="layui-input-inline">
                <select class="categoryId">
                    <?php if (!empty($categoryList)): ?>
                        <?php foreach ($categoryList as $key => $val): ?>
                            <?php if ($key == $info['categoryId']): ?>
                                <option value="<?= $key ?>" selected><?= $val ?></option>
                            <?php else: ?>
                                <option value="<?= $key ?>"><?= $val ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option>其他</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button class="layui-edit-btn" lay-filter="add" lay-submit="" onclick="info_edit()">
                编辑
            </button>
        </div>
    </div>
</div>
<script>
    /*分类--编辑*/
    function info_edit() {
        layer.confirm('确认要编辑吗？', function (index) {
            var params = {};
            params.id = <?=$params['id']?>;
            params.content = $("#content").val();
            $.ajax({
                url: "/info/edit",
                type: "post",
                cache: false,
                data: params,
                dataType: "json",
                success: function (response) {
                    switch (parseInt(response.code)) {
                        case 0:
                            layer.msg('编辑成功!', {icon: 1, time: 1000});
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                            parent.window.location.href = parent.window.location.href;
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
</script>
<script>var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();</script>
