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
    .layui-add-btn {
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
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">信息管理</a>
        <a>
          <cite>发布信息</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>

<div class="x-body">
    <div class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>内容
            </label>
            <div class="layui-input-inline">
                <textarea rows="9" cols="100" id="content"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>分类
            </label>
            <div class="layui-input-inline">
                <select class="categoryId">
                    <?php if (!empty($categoryList)): ?>
                        <?php foreach ($categoryList as $key => $val): ?>
                            <option value="<?= $key ?>"><?= $val ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="0">其他</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>置顶</label>
            <div class="layui-input-block">
                <input type="radio" name="top" value="1" title="是">
                <input type="radio" name="top" value="0" title="否" checked>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>时间
            </label>
            <div class="layui-input-inline">
                <input type="number" placeholder="置顶时间(天)" id="time" class="layui-input" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>权重
            </label>
            <div class="layui-input-inline">
                <input type="number" placeholder="默认权重为0,范围0-100" value="0" id="weight" class="layui-input"
                       autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-add-btn" lay-filter="add" lay-submit="" onclick="info_release()">
                发布信息
            </button>
        </div>
    </div>
</div>
<script>
    /*信息发布*/
    function info_release() {
        layer.confirm('确认要发布吗？', function (index) {
            var top = $('input:radio:checked').val();

            var params = {};
            params.content = $("#content").val();//内容
            params.categoryId = $(".categoryId").val();//分类
            params.top = top;//置顶
            params.time = $("#time").val();//置顶时间
            params.weight = $("#weight").val();//权重

            $.ajax({
                url: "/info/release",
                type: "post",
                cache: false,
                data: params,
                dataType: "json",
                success: function (response) {
                    switch (parseInt(response.code)) {
                        case 0:
                            layer.msg('发布成功!', {icon: 1, time: 1000});
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

    //权重
    $(document).on("input propertychange", "#weight", function () {
        var limitNum = $(this).val().replace(/[^0-9.]+/g, "");
        if (limitNum < 1 && limitNum.length > 1) {
            $(this).val(0);
            return;
        }
        if (limitNum >= 0 && limitNum <= 100) {
            $(this).val(parseInt(limitNum));
        } else {
            $(this).val("");
        }
    });

    //置顶时间
    $(document).on("input propertychange", "#time", function () {
        var limitNum = $(this).val().replace(/[^0-9.]+/g, "");
        if (limitNum < 1 && limitNum.length > 1) {
            $(this).val(0);
            return;
        }
        if (limitNum >= 0) {
            $(this).val(parseInt(limitNum));
        } else {
            $(this).val("");
        }
    });

</script>
<script>var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();</script>
