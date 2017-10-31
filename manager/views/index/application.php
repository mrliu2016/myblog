<?php
use yii\widgets\LinkPager;

$this->title = '服务号';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/index/application" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="content" name="name"
                                        <?php if (!empty($params['name'])): ?>
                                            value="<?= $params['name'] ?>"
                                        <?php endif; ?>
                                    >
                                </div>
                                <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                        name="searchBtn">筛选
                                </button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
            <form method="get" action="/index/application-add" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="content" name="name"
                                        <?php if (!empty($params['name'])): ?>
                                            value="<?= $params['name'] ?>"
                                        <?php endif; ?>
                                    >
                                </div>
                                <button type="submit" class="mb-sm btn btn-primary ripple">增加
                                </button>
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
                    <th class="col-md-1">公众号名称</th>
                    <th class="col-md-1">推荐</th>
                    <th class="col-md-2">logo</th>
                    <th class="col-md-2">二维码</th>
                    <th class="col-md-1">更新时间</th>
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
                            <?= empty($item['isRecommend']) ? '否' : '是' ?>
                        </td>
                        <td>
                            <?php if (!empty($item['imgSrc'])): ?>
                                <img width="100" height="100" src="<?= $item['imgSrc'] ?>">
                            <?php endif; ?>
                            <form enctype="multipart/form-data" method="post" action="/index/application">
                                <input type="hidden" name="id" value="<?php echo $item["id"]; ?>">
                                <input type="hidden" name="type" value="logo">
                                <input name="file" type="file">
                                <input type="submit" value="上传">
                            </form>
                        </td>
                        <td>
                            <?php if (!empty($item['qrCode'])): ?>
                                <img width="100" height="100" src="<?= $item['qrCode'] ?>">
                            <?php endif; ?>
                            <form enctype="multipart/form-data" method="post" action="/index/application">
                                <input type="hidden" name="id" value="<?php echo $item["id"]; ?>">
                                <input type="hidden" name="type" value="qrCode">
                                <input name="file" type="file">
                                <input type="submit" value="上传">
                            </form>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i:s', $item['updated']) ?>
                        </td>
                        <td>
                            <a href="/index/application-delete?id=<?php echo $item["id"]; ?>">删除</a>
                            <a href="/index/application-recommend?id=<?php echo $item["id"]; ?>"><?= empty($item['isRecommend']) ? '推荐' : '取消推荐' ?></a>
                            <a href="javascript:void(0);"
                               onclick="code('<?php echo $apiUrl; ?>',<?= $item['id'] ?>)">生成链接</a>
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

    function code(url, id) {
        alert(url + id + '--tid__');
    }
</script>
