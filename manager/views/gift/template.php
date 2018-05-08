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

                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">查询
                            </button>
                            <a href="/gift/create/">
                                <button type="button" class="mb-sm btn btn-primary ripple">添加礼物
                                </button>
                            </a>
                            <div class="col-md-3">
                                <input type="text" style="width: 200px" id="content" name="content" placeholder="请输入礼物ID或名称"
                                       class="form-control"
                                    <?php if (!empty($params['content'])): ?>
                                        value="<?= $params['content'] ?>"
                                    <?php endif; ?>>
                            </div>
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
                    <th class="col-md-1">名称</th>
                    <th class="col-md-1">图片</th>
                    <th class="col-md-1">价格(分)</th>
                    <th class="col-md-1">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $item): ?>
                    <tr>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <img src="<?= $item['imgSrc'] ?>" width="50" height="50">
                        </td>
                        <td>
                            <?= $item['price'] ?>
                        </td>
                        <td>
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
