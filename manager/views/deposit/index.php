<?php
use yii\widgets\LinkPager;

$this->title = '提现审核';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/deposit/index" class="form-horizontal" id="searchForm"
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
                    <th class="col-md-1">id</th>
                    <th class="col-md-1">用户id</th>
                    <th class="col-md-1">充值金额</th>
                    <th class="col-md-1">佣金</th>
                    <th class="col-md-1">订单号</th>
                    <th class="col-md-1">第三方交易号</th>
                    <th class="col-md-1">渠道</th>
                    <th class="col-md-1">订单状态</th>
                    <th class="col-md-1">下单时间</th>
                    <th class="col-md-1">完成时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $item): ?>
                    <tr>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <?= $item['userId'] ?>
                        </td>
                        <td>
                            <?= $item['price'] ?>
                        </td>
                        <td>
                            <?= $item['commission'] ?>
                        </td>
                        <td>
                            <?= $item['orderIdAlias'] ?>
                        </td>
                        <td>
                            <?= $item['transactionId'] ?>
                        </td>
                        <td>
                            <?= $item['source'] ?>
                        </td>
                        <td>
                            <?= $item['status'] ?>
                        </td>
                        <td>
                            <?= $item['orderCreateTime'] ?>
                        </td>
                        <td>
                            <?= $item['orderPayTime'] ?>
                        </td>
                        <td>
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
</script>
