<?php
use yii\widgets\LinkPager;
$this->title = '机器人管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/gift/template" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-2">
                                <div class="col-md-2" style="display: flex;">
                                    <div class="query" style="white-space: nowrap;">
                                        ID <input type="text" style="width: 120px;display: inline-block" id="id" name="id" placeholder=""
                                                  class="form-control">
                                    </div>
                                    <div class="query" style="white-space: nowrap;">
                                        昵称<input type="text" style="width: 120px;display: inline-block;" id="name" name="name" placeholder=""
                                                 class="form-control">
                                    </div>
                                    <div class="query" style="white-space: nowrap;">
                                        房间号<input type="text" style="width: 120px;display: inline-block;" id="roomId" name="roomId" placeholder=""
                                                 class="form-control">
                                    </div>

                                    <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                            name="searchBtn">查询
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <div class="card">
        <a href="/robot/add-robot">新增</a>
        <a href="/robot/batch-add">批量新增</a>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-md-1">序号</th>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-1">昵称</th>
                    <th class="col-md-1">房间号</th>
                    <th class="col-md-1">性别</th>
                    <th class="col-md-1">所在地</th>
                    <th class="col-md-1">个性签名</th>
                    <th class="col-md-1">关注数</th>
                    <th class="col-md-1">粉丝数</th>
                    <th class="col-md-1">收到礼物/币</th>
                    <th class="col-md-1">送出礼物/豆</th>
                    <th class="col-md-1">更新时间</th>
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
                            <a href="/gift/detail?id=<?=$item['id']?>"><?= $item['name'] ?></a>
                        </td>
                        <td>
                            <?= $item['roomId'] ?>
                        </td>
                        <td>
                            <?= $item['sex'] ?>
                        </td>
                        <td>
                            <?= $item['address'] ?>
                        </td>
                        <td><!--个性签名-->
                            <?= $item['description'] ?>
                        </td>
                        <td><!--关注数-->
                            <?= $item['followees_cnt'] ?>
                        </td>
                        <td><!--粉丝数-->
                            <?= $item['followers_cnt'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i',$item['udpated']) ?>
                        </td>
                        <td>
                            <a href="/gift/gift-edit?id=<?= $item['id'] ?>">编辑</a>
                            <a href="/gift/gift-delete?id=<?= $item['id'] ?>">删除</a>
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
</script>
