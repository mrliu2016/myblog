<?php
$this->title = '任务首页';
?>
<style>

    .swiper-container {
        width: 100%;
    }

    .swiper-container img {
        display: block;
        width: 100%;
        height: auto;
    }

    .now-task {
        font-size: 1.2em;
        line-height: 3em;
    }

    p {
        margin-bottom: 0px;
    }

    .weui-tabbar p {
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .weui-footer_fixed-bottom {
        bottom: 0;
    }
    .weui-panel_access{
        margin-bottom: 40px;
    }
    .weui-tab__bd a{
        background-color: #f0f0f0;
        margin:15px auto;
    }
</style>
<div class="container">
    <div class="swiper-container" data-space-between='10' data-pagination='.swiper-pagination' data-autoplay="1000">
        <div class="swiper-wrapper">
            <?php foreach ($bannerList as $banner): ?>
                <div class="swiper-slide">
                    <a href="<?= $banner['url'] ?>">
                        <img src="<?= $banner['imgSrc'] ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-pagination swiper-pagination-bullets">
            <span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
            <span class="swiper-pagination-bullet"></span>
            <span class="swiper-pagination-bullet"></span>
        </div>
    </div>
    <div class="weui-tab tab-top">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on" href="#tab1">
                发起任务
            </a>
            <a class="weui-navbar__item" href="#tab2">
                加入任务
            </a>
        </div>
        <div class="weui-tab__bd">
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
                <div class="weui-panel__bd">
                    <?php foreach ($applicationList as $item): ?>
                        <a href="<?= $authUrl ?><?= $item['id'] ?>" class="weui-media-box weui-media-box_appmsg">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="<?= $item['imgSrc'] ?>">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title"><?= $item['name'] ?></h4>
                                <p class="weui-media-box__desc"><?= $item['desc'] ?></p>
                                <p class="weui-cell__ft">发起</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="tab2" class="weui-tab__bd-item mention-history">
                <div class="weui-panel__bd">

                    <?php foreach ($taskList as $task): ?>
                        <a href="/user/info?state=--flag__4--taskid__<?= $task['id'] ?>" class="weui-media-box weui-media-box_appmsg" data="<?= $task['id'] ?>">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="<?= $task['applicationImgSrc'] ?>">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title"><?= $task['name'] ?></h4>
                                <p class="weui-media-box__desc"><?= $task['num'] ?>人参加</p>
                                <p class="weui-media-box__desc"><?= $task['remainTime'] ?>天后平分<?= $task['sumPriceReal'] ?>元</p>
                                <p class="weui-cell__ft">加入</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="weui-tabbar weui-footer_fixed-bottom">
    <a href="/h5/index" class="weui-tabbar__item weui-bar__item--on">

        <p class="weui-tabbar__label">首页</p>
    </a>

    <!--<a href="/user/info?state=--flag__2" class="weui-tabbar__item">

        <p class="weui-tabbar__label">任务</p>
    </a>-->
    <a href="/h5/price-real-top" class="weui-tabbar__item">

        <p class="weui-tabbar__label">排行榜</p>
    </a>
    <a href="/user/info?state=--flag__1" class="weui-tabbar__item">

        <p class="weui-tabbar__label">我的</p>
    </a>
</div>
<script>

    $(".swiper-container").swiper({
        loop: true,
        autoplay: 3000
    });

</script>
