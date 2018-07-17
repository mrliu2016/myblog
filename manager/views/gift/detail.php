<?php
$this->title = '礼物管理';
?>
<div class="s-gift-manage">
    <div class="s-userinfo_title">礼物详情</div>
    <a class="s-accuse_back" href="/gift/index">返回</a>
    <div class="s-userinfo_basic">
        <img class="s-userinfo_headimg" src="<?= $item['imgSrc'] ?>" alt="用户头像">
        <div class="s-basic">
            <p class="s-basic_item">
                <span class="s-basic_item-title">ID：</span>
                <span class="s-basic_item-value"><?= $item['id'] ?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">礼物名称：</span>
                <span class="s-basic_item-value"><?= $item['name'] ?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">价格：</span>
                <span class="s-basic_item-value"><?= $item['price'] ?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">是否连发：</span>
                <span class="s-basic_item-value s-basic--certified"><?= isset($item['isFire']) && $item['isFire'] == 1 ? '是' : '否' ?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">创建时间：</span>
                <span class="s-basic_item-value"><?= date('Y-m-d H:i', $item['created']) ?></span>
            </p>
        </div>
    </div>
</div>


