<?php
use yii\widgets\LinkPager;
$this->title = '举报管理';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/message/key" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">查询
                            </button>
                            <div class="col-md-2">
                                <input type="text" style="width: 120px" id="content" name="id"
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
                    <th class="col-md-1">被举报人ID</th>
                    <th class="col-md-1">被举报人昵称</th>
                    <th class="col-md-1">举报人ID</th>
                    <th class="col-md-1">举报人昵称</th>
                    <th class="col-md-1">举报类型</th>
                    <th class="col-md-1">举报时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $key => $item): ?>
                    <tr>
                        <td>
                            <?= $key+1 ?>
                        </td>
                        <td>
                            <?= $item['reportedUserId'] ?>
                        </td>
                        <td>
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <?= $item['userId'] ?>
                        </td>
                        <td>
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <?= $item['content'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i:s',$item['created']) ?>
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


</script>
