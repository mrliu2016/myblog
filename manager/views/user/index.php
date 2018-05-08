<?php

use yii\widgets\LinkPager;

$this->title = '用户管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/user/index" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">查询
                            </button>
                            <div class="col-md-2">
                                <input type="text" style="width: 120px" id="content" name="id" placeholder="请输入用户ID"
                                       class="form-control"
                                    <?php if (!empty($params['id'])): ?>
                                        value="<?= $params['id'] ?>"
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
                    <!--                    <th class="col-md-1">应用</th>-->
                    <th class="col-md-1">昵称</th>
                    <th class="col-md-1">头像</th>
                    <th class="col-md-1">等级</th>
                    <th class="col-md-1">虚拟货币</th>
                    <th class="col-md-1">余额(元)</th>
                    <th class="col-md-1">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $item): ?>
                    <tr>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <!--                        <td>-->
                        <!--                            --><? //= $item['applicationId'] ?>
                        <!--                        </td>-->
                        <td>
                            <?= $item['nickName'] ?>
                        </td>
                        <td>
                            <img src="<?= $item['avatar'] ?>" width="50" height="50" style="border-radius: 50%;">
                        </td>
                        <td>
                            <?= $item['level'] ?>
                        </td>
                        <td>
                            <?= $item['idealMoney'] ?>
                        </td>
                        <td>
                            <?= $item['balance'] / 100 ?>
                        </td>
                        <td>
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn" onclick="depositIdealMoney(<?= $item['id'] ?>)">虚拟币
                            </button>
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

    /**
     * 充值虚拟货币
     *
     * @param userId
     */
    function depositIdealMoney(userId) {
        layer.prompt({title: '输入虚拟货币，并确认', formType: 3}, function (idealMoney, index) {
            var regPos = /^\d+(\.\d+)?$/; //非负浮点数
            if (idealMoney == '') {
                layer.msg('输入虚拟货币', {icon: 2, time: 1000});
                return false;
            }
            if (!regPos.test(idealMoney)) {
                layer.msg('输入虚拟货币', {icon: 2, time: 1000});
                return false;
            }
            $.ajax({
                url: '/user/deposit-ideal-money',
                type: "post",
                cache: false,
                dataType: 'json',
                data: {
                    userId: userId,
                    idealMoney: idealMoney
                },
                success: function (response) {
                    switch (parseInt(response.code)) {
                        case 0:
                            layer.close(index);
                            layer.msg('虚拟货币充值成功！', {time: 1000}, function () {
                                window.location.reload();
                            });
                            break;
                        case -1:
                            layer.msg('虚拟货币充值失败！', {time: 1000});
                            break;
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('get issue');
                }
            });
        });
    }
</script>
