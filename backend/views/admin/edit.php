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
            <label class="layui-form-label"><span class="x-red">*</span>用户名</label>
            <div class="layui-input-inline">
                <input type="text" name="title" required lay-verify="required" placeholder="请输入用户名" autocomplete="off"
                       id="username" class="layui-input" value="<?= $admin['username'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="title" required lay-verify="required" placeholder="请输入昵称" autocomplete="off"
                       id="nickname" class="layui-input" value="<?= $admin['nickname'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>密码</label>
            <div class="layui-input-inline">
                <input type="password" name="title" placeholder="请输入密码" autocomplete="off"
                       id="password" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-inline">
                <input type="text" name="title" placeholder="请输入手机号码" autocomplete="off"
                       id="mobile" class="layui-input" value="<?= $admin['mobile'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">微信</label>
            <div class="layui-input-inline">
                <input type="text" name="title" placeholder="请输入微信" autocomplete="off"
                       id="wx" class="layui-input" value="<?= $admin['wx'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-inline">
                <input type="text" name="title" placeholder="请输入邮箱" autocomplete="off"
                       id="email" class="layui-input" value="<?= $admin['email'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>状态</label>
            <div class="layui-input-block">
                <?php if (empty($admin['status'])): ?>
                    <input type="radio" name="top" value="1" title="启用">
                    <input type="radio" name="top" value="0" title="停用" checked>
                <?php else: ?>
                    <input type="radio" name="top" value="1" title="启用" checked>
                    <input type="radio" name="top" value="0" title="停用">
                <?php endif; ?>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button class="layui-edit-btn" lay-filter="add" lay-submit="" onclick="admin_edit()">
                编辑管理员
            </button>
        </div>
    </div>
</div>
<script>
    /*分类--编辑*/
    function admin_edit() {
        layer.confirm('确认要编辑吗？', function (index) {

            var status = $('input:radio:checked').val();
            var params = {};
            params.id = <?=$params['id']?>;
            params.username = $("#username").val();
            params.nickname = $("#nickname").val();
            params.mobile = $("#mobile").val();
            params.wx = $("#wx").val();
            params.password = $("#password").val();
            params.email = $("#email").val();
            params.status = status;

            $.ajax({
                url: "/admin/edit",
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
    })();
</script>
