
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin Template">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>文联直播列表</title>

</head>
<body class="theme-1">
<script src="/vendor/jquery/dist/jquery.js"></script>
<script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.2.1.js" charset="utf-8"></script>;
<div class="layout-container">
    <div class="sidebar-layout-obfuscator"></div>
    <main class="main-container">
        <section>
            <div class="loading hide">加载中。。。</div>
            <?= $content ?>
        </section>
    </main>
</div>
</body>
</html>