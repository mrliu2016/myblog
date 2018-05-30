<?php
$this->title = '礼物详情';
?>

<style>
    .gift-detail{
        padding-left: 20px;
        padding-top: 20px;
    }
</style>

<div class="gift-detail">
    <div>
        礼物图片:<br/>
        <img src="<?=$list['imgSrc']?>">
    </div>
    <table class="table table-hover">
        <tbody>
            <tr>
                <td>
                    ID
                </td>
                <td>
                    <?= $list['id'] ?>
                </td>
            </tr>
            <tr>
                <td>礼物名称</td>
                <td><?= $list['name'] ?></td>
            </tr>
            <tr>
                <td>价格</td>
                <td><?= $list['price'].'豆' ?></td>
            </tr>
            <tr>
                <td>是否可以连发</td>
                <td><?= (isset($list['isFire'])&&$list['isFire']==1)?'是':'否' ?></td>
            </tr>
            <tr>
                <td>创建时间</td>
                <td><?= date('Y-m-d H:i:s',$list['created'])?></td>
            </tr>
        </tbody>
    </table>
</div>



