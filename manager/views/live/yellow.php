<?php
$this->title = '鉴黄管理';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <p class="s-gift-search-title s-page-title">鉴黄管理</p>
            <form method="get" action="/live/yellow" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>开始时间</span>
                        <input type="text" id="startTime" name="startTime"
                                class="c-input s-gift-search-input form-control datepicker-pop" style="width: 100px;" autocomplete="off" >
                        —
                        <input type="text" id="endTime" name="endTime"
                               class="c-input s-gift-search-input form-control datepicker-pop" style="width: 100px;" autocomplete="off">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>
                </div>
            </form>
        </div>

        <div class="s-gift-table-wrap" style="margin-top:40px;">
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>昵称</th>
                <th>房间号</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>截图</th>
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
                        <?= $item['nickName'] ?>
                    </td>
                    <td>
                        <?= $item['roomId'] ?>
                    </td>
                    <td>
                        <?= date('Y-m-d H:i',$item['startTime'])?>
                    </td>
                    <td>
                        <?= date('Y-m-d H:i',$item['endTime'])?>
                    </td>
                    <td>
                        <a href="/live/yellow-check?id=<?=$item['id']?>" class="s-page-font-color">查看</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div>
            <p class="s-gift-count" style="padding-top: 10px;">共 <span class="s-page-font-color"><?= $count ?></span> 条记录</p>
            <nav class="text-center" style="margin-left:30%">
                <table>
                    <tr>
                        <td class="page-space"> <?= $page ?></td>
                        <td>共<?= $count ?> 条</td>
                    </tr>
                </table>
            </nav>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    $("#cleanBtn").click(function () {
        $(this).closest('form').find("input[type=text]").val("")
    });
    $(".datepicker-pop").datetimepicker({
        todayHighlight: true,
        todayBtn: true,
        autoclose: true,
        minView: 3,
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });

    function detail(serverName, begin, end) {
        $.ajax({
            url: "/report/lost-detail?serverName=" + serverName + '&beginTime=' + begin + '&endTime=' + end,
            type: "get",
            cache: false,
            dataType: "text",
            success: function (data) {
                html = '<div id="main_1" style="width:1200px;height:450px;float: left"></div>'
                layer.open({
                    title: serverName,
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['1000px', '550px'], //宽高
                    content: html
                });
            }
        });
    }
    
    function show(url) {
        alert(url);
    }
</script>
