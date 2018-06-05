<?php
use yii\widgets\LinkPager;

$this->title = '消息管理';
?>

<div class="container-fluid">

    <div class="card">
        <textarea rows="10" cols="50">

        </textarea>
        <br/>
        <input type="checkbox">全部用户<span></span>
        <a href="" id="selectUser">选择用户</a>
    </div>

</div>


<script type="text/javascript">

    $("#selectUser").click(function () {
        
    });

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
