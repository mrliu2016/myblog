<?php
$this->title = '通讯服务器';
?>
<link rel="stylesheet" href="/vendor/bootstrap-switch/dist/css/bootstrap-switch.min.css">
<script src="/vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
<style type="text/css">
    .table > tbody > tr > td {
        padding: 2px 5px;
    }

    .btn1 {
        padding: 4px;
        font-size: 12px;
        line-height: 20px;
        margin-left: 5px;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="col">
            <table class="table table-hover table1">
                <thead>
                <tr>
                    <th>区域</th>
                    <th>IP</th>
                    <th>CPU(%)</th>
                    <th>内存(G)</th>
                    <th>带宽(MBit/s)</th>
                    <th>磁盘(G)</th>
                    <th>服务器负载</th>
                    <th>调度状态</th>
                    <th>服务器心跳</th>
                    <th>操作</th>
                </tr>
                </thead>
                <?php foreach ($serverResource as $key => $item): ?>
                    <tr class="detail-list server-resource-<?= $key ?>">
                        <td>
                            北京
                        </td>
                        <td class="ip-<?= $key ?>">
                            <?php if ($item['idle'] < 50) { ?>
                                <span style="color: red;"><?= $item['ip'] ?></span>
                            <?php } else { ?>
                                <span style="color: green;"><?= $item['ip'] ?></span>
                            <?php } ?>
                        </td>
                        <td class="cpu-<?= $key ?>">
                            使用：<?= $item['us'] ?><br/>
                            sy：<?= $item['sy'] ?><br/>
                            <?php if ($item['idle'] < 50) { ?>
                                空闲：<span style="color: red;"><?= $item['idle'] ?></span><br/>
                            <?php } else { ?>
                                空闲：<span style="color: green;"><?= $item['idle'] ?></span><br/>
                            <?php } ?>
                            si：<?= $item['si'] ?>
                        </td>
                        <td class="memory-<?= $key ?>">
                            总大小：<?= $item['memTotal'] ?><br/>
                            已用：<?= $item['memUsed'] ?><br/>
                            空闲：<?= $item['memFree'] ?><br/>
                            实际使用：<?= $item['memRealPercent'] ?>
                        </td>
                        <td class="network-<?= $key ?>">
                            发送：<?= $item['outgoing'] ?><br/>
                            接收：<?= $item['incoming'] ?><br/>
                        </td>
                        <td class="disk-<?= $key ?>">
                            总大小：<?= $item['total'] ?><br/>
                            已用：<?= $item['used'] ?><br/>
                            空闲：<?= $item['free'] ?>
                        </td>
                        <td class="load-<?= $key ?>">
                            负载数：<?= $item['loadAvgNum'] ?><br/>
                            已连接数：<?= $item['connectionNum'] ?><br/>
                        </td>
                        <td class="isOnline-<?= $key ?>">
                            <?= $item['isOnline'] ? '开启' : '<span style="color:red;">关闭</span>' ?>
                        </td>
                        <td class="time-<?= $key ?>">
                            <?= $item['heartbeat'] ?>
                        </td>
                        <td>
                            <button class="btn btn1 btn-primary reset-load-avg"
                                    onclick="resetLoadAvg('<?= $item['ip'] ?>')">调整负载
                            </button>
                            <button class="btn btn1 btn-primary dispatch-location"
                                    onclick="dispatchLocation('<?= $item['ip'] ?>')">调度
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function rest() {
        $.ajax({
            type: 'get',
            url: '/i-m/ajax-get-server' + "?r=" + Math.random(),
            dataType: 'json',
            timeout: 5000
        }).done(function (response) {
            switch (parseInt(response.code)) {
                case 0:
                    var length = response.data.length;
                    var index;
                    for (index = 0; index < length; index++) {
                        // cpu
                        var cpu = '使用：' + response.data[index].us + '<br/>sy：' + response.data[index].sy + '<br/>';
                        if (parseInt(response.data[index].idle) < 50) {
                            html = '<span style="color: red;">' + response.data[index].ip + '</span>';
                            cpu += '空闲：<span style="color: red;">' + response.data[index].idle + '</span><br/>';
                        } else {
                            html = '<span style="color: green;">' + response.data[index].ip + '</span>';
                            cpu += '空闲：<span style="color: green;">' + response.data[index].idle + '</span><br/>';
                        }
                        cpu += 'si：' + response.data[index].si;
                        $('.ip-' + index).html(html);
                        $('.cpu-' + index).html(cpu);
                        // memory
                        var memory = '总大小：' + response.data[index].memTotal + '<br/>已用：' + response.data[index].memUsed
                            + '<br/>空闲：' + response.data[index].memFree + '<br/>实际使用：' + response.data[index].memRealPercent;
                        $('.memory-' + index).html(memory);
                        // network
                        var network = '发送：' + response.data[index].outgoing + '<br/>接收：' + response.data[index].incoming;
                        $('.network-' + index).html(network);
                        // disk
                        var disk = '总大小：' + response.data[index].total + '<br/>已用：' + response.data[index].used
                            + '<br/>空闲：' + response.data[index].free;
                        $('.disk-' + index).html(disk);
                        // load
                        var load = '负载数：' + response.data[index].loadAvgNum + '<br/>已连接数：' + response.data[index].connectionNum;
                        $('.load-' + index).html(load);
                        // isOnline
                        if (response.data[index].isOnline) {
                            html = '开启';
                        } else {
                            html = '<span style="color:red;">关闭</span>';
                        }
                        $('.isOnline-' + index).html(html);
                        // time
                        $('.time-' + index).html(response.data[index].heartbeat);
                    }
                    break;
                default:
                    break;
            }
        }).fail(function (jqXHR, textStatus) {
            layer.msg('请求失败');
        });
        t = setTimeout("rest()", 1000);
    };
    setTimeout("rest()", 1000);

    /**
     * 调整负载
     */
    $('.reset-load-avg').on('click', function () {
        // $.ajax({
        //     type: 'post',
        //     url: '/i-m/reset-load-avg' + "?r=" + Math.random(),
        //     dataType: 'json',
        //     timeout: 5000
        // }).done(function (response) {
        // }).fail(function (jqXHR, textStatus) {
        //     layer.msg('请求失败:');
        // });
    });

    /**
     * 开启或关闭
     *
     * @param ip
     */
    function dispatchLocation(ip) {
        layer.confirm('开启或关闭该服务器调度?', {
            btn: ['开启', '关闭'], //按钮
            title: '服务器调度'
        }, function () {
            requestDispatchLocation({ip: ip, type: 1});
        }, function () {
            requestDispatchLocation({ip: ip, type: 0});
        });
    }

    /**
     * 请求执行开启、关闭调度操作
     *
     * @param data
     */
    function requestDispatchLocation(data) {
        $.ajax({
            type: 'post',
            url: '/i-m/dispatch-location',
            dataType: 'json',
            timeout: 5000,
            data: data
        }).success(function (response) {
            switch (parseInt(response.code)) {
                case 0:
                    layer.msg('操作成功', {
                        time: 1000
                    });
                    break;
                case -1:
                    layer.msg('操作失败', {
                        time: 1000
                    });
                    break;
            }
        }).error(function (jqXHR, textStatus) {
            layer.msg('系统繁忙，请稍后重试!');
        });
    }

    /**
     * 调整负载数
     *
     * @param ip
     */
    function resetLoadAvg(ip) {
        //prompt层
        layer.prompt({title: '调整服务器负载', formType: 3}, function (pass, index) {
            $.ajax({
                type: 'post',
                url: '/i-m/reset-load-avg',
                dataType: 'json',
                timeout: 5000,
                data: {ip: ip, loadAvg: pass}
            }).success(function (response) {
                console.log(response);
                switch (parseInt(response.code)) {
                    case 0:
                        layer.msg('操作成功', {
                            time: 1000
                        });
                        layer.close(index);
                        break;
                    case -1:
                        layer.msg(response.message, {
                            time: 1000
                        });
                        break;
                }
            }).error(function (jqXHR, textStatus) {
                layer.msg('系统繁忙，请稍后重试!');
            });
        });
    }
</script>

