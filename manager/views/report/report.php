<?php
use yii\widgets\LinkPager;
$this->title = '举报管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/report/report" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-2" style="display: flex;">
                                <div class="query" style="white-space: nowrap;">
                                    被举报人ID <input type="text" style="width: 120px;display: inline-block" id="content" name="id" placeholder="请输入用户ID"
                                              class="form-control">
                                </div>
                                <div class="query" style="white-space: nowrap;">
                                    被举报人昵称<input type="text" style="width: 120px;display: inline-block;" id="nickName" name="nickName" placeholder="昵称"
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
                    <th class="col-md-1">被举报人ID</th>
                    <th class="col-md-1">被举报人昵称</th>
                    <th class="col-md-1">举报人ID</th>
                    <th class="col-md-1">举报人昵称</th>
                    <th class="col-md-1">举报类型</th>
                    <th class="col-md-1">举报时间</th>
                </tr>
                </thead>
                <tbody>
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

</script>
