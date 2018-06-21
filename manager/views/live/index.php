<?php
use yii\widgets\LinkPager;
$this->title = '直播管理';
?>

<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <form method="get" action="/live/index" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName"  autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>注册时间</span>
                        <input class="c-input s-gift-search-input form-control datepicker-pop" type="text" id="startTime" name="startTime" autocomplete="off" style="width: 100px;">
                        —
                        <input type="text" id="endTime" name="endTime"
                               class="c-input s-gift-search-input form-control datepicker-pop" style="width: 100px;" autocomplete="off">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>
                </div>
            </form>
        </div>
       <!-- <div class="s-gitf-operate">
            <button class="c-btn u-radius--circle c-btn-primary">新增</button>
            <a class="c-a s-gift-setting">设置连击</a>
        </div>-->
        <div class="s-gift-table-wrap">
        <table class="c-table s-gift-table">
            <thead class="c-table-thead s-gift-thead">
            <tr>
                <th>序号</th>
                <th>ID</th>
                <th>房间号</th>
                <th>主播昵称</th>
                <th>观众数</th>
                <th>开始时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="c-table-tbody s-gift-tbody">

            <?php foreach ($itemList as $key=>$item): ?>
                <tr>
                    <td><?=$key+1?></td>
                    <td>
                        <?= $item['id'] ?>
                    </td>
                    <td>
                        <?= $item['roomId'] ?>
                    </td>
                    <td>
                        <?= $item['nickName'] ?>
                    </td>
                    <td>
                        <?= $item['viewerNum']?>
                    </td>
                    <td>
                        <?= date('Y-m-d H:i', $item['created']) ?> <br />
                    </td>
                    <td>
                        <?php if($item['isLive'] == 1){
                            echo "直播中";
                        } else if($item['isLive'] == 0){
                            echo "结束";
                        } ?>
                    </td>
                    <td>
                       <!--<a href="/live/check?id=<?/*=$item['id']*/?>">查看</a>-->
                       <a href="#" onclick="watchVideo('<?=$item['liveUrl'] ?>')">查看</a>
                        <?php if(empty($item['status']) || $item['status'] == 0): ?>
                            <a href="#" onclick="noplay(<?=$item['userId']?>,<?=$item['roomId']?>,<?=$item['isLive']?>)">禁播</a>
                        <?php elseif ($item['status'] == 1):?>
                            <a disabled class="c-btn s-gift-page" href="#" style="text-decoration: none;">禁播</a>
                        <?php endif; ?>
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

<!--禁播弹框start-->
<div style="display: none;" id="forbid_frame">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive" >
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close">关闭</div>
            <div class="s-banlive-content">
                <button class="c-btn s-banlive-btn" data-val="1">禁播24h</button>
                <button class="c-btn s-banlive-btn c-btn-primary" data-val="2">禁播30天</button>
                <button class="c-btn s-banlive-btn" data-val="3">永久禁播</button>
                <button class="c-btn s-banlive-btn" data-val="4">解封账号</button>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--禁播弹框end-->

<!--视频弹框start-->
<div id="video_modal" class="c-modal s-video-m" style="display: none;">
    <div id="video_drag" style="position: relative">
        <div class="c-modal-close s-video-m_close"></div>
        <div id="id_video_container" style="width:100%; height:auto;"></div>
    </div>
</div>
<!--视频弹框end-->


<script src="//qzonestyle.gtimg.cn/open/qcloud/video/live/h5/live_connect.js" charset="utf-8"></script>
<script type="text/javascript">
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    //时间插件
    $(".datepicker-pop").datetimepicker({
        todayHighlight: true,
        todayBtn: true,
        autoclose: true,
        minView: 3,
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });
    
    //视频查看
    function watchVideo(liveUrl) {
        $("#video_modal").css("display","block");
        var player = new qcVideo.Player("id_video_container", {
            // "channel_id": "16093104850682282611",
            // "app_id": "1251783441",
            "live_url":liveUrl,
            "width" : 480,
            "height" : 320
        });

        $(".s-video-m_close").unbind("click").bind("click",function () {
            $("#video_modal").css("display","none");
        });

    }


    // (function () {
    //     var player = new qcVideo.Player("id_video_container", {
    //         // "channel_id": "16093104850682282611",
    //         // "app_id": "1251783441",
    //         "live_url":"rtmp://ali.3tlive.customize.cdn.3ttech.cn/customize/0?auth_key=1528873569-0-0-02213b3409d805126e7d471d5f648745",
    //         "width" : 480,
    //         "height" : 320
    //     });
    // })();

    //关闭
    $(".s-banlive-close").click(function () {
        $("#forbid_frame").css("display","none");
    });

    //取消
    $(".s-banlive-cancel").click(function () {
        $("#forbid_frame").css("display","none");
    });
    //封禁类型
    $(".s-banlive-btn").click(function () {
        // console.log($(this).attr("data-val"));
        $(this).siblings().removeClass("c-btn-primary");
        $(this).addClass("c-btn-primary");
    });

    function detail(serverName, begin, end) {
        $.ajax({
            url: "/report/lost-detail?serverName=" + serverName + '&beginTime=' + begin + '&endTime=' + end,
            type: "get",
            cache: false,
            dataType: "text",
            success: function (data) {
                html = '<div id="main_1" style="width:1200px;height:450px;float: left"></div>'
                layer.open({
                    title: serverName,
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['1000px', '550px'], //宽高
                    content: html
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }

    //查看
    function check(id) {
        var params = {};
        params.id = id;
        $.ajax({
            url: "/live/check",
            type: "post",
            data: params,
            // cache: false,
            dataType: "json",
            success: function (data) {
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }

    //禁播
    function noplay(userId,roomId,isLive) {
        $("#forbid_frame").css("display","block");
        $(".s-banlive-confirm").unbind('click').bind('click',function () {
            var type = 0;
            $(".s-banlive-btn").each(function () {
                if($(this).hasClass("c-btn-primary")){
                    // console.log($(this).attr("data-val"));
                    type = $(this).attr("data-val")
                }
            });
            var params = {};
            params.userId = userId;
            params.roomId = roomId;
            params.isLive = isLive;
            params.type = type;

            $("#forbid_frame").css("display","none");
            // console.log(params);
            $.ajax({
                url: '/live/noplay',
                type: 'post',
                data: params,
                dataType: 'json',
                // timeout: 1000
            }).done(function (data) {
                if(data.code == 0){
                    // alert('禁播成功');
                    window.location.reload();
                }
                else{
                    alert('禁播失败');
                }
            });
        });
    }
</script>
<script>
    function dragRegist(bar, target, platform, callback) {
        var params = {
            left: 0,
            top: 0,
            currentX: 0,
            currentY: 0,
            flag: false
        };
        var platformElement = platform || document;
        var getCss = function(o, key) {
            return o.currentStyle ? o.currentStyle[key] : document.defaultView.getComputedStyle(o, false)[key];
        };
        if (getCss(target, 'left') !== 'auto') {
            params.left = getCss(target, 'left');
        }
        if (getCss(target, 'top') !== 'auto') {
            params.top = getCss(target, 'top');
        }
        bar.onmousedown = function(event) {
            params.flag = true;
            if (!event) {
                event = window.event;
                bar.onselectstart = function() {
                    return false;
                };
            }
            var e = event;
            params.currentX = e.clientX;
            params.currentY = e.clientY;
        };
        bar.onmouseup = function() {
            params.flag = false;
            if (getCss(target, 'left') !== 'auto') {
                params.left = getCss(target, 'left');
            }
            if (getCss(target, 'top') !== 'auto') {
                params.top = getCss(target, 'top');
            }
        };
        platformElement.onmousemove = function(event) {
            var e = event ? event : window.event;
            if (params.flag) {
                var nowX = e.clientX,
                    nowY = e.clientY;
                var disX = nowX - params.currentX,
                    disY = nowY - params.currentY;
                target.style.left = parseInt(params.left) + disX + 'px';
                target.style.top = parseInt(params.top) + disY + 'px';

                if (typeof callback == 'function') {
                    callback((parseInt(params.left) || 0) + disX, (parseInt(params.top) || 0) + disY);
                }

                if (event.preventDefault) {
                    event.preventDefault();
                }
                return false;
            }
        };
    }
    var modal = document.getElementById('video_drag');
    dragRegist(modal, modal);
</script>