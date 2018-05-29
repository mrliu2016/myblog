<?php
use yii\widgets\LinkPager;
$this->title = '礼物列表';
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
                                        ID <input type="text" style="width: 120px;display: inline-block" id="content" name="id" placeholder="请输入礼物ID"
                                                  class="form-control">
                                    </div>
                                    <div class="query" style="white-space: nowrap;">
                                        礼物名称<input type="text" style="width: 120px;display: inline-block;" id="name" name="name" placeholder="礼物名称"
                                                 class="form-control">
                                    </div>
                                    <div class="query" style="white-space: nowrap;">
                                        是否连发
                                        <select>
                                            <option>是</option>
                                            <option>否</option>
                                        </select>
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
        <input type="button" value="新增">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-md-1">序号</th>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-1">礼物名称</th>
                    <th class="col-md-1">价格/豆</th>
                    <th class="col-md-1">是否可以连发</th>
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
                            <?= $item['price'] ?>
                        </td>
                        <td>
                            <?= (!empty($item['isFire']) && $item['isFire'] == 1)?'是':'否'?>
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
