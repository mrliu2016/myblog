<?php
$this->context->layout = false;
?>
<!DOCTYPE html>
<html>
<head style="height: 100%; width: 100%">
    <title>首页</title>
    <link rel="stylesheet" href="/css/index.css?aaa=1315">
    <script src="/vendor/jquery/dist/jquery.js?a=1314"></script>
    <link rel="stylesheet" href="/css/style.css??bbb=999">
    <link rel="stylesheet" href="/css/list.css?aaa=1314">
    <link rel="stylesheet" href="/css/modal.css?aaa=1314">
</head>
<body style="margin: 0; padding: 0; height: 100%; width: 100%">
<div class="live-login">
    <div class="live-login-left">
        <?php if($_SERVER['HTTP_HOST']=='3tlive.3ttech.cn'):?>
            <img class="live-logo" src="/img/live/logo.png" alt="Logo" >
        <?php else: ?>
            <img class="live-logo" src="/img/live/zhibobanlogo2.png" alt="Logo" >
        <?php endif;?>
        <div class="live-login-left-content">
            <h1 class="live-login-title">直播后台</h1>
            <h3 class="live-download-title">下载专区</h3>
            <ul class="live-download-list">
                <li class="live-download-item">
                    <div class="live-download-item_icon icon-ios"></div>
                    <?php if($_SERVER['HTTP_HOST']=='3tlive.3ttech.cn'):?>
                        <img class="live-download-item_qrCode" src="/img/live/qrcode-ios.png" alt="qrcode">
                    <?php else: ?>
                        <img class="live-download-item_qrCode" src="/img/live/pure-ios.png" alt="qrcode">
                    <?php endif;?>
                    <a class="live-download-btn-wrap" href="#">
                        <button class="live-download-item_btn">
                            iOS
                        </button>
                    </a>
                   <!-- <a class="live-download-item_a" href="https://abc" download>使用手册下载&gt;</a>-->
                    <a class="live-download-item_a" href="http://3ttech.cn/res/tpl/default/file/CMZBYD.pdf" download>使用手册下载&gt;</a>
                </li>
                <li class="live-download-item">
                    <div class="live-download-item_icon icon-android"></div>
                    <?php if($_SERVER['HTTP_HOST']=='3tlive.3ttech.cn'):?>
                        <img class="live-download-item_qrCode" src="/img/live/qrcode-andriod.png" alt="qrcode">
                    <?php else: ?>
                        <img class="live-download-item_qrCode" src="/img/live/pure-andriod.png" alt="qrcode">
                    <?php endif;?>
                    <a class="live-download-btn-wrap" href="#">
                        <button class="live-download-item_btn">
                            Android
                        </button>
                    </a>
                    <!--<a class="live-download-item_a" href="https://abc" download>使用手册下载&gt;</a>-->
                    <a class="live-download-item_a" href="http://3ttech.cn/res/tpl/default/file/CMZBYD.pdf" download>使用手册下载&gt;</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="live-login-right">
        <div class="live-login-content">
            <div class="live-login-form">
                <div class="live-login-form-title">登录</div>
                <input type="text" class="live-login-input" id="username" placeholder="帐号">
                <input type="password" class="live-login-input" id="password" placeholder="密码">
                <input type="submit" value="登录" class="live-login-subBtn" />
            </div>

            <?php if($_SERVER['HTTP_HOST']=='3tlive.3ttech.cn'):?>
                <div class="live-contact">
                    <p class="live-contact-title">联系我们！</p>
                    <p class="live-contact-item">北京：18516842770</p>
                    <p class="live-contact-item">广州：15989031044</p>
                    <p class="live-contact-item">商务邮箱：sales@3ttech.cn</p>
                    <p class="live-contact-item">客服邮箱：support@3ttech.cn</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<!--提示框Start-->
<div id="tip_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text"></span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm" id="tip-confirm">确认</button>
            </div>
        </div>
    </div>
</div>
<!--提示框end-->
</body>
</html>
<script type="text/javascript">
    function Jump() {
        window.location.href="/user/index";
    }
    //登录
    $(".live-login-subBtn").unbind("click").bind("click",function () {

        var params = {};
        var username = $("#username").val();
        var password = $("#password").val();
        if(username == '' || username == undefined || username == null){
            tip("请输入用户名！");
            return;
        }
        if(password == '' || password == undefined || password == null){
            tip("请输入用户密码！");
            return;
        }
        params.username = username;
        params.password = password;
        params.redirect = '<?=$redirect?>'
        $.ajax({
            url:'/index/check',
            type:'post',
            data:params,
            dataType:'json',
            success:function (data) {
                if(data.code == 0){
                    window.location.href=data.data.redirect;
                }
                else{
                    tip("用户名或密码错误！");
                }
            }
        });
    });
    
    function tip(message) {
        $("#tip_frame").css("display","block");
        $(".s-banlive-confirm-text").text(message);
        $("#tip-confirm").unbind("click").bind("click",function () {
            $("#tip_frame").css("display","none");
        });
    }
</script>
