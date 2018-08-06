<?php
$this->title = '直播管理';
?>
<div class="s-gift-manage">
    <div class="s-userinfo_title">图片详情</div>
    <a class="s-accuse_back" href="/live/live-img">返回</a>
    <div class="s-userinfo_basic">
        <img class="s-userinfo_headimg" src="<?= $item['imgSrc'] ?>" alt="直播图片">
        <div class="s-basic">
            <p class="s-basic_item">
                <span class="s-basic_item-title">ID：</span>
                <span class="s-basic_item-value"><?= $item['id'] ?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">图片名称：</span>
                <span class="s-basic_item-value"><?= $item['name'] ?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">创建时间：</span>
                <span class="s-basic_item-value"><?= date('Y-m-d H:i', $item['created']) ?></span>
            </p>
        </div>
    </div>
</div>


