<?php
use yii\widgets\LinkPager;

$this->title = '轮播图';
?>
<style type="text/css">
    .title_list th {
        text-align: center;
    }

    .detail_list td {
        text-align: center;

    }
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <fieldset style="height: 20px">
                <div class="form-group">
                    <div class="col-sm-10">
                        <div class="row">
                            <a href="/index/banner-add">
                                <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                        name="searchBtn">创建
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr class="title_list">
                    <th class="col-md-1">id</th>
                    <th class="col-md-1">图片</th>
                    <th class="col-md-1">链接地址</th>
                    <th class="col-md-1">状态</th>
                    <th class="col-md-1">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $item): ?>
                    <tr class="detail_list">
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <img class="img-rounded" style="width: 200px;height: 90px;" src="<?= $item['imgSrc'] ?>"
                                 onerror="javascript:this.src='/img/center3.jpg';">
                        </td>
                        <td>
                            <?= $item['url'] ?>
                        </td>
                        <td>
                            <?php if ($item['isRecommend'] == 0) {
                                echo "未推荐";
                            } else if ($item['isRecommend'] == 1) {
                                echo "推荐";
                            } ?>
                        </td>
                        <td>
                            <?php
                            switch ($item['isRecommend']) {
                                case 0:
                                    echo "<a href='/index/banner-operation?id=$item[id]&isRecommend=1'>推荐</a>";
                                    break;
                                case 1:
                                    echo "<a href='/index/banner-operation?id=$item[id]&isRecommend=0'>取消推荐</a>";
                            }
                            ?> ||
                            <a href="/index/banner-delete?id=<?= $item['id'] ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <nav class="text-center">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </nav>
</div>
<script>
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    $("#cleanBtn").click(function () {
        $(this).closest('form').find("input[type=text]").val("")
    });
</script>