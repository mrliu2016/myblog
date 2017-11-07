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
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">查询
                            </button>
                            <div class="col-md-2">
                                <input type="text" style="width: 120px" id="content" name="userId"
                                       class="form-control datepicker-pop"
                                    <?php if (!empty($params['userId'])): ?>
                                        value="<?= $params['userId'] ?>"
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
                    <th class="col-md-1">身份证</th>
                    <th class="col-md-1">金额</th>
                    <th class="col-md-1">平台交易号</th>
                    <th class="col-md-1">状态</th>
                    <th class="col-md-1">审核人</th>
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
                            <?= $item['idCard'] ?>
                        </td>
                        <td>
                            <?= $item['price'] ?>
                        </td>
                        <td>
                            <?= $item['transactionId'] ?>
                        </td>
                        <td>
                            <?= $item['status'] ?>
                        </td>
                        <td>
                            <?= $item['auditor'] ?>
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

                var client_array = data;
                var client_js = eval('(' + client_array + ')');
                // 初始化图表标签
                var myChart = echarts.init(document.getElementById('main_1'));
                options = {
                    /* title : {
                     text: '丢包率',
                     x:'center'
                     },*/
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        data: ['0~5%', '5%~10%', '10%~20%', '>20%', '成功']
                    },
                    series: [
                        {
                            name: '丢包率',
                            type: 'pie',
                            radius: '55%',
                            center: ['50%', '60%'],
                            data: [
                                {value: client_js['a'], name: '0~5%'},
                                {value: client_js['b'], name: '5%~10%'},
                                {value: client_js['c'], name: '10%~20%'},
                                {value: client_js['d'], name: '>20%'},
                                {value: client_js['e'], name: '成功'}
                            ],
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true,
                                        formatter: '{b} : {c} ({d}%)'
                                    },
                                    labelLine: {show: true}
                                }
                            }
                        }
                    ]
                };
                myChart.setOption(options);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }
</script>
