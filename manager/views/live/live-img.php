<?php
$this->title = '直播管理';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <p class="s-gift-search-title s-page-title">直播管理</p>
            <form method="get" action="/live/live-img" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>图片名称</span>
                        <input class="c-input s-gift-search-input" type="text" name="name" autocomplete="off">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>
                </div>
            </form>
        </div>
        <div class="s-gitf-operate" style="margin-bottom: 30px;">
            <a class="c-btn u-radius--circle c-btn-primary" href="/live/add-img">新增</a>
            <!--            <a class="c-a s-gift-setting" href="/gift/setting">设置连击</a>-->
        </div>
        <div class="s-gift-table-wrap">
            <table class="c-table s-gift-table">
                <thead class="c-table-thead s-gift-thead">
                <tr>
                    <th>序号</th>
                    <th>ID</th>
                    <th>图片名称</th>
                    <th>上传时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="c-table-tbody s-gift-tbody">
                <?php foreach ($itemList as $key => $item): ?>
                    <tr>
                        <td>
                            <?= $key + 1 ?>
                        </td>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <a href="/live/img-detail?id=<?= $item['id'] ?>"
                               class="s-page-font-color"><?= $item['name'] ?></a>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['created']) ?>
                        </td>
                        <td>
                            <!--<a href="/live/gift-edit?id=<? /*= $item['id'] */ ?>" class="s-page-font-color">编辑</a>-->
                            <a href="#" onclick="deleteImg(<?= $item['id'] ?>)" class="s-page-font-color">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div>
            <p class="s-gift-count" style="padding-top: 10px;">共 <span class="s-page-font-color"><?= $count ?></span>
                条记录</p>
            <nav class="text-center pagebanner-location">
                <table>
                    <tr>
                        <td class="page-space"> <?= $page ?></td>
                        <!--<td>共<? /*= $count */ ?> 条</td>-->
                    </tr>
                </table>
            </nav>
        </div>
    </div>
</div>

<!--确认是否删除start-->
<div id="confirm_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close">关闭</div>
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">是否删除？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--确认是否删除end-->

<script type="text/javascript">
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });

    //删除礼物
    function deleteImg(id) {
        $("#confirm_frame").css("display", "block");
        //点击确认
        $(".s-banlive-confirm").unbind("click").bind("click", function () {
            $("#confirm_frame").css("display", "none");
            var params = {};
            params.id = id;
            $.ajax({
                url: "/live/img-delete",
                type: "post",
                data: params,
                // cache: false,
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        window.location.reload();
                    }
                    else {
                        alert("删除失败");
                    }
                }
            });
        });
        $(".s-banlive-close").unbind('click').bind('click', function () {
            $("#confirm_frame").css("display", "none");
        });
        $(".s-banlive-cancel").unbind('click').bind('click', function () {
            $("#confirm_frame").css("display", "none");
        });
    }

</script>
