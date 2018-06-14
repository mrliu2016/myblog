<?php
$this->title = '机器人管理';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <!--<p class="s-gift-search-title">礼物管理</p>-->
            <form method="get" action="/robot/index" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>

                </div>
            </form>
        </div>
        <div class="s-gitf-operate">
            <a class="c-btn u-radius--circle c-btn-primary" href="/robot/add-robot" >新增</a>
            <a class="c-btn u-radius--circle c-btn-primary" href="/robot/batch-add">批量新增</a>
            <button class="c-btn u-radius--circle c-btn-primary" id="refresh">更新缓存</button>
        </div>
        <div class="s-gift-table-wrap">
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>昵称</th>
                <th>房间号</th>
                <th>性别</th>
                <th>所在地</th>
                <th>个性签名</th>
                <th>关注数</th>
                <th>粉丝数</th>
                <th>收到礼物/币</th>
                <th>送出礼物/豆</th>
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
                        <a href="/robot/detail?id=<?=$item['id']?>"><?= $item['nickName'] ?></a>
                    </td>
                    <td>
                        <?= $item['roomId'] ?>
                    </td>
                    <td>
                        <?= isset($item['sex'])&& $item['sex']==1?'男':'女' ?>
                    </td>
                    <td>
                        <?= $item['province'].$item['city'] ?>
                    </td>
                    <td><!--个性签名-->
                        <?= $item['description'] ?>
                    </td>
                    <td><!--关注数-->
                        <?= $item['followees_cnt'] ?>
                    </td>
                    <td><!--粉丝数-->
                        <?= $item['followers_cnt'] ?>
                    </td>
                    <td><!--收到礼物-->
                        <?= $item['receiveValue'] ?>
                    </td>
                    <td><!--送出礼物-->
                        <?= $item['sendValue'] ?>
                    </td>
                    <td>
                        <?= date('Y-m-d H:i',$item['updated']) ?>
                    </td>
                    <td>
                        <a href="/robot/edit-robot?id=<?= $item['id'] ?>">编辑</a>
                       <!-- <a href="/robot/delete-robot?id=<?/*= $item['id'] */?>">删除</a>-->
                        <a href="#" onclick="deleteRobot(<?=$item['id']?>)">删除</a>
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

<!--确认是否删除start-->
<div id="confirm_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close">关闭</div>
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">确认删除此机器人？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--确认是否删除end-->

<!--提示框Start-->
<div id="tip_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text2"></span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm" id="close-tip">确认</button>
            </div>
        </div>
    </div>
</div>
<!--提示框end-->

<script type="text/javascript">
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    //删除机器人
    function deleteRobot(id) {
        $("#confirm_frame").css("display","block");
        //点击确认
        $(".s-banlive-confirm").unbind("click").bind("click",function () {
            var params = {};
            params.id = id;
            $.ajax({
                url: "/robot/delete-robot",
                type: "post",
                data:params,
                // cache: false,
                dataType: "json",
                success: function (data) {
                    if(data.code == 0){
                        window.location.reload();
                    }
                    else{
                        tip("删除失败");
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
    //刷新redis
    $("#refresh").unbind('click').bind('click',function () {
        $.ajax({
            url: "/robot/refresh",
            type: "post",
            // cache: false,
            dataType: "json",
            success: function (data) {
                if(data.code == 0){
                    tip("更新成功");
                }
                else{
                    tip("更新失败");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    });

    function tip($message) {
        $("#tip_frame").css("display","block");
        $(".s-banlive-confirm-text2").text($message);

        $("#close-tip").unbind("click").bind("click",function(){
            $("#tip_frame").css("display","none");
        });
    }
    
</script>
