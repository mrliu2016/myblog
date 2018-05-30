<?php

use yii\widgets\LinkPager;
$this->title = '用户管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/user/index" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 40px">
                    <div class="form-group">
                        <div class="col-sm-10 s-formWrap" style="display: flex; height=42px">
                            <div class="col-md-2" style="display: flex;">
                                <div class="query" style="white-space: nowrap;">
                                    ID <input type="text" style="width: 120px;display: inline-block" id="content" name="id" placeholder="请输入用户ID"
                                           class="form-control"
                                        <?php if (!empty($params['id'])): ?>
                                            value="<?= $params['id'] ?>"
                                        <?php endif; ?>>
                                </div>
                                <div class="query" style="white-space: nowrap;">
                                    昵称<input type="text" style="width: 120px;display: inline-block;" id="nickName" name="nickName" placeholder="昵称"
                                             class="form-control">
                                </div>
                                <div class="query" style="white-space: nowrap;">
                                    房间号<input type="text" style="width: 120px;display: inline-block" id="roomId" name="roomId" placeholder="房间号"
                                              class="form-control">
                                </div>
                                <div class="query" style="white-space: nowrap;">
                                    手机号<input type="text" style="width: 120px;display: inline-block" id="mobile" name="mobile" placeholder="手机号"
                                              class="form-control">
                                </div>

                                <div class="query" style="white-space: nowrap; display: flex; align-items: center;">
                                    是否认证<select>
                                        <option>是</option>
                                        <option>否</option>
                                    </select>
                                </div>
                                <div class="query" style="white-space: nowrap; display: flex; align-items: center;">
                                    状态<select>
                                        <option>启用</option>
                                        <option>禁用</option>
                                    </select>
                                </div>

                                <!--<div class="query" style="white-space: nowrap;">
                                    注册时间<input type="text" style="width: 120px;display: inline-block" id="mobile" name="id" placeholder="请输入用户ID"
                                               class="form-control">-<input type="text" style="width: 120px;display: inline-block" id="mobile" name="id" placeholder="请输入用户ID"
                                                                            class="form-control">
                                </div>-->

                                <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                        name="searchBtn" style="margin: 0 5px !important;">查询
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
                    <th class="col-md-1">性别</th>
                    <th class="col-md-1">房间号</th>
                    <th class="col-md-1">手机号</th>
                    <th class="col-md-1">身份证</th>
                    <th class="col-md-1">是否认证</th>
                    <th class="col-md-1">粉丝数</th>
                    <th class="col-md-1">账户余额/豆</th>
                    <th class="col-md-1">收到礼物/币</th>
                    <th class="col-md-1">送出礼物/豆</th>
                    <th class="col-md-1">直播次数</th>
                    <th class="col-md-1">被举报次数</th>
                    <th class="col-md-1">注册时间</th>
                    <th class="col-md-1">状态</th>
                    <th class="col-md-1">操作</th>
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
                            <?= (isset($item['sex'])&&$item['sex']==1)?'男':'女'?>
                        </td>
                        <td>
                            <?= $item['roomId']?>
                        </td>
                        <td>
                            <?= $item['mobile'] ?>
                        </td>
                        <td>
                            <?= $item['idCard'] ?>
                        </td>
                        <td>
                            <?= (isset($item['isValid'])&&$item['isValid']==1)?'已认证':'未认证'?>
                        </td>
                        <td>
                            <?= $item['followers_cnt'] ?>
                        </td>
                        <td>
                            <?= $item['balance'] ?>
                        </td>
                        <td>
                            <?= $item['receiveValue'] ?>
                        </td>
                        <td>
                            <?= $item['sendValue'] ?>
                        </td>
                        <td>
                            <?= $item['liveCount'] ?>
                        </td>
                        <td>
                            <?= $item['reportCount'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i',$item['created']) ?>
                        </td>
                        <td>
                            <?= $item['income'] ?>
                        </td>
                        <td>
                            启用
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
