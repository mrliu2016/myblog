<?php
use yii\widgets\LinkPager;

$this->title = '充值历史';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/deposit/deposit-record" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
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
    <div class="card" style="position: relative;">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>订单别名</th>
                    <th>真实姓名</th>
                    <th>提现金额</th>
                    <th>申请时间</th>
                    <th>交易完成</th>
                    <th>状态</th>
                    <th>操作</th>
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
                            <?= $statusEnum[$item['type']] ?>
                        </td>
                        <td>
                            <?= $item['ipLocal'] ?>
                        </td>
                        <td>
                            <?php foreach (json_decode($item['ipList'], true) as $ip) { ?>
                                <?= isset($ip['ip']) ? $ip['ip'] : $ip ?>
                                <?= isset($ip['operator']) ? $ip['operator'] : '' ?>
                                <br/>
                            <?php } ?>
                        </td>
                        <!-- <td>
                            <?php if ($item['type'] == 1) {
                            foreach ($item['services'] as $ipItem) { ?>
                                    <?= $typeMap[$ipItem['type']] . ' 端口：' . $ipItem['port'] . ' 服务code：' . $ipItem['code'] . '' ?>
                                    <a href="/server/delete-service?code=<?= $ipItem['code'] ?>"  onclick="return confirm('删除时请三思!确定要删除吗')">X</a>
                                    <br/>
                                <?php }
                        } ?>
                        </td>-->
                        <td>
                            <?= $item['province'] ?>
                        </td>
                        <td>
                            <?= $item['status'] == 1 ? '在线' : '<span style="color: red;">离线</span>' ?>
                        </td>
                        <td>
                            <a href="/server/edit?id=<?= $item['id'] ?>">编辑</a>
                            <?php if ($item['status'] == 1) { ?>
                                <a href="/server/off-line?id=<?= $item['id'] ?>"
                                   onclick="return confirm('确定要下线吗')">下线</a>
                            <?php } else { ?>
                                <a href="/server/on-line?id=<?= $item['id'] ?>"
                                   onclick="return confirm('确定要上线吗')">上线</a>
                            <?php } ?>
                            |
                            <a href="/server/delete?id=<?= $item['id'] ?>"
                               onclick="return confirm('删除时请三思!确定要删除吗')">删除</a>
                            <?php if ($item['type'] == 1) { ?>
                                |
                                <a href="#" class="showDetil">服务</a>
                            <?php } ?>
                        </td>
                        <td class="serverDetail">

                            <p class="serverDetail_list">
                                <?php if ($item['type'] == 1) {
                                    foreach ($item['services'] as $ipItem) { ?>
                                        <?= $typeMap[$ipItem['type']] . ' 端口：' . $ipItem['port'] . ' 服务code：' . $ipItem['code'] . '' ?>
                                        <!--
                                <a href="/server/delete-service?code=<?= $ipItem['code'] ?>"  onclick="return confirm('删除时请三思!确定要删除吗')">X</a>
                                 -->
                                        <br/>
                                    <?php }
                                } ?>
                                <button class="mb-sm btn btn-primary ripple serverBtn"
                                >关闭
                                </button>
                            </p>
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
                <td>共<?= $pagination->totalCount ?> 条</td>
            </tr>
        </table>
    </nav>
</div>
<script>
    $(".datepicker-pop").datetimepicker({
        todayHighlight: true,
        todayBtn: true,
        autoclose: true,
        minView: 3,
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });

    function writeRedis() {
        $.ajax({
            url: "/server/server-redis",
            type: "get",
            cache: false,
            dataType: "json",
            success: function (data) {
                var info = eval(data);
                switch (parseInt(info.code)) {
                    case 0:
                        layer.msg('写入成功', {icon: 1});
                        break;
                    case 1:
                        layer.msg('写入失败', {icon: 2});
                        break;
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }
</script>