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
            <label class="layui-form-label">用户名：</label>
            <label class="layui-form-label-col"><?= $admin['username'] ?></label>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称：</label>
            <label class="layui-form-label-col"><?= $admin['nickname'] ?></label>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码：</label>
            <label class="layui-form-label-col"><?= $admin['mobile'] ?></label>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">微信：</label>
            <label class="layui-form-label-col"><?= $admin['wx'] ?></label>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱：</label>
            <label class="layui-form-label-col"><?= $admin['email'] ?></label>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态：</label>
            <div class="layui-input-block">
                <?php if (empty($admin['status'])): ?>
                    <input type="radio" name="top" value="0" title="停用" checked>
                <?php else: ?>
                    <input type="radio" name="top" value="1" title="启用" checked>
                <?php endif; ?>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">创建时间：</label>
            <label class="layui-form-label-col"><?= date('Y-m-d H:i:s', $admin['created']) ?></label>
        </div>
    </div>
</div>
<script>var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
