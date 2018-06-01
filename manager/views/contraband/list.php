<?php
use yii\widgets\LinkPager;
$this->title = '违禁词管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/contraband/list" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">查询
                            </button>
                            <div class="col-md-2" >
                                违禁词<input type="text" style="width: 120px" id="word" name="word"
                                       class="form-control datepicker-pop"
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
                    <th class="col-md-1">序号</th>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-1">违禁词</th>
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
                            <?= $item['word'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i:s',$item['updated']) ?>
                        </td>
                        <td>
                            <a href="/contraband/edit-word?id=<?= $item['id'] ?>">编辑</a>
                            <a href="/contraband/delete-word?id=<?= $item['id'] ?>">删除</a>
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
