<?php
use yii\widgets\LinkPager;
$this->title = '鉴黄管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/live/yellow" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-2" style="display: flex;">
                                <div class="query" style="white-space: nowrap;">
                                    ID <input type="text" style="width: 120px;display: inline-block" id="content" name="id" placeholder="请输入用户ID"
                                              class="form-control">
                                </div>
                                <div class="query" style="white-space: nowrap;">
                                    昵称<input type="text" style="width: 120px;display: inline-block;" id="nickName" name="nickName" placeholder="昵称"
                                             class="form-control">
                                </div>
                                <div class="query" style="white-space: nowrap;">
                                    房间号<input type="text" style="width: 120px;display: inline-block" id="roomId" name="roomId" placeholder="房间号"
                                              class="form-control">
                                </div>
                                <input type="text" style="width: 120px" id="startTime" name="startTime"
                                       class="form-control datepicker-pop">

                                <!--直播时间-->
                                <input type="text" style="width: 120px" id="endTime" name="endTime"
                                       class="form-control datepicker-pop">

                                <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                        name="searchBtn">查询
                                </button>
                            </div>
                        </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-md-1">序号</th>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-1">昵称</th>
                    <th class="col-md-1">房间号</th>
                    <th class="col-md-1">开始时间</th>
                    <th class="col-md-1">结束时间</th>
                    <th class="col-md-1">截图</th>
                </tr>
                </thead>
                <tbody>
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
                            <?= date('Y-m-d H:i:s',$item['startTime'])?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i:s',$item['endTime'])?>
                        </td>
                        <td>
                           <!-- <img src="<?/*= $item['yellowurl'] */?>" width="150" height="85"></a>-->
                            <a href="/live/yellow-check?id=<?=$item['id']?>">查看</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }
    
    function show(url) {
        alert(url);
    }
</script>
