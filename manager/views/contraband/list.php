<?php
use yii\widgets\LinkPager;
$this->title = '违禁词管理';
?>

<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
           <!-- <p class="s-gift-search-title">礼物管理</p>-->
            <form method="get" action="/contraband/list" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>

                </div>
            </form>
        </div>
        <div class="s-gitf-operate">
            <a class="c-btn u-radius--circle c-btn-primary" href="/contraband/add-word">新增</a>
            <a class="c-btn u-radius--circle c-btn-primary" href="/contraband/batch-word">Excel导入</a>
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
                        <a href="#" onclick="editWord(<?= $item['id'] ?>,'<?= $item['word'] ?>')">编辑</a>
                      <!--  <a href="/contraband/delete-word?id=<?/*= $item['id'] */?>">删除</a>-->
                        <a href="#" onclick="deleteWord(<?= $item['id'] ?>)">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p class="s-gift-count">共 <?= $count ?> 条记录</p>
    </div>
    <nav class="text-center" style="margin-left:30%">
        <table>
            <tr>
                <td class="page-space"> <?= $page ?></td>
                <td>共<?= $count ?> 条</td>
            </tr>
        </table>
    </nav>
</div>

<!--编辑弹框-->
<div id="edit_frame" style="display: none;">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banword" >
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

<script type="text/javascript">
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });

    $(".s-banword-cancel").unbind('click').bind('click',function () {
        $("#edit_frame").css("display","none");
    });

    $(".s-banword-close").unbind('click').bind('click',function(){
        $("#edit_frame").css("display","none");
    });
    //刷新redis
    $("#refresh").unbind('click').bind('click',function () {
        $.ajax({
            url: "/contraband/refresh",
            type: "get",
            // cache: false,
            dataType: "json",
            success: function (data) {
                if(data.code == 0){
                    alert("更新成功！");
                }
                else{
                    alert("更新失败");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    });

    //添加一个编辑方法
    function editWord(id,word) {

        $("#edit_frame").css("display","block");
        // $(".s-banword-input").val(word);
        $(".s-banword-input").focus();//聚焦
        $(".s-banword-confirm").unbind('click').bind('click',function () {

            var word = $(".s-banword-input").val();
            if(word.length >10){
                alert("字段长度要求不能超过10个字符。");
                return false;
            }

            var params = {};
            params.id = id;
            params.word = word;
            $("#edit_frame").css("display","none");
            $.ajax({
                url: "/contraband/edit-word",
                type: "post",
                data:params,
                // cache: false,
                dataType: "json",
                success: function (data) {
                    if(data.code == 0){
                        alert("编辑成功！");
                    }
                    else{
                        alert("编辑失败");
                    }
                    window.location.reload();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('get issue');
                }
            });
        });
    }

    //删除违禁词
    function deleteWord(id) {
        $("#confirm_frame").css("display","block");
        //点击确认
        $(".s-banlive-confirm").unbind("click").bind("click",function () {
            var params = {};
            params.id = id;
            $.ajax({
                url: "/contraband/delete-word",
                type: "post",
                data:params,
                // cache: false,
                dataType: "json",
                success: function (data) {
                    if(data.code == 0){
                        window.location.reload();
                    }
                    else{
                        alert("删除失败");
                    }
                    // window.location.reload();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('get issue');
                }
            });
        });
        $(".s-banlive-close").unbind('click').bind('click',function () {
            $("#confirm_frame").css("display","none");
        });
        $(".s-banlive-cancel").unbind('click').bind('click',function () {
            $("#confirm_frame").css("display","none");
        });
    }
</script>
