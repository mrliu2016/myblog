<?php
use yii\widgets\LinkPager;
$this->title = '举报管理';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <!--<p class="s-gift-search-title">礼物管理</p>-->
            <form method="get" action="/report/index" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>被举报人ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>被举报人昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName" autocomplete="off">
                    </div>

                    <div class="s-gift-search-item">
                        <span>注册时间</span>
                        <input class="c-input s-gift-search-input form-control datepicker-pop" type="text" id="startTime" name="startTime" autocomplete="off" style="width: 100px;">
                        —
                        <input type="text" id="endTime" name="endTime"
                               class="c-input s-gift-search-input form-control datepicker-pop" style="width: 100px;" autocomplete="off">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>

                </div>
            </form>
        </div>
        <div class="s-gitf-operate">
            <!--<button class="c-btn u-radius--circle c-btn-primary">新增</button>-->
            <a class="c-a s-gift-setting" href="/report/set-type">设置举报类型</a>
        </div>
        <div class="s-gift-table-wrap">
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>被举报人ID</th>
                <th>被举报人昵称</th>
                <th>举报人ID</th>
                <th>举报人昵称</th>
                <th>举报类型</th>
                <th>举报时间</th>
            </tr>
            </thead>
            <tbody class="c-table-tbody s-gift-tbody">
            <?php foreach ($itemList as $key => $item): ?>
                <tr>
                    <td>
                        <?= $key+1 ?>
                    </td>
                    <td>
                        <?= $item['reportedUserId'] ?>
                    </td>
                    <td>
                        <?= $item['reportName'] ?>
                    </td>
                    <td>
                        <?= $item['userId'] ?>
                    </td>
                    <td>
                        <?= $item['nickName'] ?>
                    </td>
                    <td>
                        <?= $item['content'] ?>
                    </td>
                    <td>
                        <?= date('Y-m-d H:i:s',$item['created']) ?>
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

    $(".datepicker-pop").datetimepicker({
        todayHighlight: true,
        todayBtn: true,
        autoclose: true,
        minView: 3,
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });

</script>
