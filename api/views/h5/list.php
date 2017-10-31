<?php
$this->title = '排行榜';
?>
<style>
    .weui-footer_fixed-bottom {
        bottom: 0;
    }
    .weui-tabbar p {
        margin-bottom: 5px;
        margin-top: 5px;
    }
    .weui-cells{
        margin-bottom: 50px;
    }
</style>
<div class="container">
    <div class="weui-cells">
        <h5 style="text-align: center">今日TOP10收入排名</h5>
        <?php foreach ($itemList as $key => $value) { ?>
            <div class="weui-cell">
                <div class="weui-cell__hd cell-list">
                    <p style="width: 30px;"><?= $key + 1 ?></p>
                </div>
                <div class="weui-cell__hd "><img src="<?= $value['avatar'] ?>" class="thumb"></div>
                <div class="weui-cell__bd cell-list-center">获得奖金<?= $value['priceReal'] ?>元</div>
            </div>
        <?php } ?>
    </div>
    <div class="weui-tabbar weui-footer_fixed-bottom">
        <a href="/h5/index" class="weui-tabbar__item ">

            <p class="weui-tabbar__label">首页</p>
        </a>

        <!--<a href="/user/info?state=--flag__2" class="weui-tabbar__item">

            <p class="weui-tabbar__label">任务</p>
        </a>-->
        <a href="/h5/price-real-top" class="weui-tabbar__item weui-bar__item--on">

            <p class="weui-tabbar__label">排行榜</p>
        </a>
        <a href="/user/info?state=--flag__1" class="weui-tabbar__item">

            <p class="weui-tabbar__label">我的</p>
        </a>
    </div>
</div>
