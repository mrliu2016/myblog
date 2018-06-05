<?php
$this->title = '机器人管理';
?>
<div class="s-userinfo">
    <div class="s-userinfo_title">机器人详情</div>
    <div class="s-userinfo_basic">
        <img class="s-userinfo_headimg" src="<?=$item['avatar']?>" alt="用户头像">
        <div class="s-basic">
            <p class="s-basic_item">
                <span class="s-basic_item-title">ID：</span>
                <span class="s-basic_item-value"><?=$item['userId']?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">昵称：</span>
                <span class="s-basic_item-value"><?=$item['nickName']?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">房间号：</span>
                <span class="s-basic_item-value"><?=$item['roomId']?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">性别：</span>
                <span class="s-basic_item-value"><?=$item['sex']==1?'男':'女'?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">所在地：</span>
                <span class="s-basic_item-value"><?=$item['city']?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">注册时间：</span>
                <span class="s-basic_item-value"><?=date('Y-m-d H:i',$item['created'])?></span>
            </p>
        </div>
    </div>
    <div class="s-userinfo-details">
        <p class="s-details_item">
            <span class="s-details_item-title">个人签名：</span>
            <span class="s-details_item-value"><?=$item['description']?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">关注数：</span>
            <span class="s-details_item-value"><?=intval($item['followees_cnt'])?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">粉丝数：</span>
            <span class="s-details_item-value"><?=intval($item['followers_cnt'])?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">收到礼物：</span>
            <span class="s-details_item-value"><?=$item['receiveValue']?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">送出礼物：</span>
            <span class="s-details_item-value"><?=$item['sendValue']?></span>
        </p>
    </div>
</div>