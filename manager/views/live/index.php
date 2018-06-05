<?php
use yii\widgets\LinkPager;
$this->title = '直播管理';
?>

<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <form method="get" action="/live/index" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" placeholder="用户ID">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName" placeholder="昵称">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId" placeholder="房间号">
                    </div>
                    <div class="s-gift-search-item">
                        <input type="text" style="width: 120px" id="startTime" name="startTime"
                               class="form-control datepicker-pop">
                    </div>
                    <div class="s-gift-search-item">
                        <input type="text" style="width: 120px" id="startTime" name="startTime"
                               class="form-control datepicker-pop">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>
                </div>
            </form>
        </div>
       <!-- <div class="s-gitf-operate">
            <button class="c-btn u-radius--circle c-btn-primary">新增</button>
            <a class="c-a s-gift-setting">设置连击</a>
        </div>-->
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>房间号</th>
                <th>主播昵称</th>
                <th>观众数</th>
                <th>开始时间</th>
                <th>状态</th>
                <th>操作</th>
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
                        <?= date('Y-m-d H:i', $item['created']) ?> <br />
                    </td>
                    <td>
                        <?php if($item['isLive'] == 1){
                            echo "直播中";
                        } else if($item['isLive'] == 0){
                            echo "结束";
                        } ?>
                    </td>
                    <td>
                        <a href="#" onclick="check(<?=$item['id']?>)">查看</a>
                        <a href="#" onclick="forbid(<?=$item['id']?>)">禁播</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="s-gift-count">共 <?= $count ?> 条记录</p>
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
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }

    //查看
    function check(id) {
        var params = {};
        params.id = id;
        $.ajax({
            url: "/live/check",
            type: "post",
            data: params,
            // cache: false,
            dataType: "json",
            success: function (data) {

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }

    //禁播
    function forbid(id) {
        var params = {};
        params.id = id;
        $.ajax({
            url: "/live/forbid",
            type: "post",
            data: params,
            // cache: false,
            dataType: "json",
            success: function (data) {

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }
    
</script>
