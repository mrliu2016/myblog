<?php
use yii\widgets\LinkPager;
$this->title = '直播记录';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <!--<p class="s-gift-search-title">礼物管理</p>-->
            <form method="get" action="/live/live-record" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId">
                    </div>
                    <div class="s-gift-search-item">
                        <input type="text" style="width: 120px" id="startTime" name="startTime"
                        class="form-control datepicker-pop">
                    </div>
                    <div class="s-gift-search-item">
                        <input type="text" style="width: 120px" id="endTime" name="endTime"
                               class="form-control datepicker-pop">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn"  id="searchBtn">查询</button>
                </div>
            </form>
        </div>

        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>房间号</th>
                <th>主播昵称</th>
                <th>观众数</th>
                <th>开始时间</th>
                <th>结束时间</th>
            </tr>
            </tr>
            </thead>
            <tbody class="c-table-tbody s-gift-tbody">
                <?php foreach ($itemList as $key=>$item): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <?= $item['roomId'] ?>
                        </td>
                        <td>
                            <?= $item['nickName'] ?>
                        </td>
                        <td>
                            <?= $item['viewerNum']?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['created']) ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['updated']) ?>
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

<script>
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
</script>
