<?php
use yii\widgets\LinkPager;

$this->title = '提现审核';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/deposit/withdraw" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="userId" name="userId" placeholder="请输入用户ID"
                                    <?php if (!empty($params['userId'])): ?>
                                        value="<?= $params['userId'] ?>"
                                    <?php endif; ?>
                                >
                            </div>
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">查询
                            </button>
                            <div class="col-md-2">
                                <input type="text" style="width: 120px" id="content" name="queryTime"
                                       class="form-control datepicker-pop"
                                    <?php if (!empty($params['queryTime'])): ?>
                                        value="<?= $params['queryTime'] ?>"
                                    <?php endif; ?>>
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
                    <th class="col-md-1">用户id</th>
                    <th class="col-md-1">姓名</th>
<!--                    <th class="col-md-1">身份证</th>-->
                    <th class="col-md-1">金额</th>
                    <th class="col-md-1">平台交易号</th>
                    <th class="col-md-1">审核人</th>
                    <th class="col-md-1">错误码</th>
                    <th class="col-md-1">错误描述</th>
                    <th class="col-md-1">交易完成时间</th>
                    <th class="col-md-1">状态</th>
                    <th class="col-md-1">原因</th>
                    <th class="col-md-1">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $item): ?>
                    <tr>
                        <td>
                            <?= $item['userId'] ?>
                        </td>
                        <td>
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <?= $item['price'] ?>
                        </td>
                        <td>
                            <?= $item['transactionId'] ?>
                        </td>
                        <td>
                            <?= $item['auditor'] ?>
                        </td>
                        <td>
                            <?= $item['errorCode'] ?>
                        </td>
                        <td>
                            <?= $item['errorCodeDescription'] ?>
                        </td>
                        <td>
                            <?= $item['orderPayTime'] ?>
                        </td>
                        <td>
                            <?= $item['status'] ?>
                        </td>
                        <td>
                            <?= $item['remark'] ?>
                        </td>
                        <td>
                            <?php if ($item['status'] == '未处理'){ ?>
                                <a type="button" href="javascript:void(0);"
                                   onclick="detail('<?= $item['id'] ?>','<?= $item['userId'] ?>','<?= $item['price'] ?>')">详情</a>
                            <?php } ?>
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

    function detail(id, userId, price) {
        $.ajax({
            url: "/deposit/withdraw-detail?id="+id+"&userId=" + userId,
            type: "get",
            cache: false,
            dataType: "json",
            success: function (data) {
                var info = eval(data);
                var html = '<table class="table table-hover"><thead>' +
                    '<tr><th class="col-md-1">可提现金额(元)</th><th class="col-md-1">申请提现金额(元)</th>' +
                    '<tbody>';
                html += '<tr><td>' + info.data.balance + '</td><td>' + price + '</td>';
                html += '</tr></tbody></table>';
                html += '<div class="row form-but"><input type="text" id="remarked" class="form-control col-md-3" name="mark" placeholder="拒绝原因/通过理由可以为空">';
                html += ' <button type="button" class="mb-sm btn btn-primary ripple col-md-1" data-id="' + id + '" id="agree" data-userId="' + userId + '" name="agree"  onclick="agree(this)" >通过</button>';
                html += ' <button type="button" class="mb-sm btn btn-primary ripple col-md-1" id="refused" data-id="' + id + '" name="refused" data-userId="' + userId + '" onclick="refuse(this)">拒绝</button></div>';
                layer.open({
                    title: '提现详情',
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['1250px', '450px'], //宽高
                    content: html
                });
            }

        })
    }

    function agree(that) {
        var that = that;
        var userId = $(that).data("userId");
        var id = $(that).data("id");
        var data = {
            id: id,
            userId: userId
        };
        $.ajax({
            url: "/deposit/agree",
            type: "post",
            cache: false,
            dataType: "json",
            data: data,
            success: function (data) {
                window.location.reload();
            }
        })
    }

    function refuse(that) {
        var that = that;
        var userId = $(that).data("userId");
        var id = $(that).data("id");
        var remark = $(that).parent().children("input[name='mark']").val();
        var data = {
            id: id,
            userId: userId,
            remark: 'remark'
        };
        // 请求参数：id，userId，remark
        if (remark == '') {
            layer.msg("拒绝原因不能为空")
        } else {
            $.ajax({
                url: "/deposit/refuse",
                type: "post",
                cache: false,
                dataType: "json",
                data: data,
                success: function (msg) {
                    window.location.reload();
                }
            })
        }

    }
</script>
