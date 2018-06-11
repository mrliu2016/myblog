<?php
use yii\widgets\LinkPager;
$this->title = '鉴黄管理';
?>

<style>
    span{
        padding-left: 10px;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <img src="<?=$yellowurl?>"/>
            <span>标签：<?=$information['Label']?></span><span>场景：<?=$information['Scene']?></span>
        </div>
    </div>
</div>

