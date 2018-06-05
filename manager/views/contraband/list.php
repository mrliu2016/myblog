<?php
use yii\widgets\LinkPager;
$this->title = '违禁词管理';
?>

<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
           <!-- <p class="s-gift-search-title">礼物管理</p>-->
            <form method="get" action="/contraband/list" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>

                </div>
            </form>
        </div>
        <!--<div class="s-gitf-operate">
            <button class="c-btn u-radius--circle c-btn-primary">新增</button>
            <a class="c-a s-gift-setting">设置连击</a>
        </div>-->
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>违禁词</th>
                <th>更新时间</th>
                <th>操作</th>
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
                        <?= $item['word'] ?>
                    </td>
                    <td>
                        <?= date('Y-m-d H:i:s',$item['updated']) ?>
                    </td>
                    <td>
                        <a href="/contraband/edit-word?id=<?= $item['id'] ?>">编辑</a>
                        <a href="/contraband/delete-word?id=<?= $item['id'] ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="s-gift-count">共 125 条记录</p>

    </div>
    <nav class="text-center">
        <table>
            <tr>
                <td> <?= LinkPager::widget(['pagination' => $pagination]) ?></td>
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
