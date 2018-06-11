<?php
use yii\widgets\LinkPager;
$this->title = '礼物管理';
?>

<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <!--<p class="s-gift-search-title">礼物管理</p>-->
            <form method="get" action="/gift/template" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id">
                    </div>
                    <div class="s-gift-search-item">
                        <span>礼物名称</span>
                        <input class="c-input s-gift-search-input" type="text" name="name">
                    </div>
                    <div class="s-gift-search-item">
                        <span>是否连发</span>
                        <span class="select-wrap">
                        <select class="c-input s-gift-search-select" name="isFire" id="borsts" default="0">
                            <option value="">全部</option>
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                      </span>
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>
                </div>
            </form>
        </div>
        <div class="s-gitf-operate">
            <!--<button class="c-btn u-radius--circle c-btn-primary" >新增</button>-->
            <a class="c-btn u-radius--circle c-btn-primary" href="/gift/create">新增</a>

            <a class="c-a s-gift-setting" href="/gift/setting">设置连击</a>
        </div>
        <div class="s-gift-table-wrap">
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>礼物名称</th>
                <th>加个/豆</th>
                <th>是否可以连发</th>
                <th>注册时间</th>
            </tr>
            </thead>
            <tbody class="c-table-tbody s-gift-tbody">
            <?php foreach ($itemList as $key => $item): ?>
                <tr>
                    <td>
                        <?= $key+1 ?>
                    </td>
                    <td>
                        <?= $item['id'] ?>
                    </td>
                    <td>
                        <a href="/gift/detail?id=<?=$item['id']?>"><?= $item['name'] ?></a>
                    </td>
                    <td>
                        <?= $item['price'] ?>
                    </td>
                    <td>
                        <?= (!empty($item['isFire']) && $item['isFire'] == 1)?'是':'否'?>
                    </td>
                    <td>
                        <a href="/gift/gift-edit?id=<?= $item['id'] ?>">编辑</a>
                        <a href="/gift/gift-delete?id=<?= $item['id'] ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p class="s-gift-count">共 <?= $count ?> 条记录</p>
    </div>
    <nav class="text-center" style="margin-left:30%">
        <table>
            <tr>
                <td class="page-space"> <?= $page ?></td>
                <td>共<?= $count ?> 条</td>
            </tr>
        </table>
    </nav>

</div>

<script type="text/javascript">

    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
</script>
