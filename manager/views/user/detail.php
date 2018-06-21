<?php
$this->title = '用户管理';
?>

<div class="s-gift-manage">
    <div class="s-userinfo_title">用户详情</div>
    <a class="s-gift-manage_back" href="/user/index">返回</a>
    <div class="s-userinfo_basic">
        <?php if(!empty($item['avatar'])):?>
            <img class="s-userinfo_headimg" src="<?=$item['avatar']?>" alt="用户头像">
        <?php else:?>
            <img class="s-userinfo_headimg" src="http://userservice.oss-cn-beijing.aliyuncs.com/gift/2018/06/20/14/3410_3765.png" alt="用户头像">
        <?php endif;?>

        <div class="s-basic">
            <p class="s-basic_item">
                <span class="s-basic_item-title">昵称：</span>
                <span class="s-basic_item-value"><?=$item['nickName']?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">性别：</span>
                <span class="s-basic_item-value"><?=isset($item['sex'])&&$item['sex']==1?'男':'女'?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">房间号：</span>
                <span class="s-basic_item-value"><?=$item['roomId']?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">是否认证：</span>
                <span class="s-basic_item-value s-basic--certified"><?=isset($item['isAuth'])&&$item['isAuth']==1?'已认证':'未认证'?></span>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">状态：</span>
                <?php if(empty($item['playType']) || $item['playType'] == 0):?>
                    <span class="s-basic_item-value">正常</span>
                <?php elseif($item['playType'] == 1 || $item['playType'] == 2):?>
                <span class="s-basic_item-value">禁播中</span>
                <?php elseif($item['playType'] == 3):?>
                    <span class="s-basic_item-value">永久禁播</span>
                <?php elseif($item['playType'] == 4):?>
                    <span class="s-basic_item-value">停用</span>
                <?php endif ?>
            </p>
            <p class="s-basic_item">
                <span class="s-basic_item-title">注册时间：</span>
                <span class="s-basic_item-value"><?=date('Y-m-d H:i',$item['created'])?></span>
            </p>
        </div>
    </div>
    <div class="s-userinfo-details">
        <p class="s-details_item">
            <span class="s-details_item-title">用户ID</span>
            <span class="s-details_item-value"><?=$item['userId']?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">姓名：</span>
            <span class="s-details_item-value"><?=$item['realName']?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">手机号：</span>
            <span class="s-details_item-value"><?=$item['mobile']?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">身份证号：</span>
            <span class="s-details_item-value"><?=$item['idCard']?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">粉丝数：</span>
            <span class="s-details_item-value"><?=intval($item['followers_cnt'])?></span>
        </p>
        <p class="s-details_item">
            <span class="s-details_item-title">账户余额：</span>
            <span class="s-details_item-value"><?=$item['balance']?></span>
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