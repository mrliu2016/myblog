<?php
$this->title = '违禁词管理';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <p class="s-gift-search-title s-page-title">违禁词管理</p>
            <form method="get" action="/contraband/list" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>违禁词</span>
                        <input class="c-input s-gift-search-input" type="text" name="word" autocomplete="off">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn"
                            style="float: none;margin-left: 20px;">查询
                    </button>
                </div>
            </form>
        </div>
        <div class="s-gitf-operate">
            <a class="c-btn u-radius--circle c-btn-primary" href="/contraband/add-word">新增</a>
            <!--<a class="c-btn u-radius--circle c-btn-primary" href="/contraband/batch-word">Excel导入</a>-->
            <a class="c-btn u-radius--circle c-btn-primary" href="#" id="import-excel">Excel导入</a>
            <button class="c-btn u-radius--circle c-btn-primary" id="refresh">更新缓存</button>
        </div>
        <div class="s-gift-table-wrap">
            <table class="c-table s-gift-table">
                <thead class="c-table-thead s-gift-thead">
                <tr>
                    <th>序号</th>
                    <th>ID</th>
                    <th>违禁词</th>
                    <th>更新时间</th>
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
                            <?= $item['word'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i:s', $item['updated']) ?>
                        </td>
                        <td>
                            <a href="#" class="s-page-font-color"
                               onclick="editWord(<?= $item['id'] ?>,'<?= $item['word'] ?>')">编辑</a>
                            <a href="#" class="s-page-font-color" onclick="deleteWord(<?= $item['id'] ?>)">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div>
            <p class="s-gift-count" style="padding-top: 10px;">共 <span class="s-page-font-color"><?= $count ?></span>
                条记录</p>
            <nav class="text-center" style="margin-left:30%">
                <table>
                    <tr>
                        <td class="page-space"> <?= $page ?></td>
                        <td>共<?= $count ?> 条</td>
                    </tr>
                </table>
            </nav>
        </div>
    </div>
</div>

<!--编辑弹框-->
<div id="edit_frame" style="display: none;">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banword">
        <div class="c-modal">
            <div class="c-modal-close s-banword-close">关闭</div>
            <div class="c-modal_header">编辑违禁词</div>
            <div class="s-banword-content">
                <input class="c-input s-banword-input" type="text" placeholder="0到10个字符长度" maxlength="10">
            </div>
            <div class="c-modal-footer s-banword-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banword-confirm">确认</button>
                <button class="c-btn c-btn--large s-banword-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--编辑弹框end-->
<!--确认是否删除start-->
<div id="confirm_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close">关闭</div>
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">确认是否删除？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--确认是否删除start-->
<!--确认是否删除start-->
<div id="tip_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text-tip">确认删除此机器人？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm" id="tip-confirm">确认</button>
            </div>
        </div>
    </div>
</div>

<!--导入违禁词start-->
<div id="import_frame" style="display: none;">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-import-excel">
        <div class="c-modal">
            <div class="c-modal-close s-import-excel-close">关闭</div>
            <div class="c-modal_header">Excel导入</div>
            <div class="s-banword-content">
                <form method="post" action="/contraband/batch-word" class="form-horizontal" id="importForm"
                      name="importForm" enctype="multipart/form-data">
                    <input type="file" class="filess" name="name" style="opacity: 0" accept="*.xls"/>
                </form>
                <div class="import-file">
                    <input type="text" class="filetext" placeholder="文件昵称" disabled/>
                    <button class="importBtn">选择文件</button>
                </div>
                <div class="download-file">
                    <form action="/contraband/download-template" id="downloadForm">
                        <button type="submit" class="download">点击下载模板文件</button>
                    </form>
                </div>

            </div>
            <div class="c-modal-footer s-import-excel-operate">
                <button class="c-btn c-btn-primary c-btn--large s-import-excel-confirm">确认</button>
                <button class="c-btn c-btn--large s-import-excel-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!---导入违禁词end-->

<script type="text/javascript">

    //导入excel
    $(".importBtn").click(function () {
        $(".filess").val("");
        $(".filess").click();
    });
    $(".filess").on("change", function () {
        var filePath = $(this).val();
        var arr = filePath.split('\\');
        var fileName = arr[arr.length - 1];
        filePath = filePath.toLowerCase().split(".");
        var fileType = filePath[filePath.length - 1];
        if (fileType == "xls") {
            $(".filetext").val(fileName);
        }
        else {
            hide_import_excel();
            tip("请选择.xls格式的文件!");
        }
    });
    //点击下载模板文件
    $("#download").click(function () {
        $('#downloadForm').attr('action', '/contraband/download-template');
        $("#downloadForm").submit()
    });
    //导入excel
    $(".s-import-excel-confirm").unbind("click").bind("click", function () {
        //先判断是否有文件
        var filePath = $(".filess").val();
        filePath = filePath.toLowerCase().split(".");
        var fileType = filePath[filePath.length - 1];
        if (fileType == "xls") {
            $("#importForm").submit();
        }
        else {
            return false;
        }
    });

    //搜索
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    $(".s-banword-cancel").unbind('click').bind('click', function () {
        $("#edit_frame").css("display", "none");
    });
    $(".s-banword-close").unbind('click').bind('click', function () {
        $("#edit_frame").css("display", "none");
    });
    //刷新redis
    $("#refresh").unbind('click').bind('click', function () {
        $.ajax({
            url: "/contraband/refresh",
            type: "get",
            // cache: false,
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {
                    tip("更新成功！");
                }
                else {
                    tip("更新失败");
                }
            }
        });
    });

    //添加一个编辑方法
    function editWord(id, word) {

        $("#edit_frame").css("display", "block");
        $(".s-banword-input").val(word);
        $(".s-banword-input").focus();//聚焦
        $(".s-banword-confirm").unbind('click').bind('click', function () {
            var word = $(".s-banword-input").val();
            if (word.length > 10 || word == "" || word == null || word == undefined) {
                return false;
            }
            var params = {};
            params.id = id;
            params.word = word;
            $("#edit_frame").css("display", "none");
            $.ajax({
                url: "/contraband/edit-word",
                type: "post",
                data: params,
                // cache: false,
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        window.location.reload();
                    }
                    else {
                        tip("编辑失败");
                    }
                    // window.location.reload();
                }
            });
        });
    }

    //删除违禁词
    function deleteWord(id) {
        $("#confirm_frame").css("display", "block");
        //点击确认
        $(".s-banlive-confirm").unbind("click").bind("click", function () {
            var params = {};
            params.id = id;
            $("#confirm_frame").css("display", "none");
            $.ajax({
                url: "/contraband/delete-word",
                type: "post",
                data: params,
                // cache: false,
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        window.location.reload();
                    }
                    else {
                        tip("删除失败");
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

    function tip(message) {
        $("#tip_frame").css("display", "block");
        $(".s-banlive-confirm-text-tip").text(message);
        $("#tip-confirm").unbind("click").bind("click", function () {
            $("#tip_frame").css("display", "none");
        });
    }

    //隐藏违禁词导入框
    function hide_import_excel() {
        $(".filetext").val("");
        $(".filess").val("");
        $("#import_frame").css("display", "none");
    }

    //excel 导入违禁词
    $("#import-excel").unbind("click").bind("click", function () {
        $("#import_frame").css("display", "block");
        $(".s-import-excel-cancel").unbind("click").bind("click", function () {
            hide_import_excel();
        });
        $(".s-import-excel-close").unbind("click").bind("click", function () {
            hide_import_excel();
        });
    });
</script>
