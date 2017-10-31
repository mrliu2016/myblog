<?php
use yii\widgets\LinkPager;

$this->title = '优惠券';
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
            <form method="post" action="/index/coupon" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                    name="searchBtn">添加
                            </button>
                            <div class="col-md-2">
                                <input type="text" style="width: 125px" placeholder="优惠券额(元)" name="price"
                                       class="form-control datepicker-pop">
                            </div>
                            <div class="col-md-2">
                                <select title="" class="form-control" name="type">
                                    <option value="0">新人券</option>
                                    <option value="1">其他</option>
                                </select>
                            </div>

                        </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr class="title_list">
                <th class="col-md-1">id</th>
                <th class="col-md-1">优惠券额(元)</th>
                <th class="col-md-1">类型</th>
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
                        <?= $item['price'] / 100 ?>
                    </td>
                    <td>
                        <?php if ($item['type'] == 0) {
                            echo "新人券";
                        } else {
                            echo "其他";
                        } ?>
                    </td>
                    <td>
                        <a href="/index/coupon-delete?id=<?=$item['id']?>">删除</a> ||
                        <a href="/index/coupon-update?id=<?=$item['id']?>&price=<?=$item['price']/100?>&type=<?=$item['type']?>">修改</a>
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
    function detail(unionId) {
        $.ajax({
            url: "/index/coupon-one?unionid=" + unionId,
            type: "get",
            cache: false,
            dataType: "text",
            success: function (msg) {
                alert(msg);
            }
        })
    }

</script>













