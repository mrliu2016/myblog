<link rel="stylesheet" href="/css/font.css">
<link rel="stylesheet" href="/css/xadmin.css">
<script language="javascript" type="text/javascript" src="/vendor/echart/echarts.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<div class="x-body">
    <blockquote class="layui-elem-quote">欢迎管理员：
        <span class="x-red"><?=$username?></span>！当前时间：<span id="currentTime"></span>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend>数据统计</legend>
        <div class="layui-field-box">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside"
                             lay-arrow="none" style="width: 100%; height: 90px;">
                            <div carousel-item="">
                                <ul class="layui-row layui-col-space10 layui-this">
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>信息总数</h3>
                                            <p>
                                                <cite><?= $total ?></cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>置顶总数</h3>
                                            <p>
                                                <cite><?= $top_total ?></cite></p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>消息发布统计</legend>
        <div class="layui-field-box">
            <div id="info-count-charts" style="width:100%;height:300px;"></div>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>开发团队</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th>版权所有</th>
                    <td>mr.liu</td>
                </tr>
                <tr>
                    <th>开发者</th>
                    <td>mr.liu</td>
                </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <blockquote class="layui-elem-quote layui-quote-nm">感谢layUI,百度ECharts,JQuery，本系统是信息发布平台。</blockquote>
</div>
<script>
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();

    loadCountCharts();

    function loadCountCharts() {

        var dates = eval('<?=$dates?>');
        var itemList = eval('<?=$itemList?>');
        var myChart = echarts.init(document.getElementById("info-count-charts"));

        option = {
            title: {
                // text: '消息发布统计'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                // data: ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    // saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: dates
            },
            yAxis: {
                type: 'value',
                name: '次'
            },
            series: [
                {
                    name: '消息数',
                    type: 'line',
                    stack: '总量',
                    data: itemList
                }
            ]
        };

        myChart.setOption(option, true);
    }


    $(function(){
        setInterval("getTime();",1000); //每隔一秒运行一次
    });

    //取得系统当前时间
    function getTime(){
        var myDate = new Date();
        var date = myDate.toLocaleDateString();
        var hours = myDate.getHours();
        var minutes = myDate.getMinutes();
        var seconds = myDate.getSeconds();

        $("#currentTime").html(date+" "+hours+":"+minutes+":"+seconds); //将值赋给div
    }

</script>