<?php
$this->context->layout = false;
?>
<!DOCTYPE html>
<html>
<head style="height: 100%; width: 100%">
    <title>首页</title>
    <link rel="stylesheet" href="/css/index.css?aaa=1314">
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
                    <img class="live-download-item_qrCode" src="img1.png" alt="qrcode">
                    <a class="live-download-btn-wrap" href="" download>
                        <button class="live-download-item_btn">
                            iOS
                        </button>
                    </a>
                    <a class="live-download-item_a" href="https://abc" download>使用手册下载&gt;</a>
                </li>
                <li class="live-download-item">
                    <div class="live-download-item_icon icon-android"></div>
                    <img class="live-download-item_qrCode" src="img1.png" alt="qrcode">
                    <a class="live-download-btn-wrap" href="" download>
                        <button class="live-download-item_btn">
                            Android
                        </button>
                    </a>
                    <a class="live-download-item_a" href="https://abc" download>使用手册下载&gt;</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="live-login-right">
        <div class="live-login-content">
            <button class="live-login-inBtn" onclick="Jump()">点击进入</button>
            <div class="live-contact">
                <p class="live-contact-title">联系我们！</p>
                <p class="live-contact-item">北京：18516842770</p>
                <p class="live-contact-item">商务邮箱：sales@3ttech.cn</p>
                <p class="live-contact-item">广州：15989031044</p>
                <p class="live-contact-item">客服邮箱：support@3ttech.cn</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    function Jump() {
        window.location.href="/user/index";
    }
</script>
