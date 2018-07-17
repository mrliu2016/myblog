<?php
$this->title = '直播记录';
?>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <p class="s-gift-search-title s-page-title">直播记录</p>
            <form method="get" action="/live/live-record" id="searchForm" name="searchForm">
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId" autocomplete="off">
                    </div>
                    <div class="s-gift-search-item">
                        <span>开始时间</span>
                        <input class="c-input s-gift-search-input form-control datepicker-pop" type="text"
                               id="startTime" name="startTime" autocomplete="off" style="width: 100px;">
                        —
                        <input type="text" id="endTime" name="endTime"
                               class="c-input s-gift-search-input form-control datepicker-pop" style="width: 100px;"
                               autocomplete="off">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn" id="searchBtn">查询</button>
                </div>
            </form>
        </div>
        <div class="s-gift-table-wrap" style="margin-top:40px;">
            <table class="c-table s-gift-table">
                <thead class="c-table-thead s-gift-thead">
                <tr>
                    <th>序号</th>
                    <th>ID</th>
                    <th>房间号</th>
                    <th>主播昵称</th>
                    <th>观众数</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="c-table-tbody s-gift-tbody">
                <?php foreach ($itemList as $key => $item): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
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
                            <?= $item['watchTime'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['startTime']) ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['created']) ?>
                        </td>
                        <td>
                            <a href="#" style="color: #1AC2AD;" onclick="playBack('<?= $item['videoSrc'] ?>')">查看回放</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div>
            <p class="s-gift-count" style="padding-top: 10px;">共 <span style="color:#1AC2AD;"><?= $count ?></span> 条记录
            </p>
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
<!--视频弹框start-->
<div id="video_modal" class="c-modal s-video-m" style="display: none;">
    <div id="video_drag" style="position: relative">
        <div class="c-modal-close s-video-m_close"></div>
        <video class="s-video-m_video" src="" controls="controls"></video>
    </div>
</div>
<!--视频弹框end-->
<script>
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

    //回访视频弹窗
    function playBack(videoSrc) {
        $(".s-video-m_video").attr("src", videoSrc);
        $("#video_modal").css("display", "block");
        $(".s-video-m_close").unbind("click").bind("click", function () {
            $("#video_modal").css("display", "none");
        });
        $("#video_drag").css({"top": "0px", "left": "0px"});
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
        var getCss = function (o, key) {
            return o.currentStyle ? o.currentStyle[key] : document.defaultView.getComputedStyle(o, false)[key];
        };
        if (getCss(target, 'left') !== 'auto') {
            params.left = getCss(target, 'left');
        }
        if (getCss(target, 'top') !== 'auto') {
            params.top = getCss(target, 'top');
        }
        bar.onmousedown = function (event) {
            params.flag = true;
            if (!event) {
                event = window.event;
                bar.onselectstart = function () {
                    return false;
                };
            }
            var e = event;
            params.currentX = e.clientX;
            params.currentY = e.clientY;
        };
        bar.onmouseup = function () {
            params.flag = false;
            if (getCss(target, 'left') !== 'auto') {
                params.left = getCss(target, 'left');
            }
            if (getCss(target, 'top') !== 'auto') {
                params.top = getCss(target, 'top');
            }
        };
        platformElement.onmousemove = function (event) {
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