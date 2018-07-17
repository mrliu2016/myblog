<?php
$this->title = '用户管理';
?>
<style>
    .switch-hover {
        color: #464646
    }

    .switch-list-def span {
        color: #ccc;
    }

    .switch-list-on span {
        color: #448AFF;
    }

    .switch-mod {
        display: inline-block;
        position: relative;
        margin-left: 25px;
    }

    .input-switch {
        opacity: 0;
        position: absolute;
        width: 20px;
        height: 20px;
        z-index: 999;
        margin-top: 0px;
    }

    .lable-switch-mod {
        width: 20px;
        height: 12px;
        background: #cacaca;
        box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, .3);
        border-radius: 6px;
        cursor: pointer;
        transition: all .2s ease;
        margin-bottom: 0px;
    }

    .lable-switch-mod:after {
        position: absolute;
        content: "";
        right: 10px;
        width: 10px;
        height: 12px;
        background-image: linear-gradient(-180deg, #F8F9F6 0, #F5F5F5 100%);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .3);
        border-radius: 6px;
        transition: all .15s ease;
    }

    .lable-switch-mod-open {
        width: 20px;
        height: 12px;
        background: #0ccb99;
        box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, .3);
        border-radius: 6px;
        cursor: pointer;
        transition: all .2s ease;
        margin-bottom: 0px;
    }

    .lable-switch-mod-open:after {
        position: absolute;
        content: "";
        right: 0;
        width: 10px;
        height: 12px;
        background-image: linear-gradient(-180deg, #F8F9F6 0, #F5F5F5 100%);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .3);
        border-radius: 6px;
        transition: all .15s ease;
    }

    .switch-list-def span {
        color: #ccc;
    }

    .switch-list-on span {
        color: #448AFF;
    }
</style>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <p class="s-gift-search-title s-page-title">用户管理</p>
            <form method="get" action="/user/index" id="searchForm" name="searchForm">
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
                        <span>手机号</span>
                        <input class="c-input s-gift-search-input" type="text" name="mobile" autocomplete="off">
                    </div>
                    <br/>
                    <div class="s-gift-search-item">
                        <span>是否认证</span>
                        <span class="select-wrap">
					<select class="c-input s-gift-search-select" name="isAuth" id="isAuth" default="0">

                        <?php if (empty($isAuth) || $isAuth == 0) { ?>
                            <option value="0" selected>全部</option>
                            <option value="1">已认证</option>
                            <option value="2">未认证</option>
                        <?php } elseif ($isAuth == 1) { ?>
                            <option value="0">全部</option>
                            <option value="1" selected>已认证</option>
                            <option value="2">未认证</option>
                        <?php } elseif ($isAuth == 2) { ?>
                            <option value="0">全部</option>
                            <option value="1">已认证</option>
                            <option value="2" selected>未认证</option>
                        <?php } ?>
					</select>
				  </span>
                    </div>
                    <div class="s-gift-search-item">
                        <span>状态</span>
                        <span class="select-wrap">
					<select class="c-input s-gift-search-select" name="playType" id="status" default="0">
						<option value="0">全部</option>
                        <option value="1">正常</option>
                        <option value="2">禁播中</option>
                        <option value="3">永久禁播</option>
                        <option value="4">停用</option>
					</select>
				  </span>
                    </div>
                    <div class="s-gift-search-item">
                        <span>注册时间</span>
                        <input class="c-input s-gift-search-input form-control datepicker-pop" type="text"
                               id="startTime" name="startTime" autocomplete="off" style="width: 100px;">
                        —
                        <input type="text" id="endTime" name="endTime"
                               class="c-input s-gift-search-input form-control datepicker-pop" autocomplete="off"
                               style="width: 100px;">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn">查询</button>

                </div>
            </form>
        </div>
        <div class="s-gift-table-wrap" style="margin-top:40px;">
            <table class="c-table s-gift-table">
                <thead class="c-table-thead s-gift-thead">
                <tr>
                <tr>
                    <th>序号</th>
                    <th>ID</th>
                    <th>昵称</th>
                    <th>性别</th>
                    <th>房间号</th>
                    <th>手机号</th>
                    <th>身份证</th>
                    <th>是否认证</th>
                    <th>粉丝数</th>
                    <th>账户余额/豆</th>
                    <th>收到礼物/币</th>
                    <th>送出礼物/豆</th>
                    <th>直播次数</th>
                    <th>被举报次数</th>
                    <th>注册时间</th>
                    <th>状态</th>
                    <th>充值</th>
                    <th>操作</th>
                </tr>
                </tr>
                </thead>
                <tbody class="c-table-tbody s-gift-tbody">
                <?php foreach ($itemList as $key => $item) { ?>
                    <tr>
                        <td>
                            <?= $key + 1 ?>
                        </td>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <a href="/user/detail?id=<?= $item['id'] ?>"
                               class="s-page-font-color"><?= $item['nickName'] ?></a>
                        </td>
                        <td>
                            <?= (isset($item['sex']) && $item['sex'] == 1) ? '男' : '女' ?>
                        </td>
                        <td>
                            <?= $item['roomId'] ?>
                        </td>
                        <td>
                            <?= $item['mobile'] ?>
                        </td>
                        <td>
                            <?= $item['idCard'] ?>
                        </td>
                        <td>
                            <?= (isset($item['isValid']) && $item['isValid'] == 1) ? '已认证' : '未认证' ?>
                        </td>
                        <td>
                            <?= $item['followers_cnt'] ?>
                        </td>
                        <td>
                            <?= $item['balance'] ?>
                        </td>
                        <td>
                            <?= $item['receiveValue'] ?>
                        </td>
                        <td>
                            <?= $item['sendValue'] ?>
                        </td>
                        <td>
                            <?= $item['liveCount'] ?>
                        </td>
                        <td>
                            <?= $item['reportCount'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['created']) ?>
                        </td>
                        <td>
                            <?php if (empty($item['playType']) || $item['playType'] == 0): ?>
                                <span class="s-basic_item-value">正常</span>
                            <?php elseif ($item['playType'] == 1): ?>
                                <?php if (time() - $item['playTime'] > 86400): ?>
                                    <span class="s-basic_item-value">正常</span>
                                <?php else: ?>
                                    <span class="s-basic_item-value"
                                          style="color:#ea5b5b;">禁播中(还剩<?= ceil(($item['playTime'] + 86400 - time()) / 3600) ?>
                                        h)</span>
                                <?php endif; ?>
                            <?php elseif ($item['playType'] == 2): ?>
                                <?php if (time() - $item['playTime'] > 2592000): ?>
                                    <span class="s-basic_item-value">正常</span>
                                <?php else: ?>
                                    <span class="s-basic_item-value"
                                          style="color:#ea5b5b;">禁播中(还剩<?= ceil(($item['playTime'] + 2592000 - time()) / 86400) ?>
                                        天)</span>
                                <?php endif; ?>
                            <?php elseif ($item['playType'] == 3): ?>
                                <span class="s-basic_item-value">永久禁播</span>
                            <?php elseif ($item['playType'] == 4): ?>
                                <span class="s-basic_item-value">停用</span>
                            <?php endif; ?>
                        </td>
                        <td><a href="#" class="s-page-font-color" onclick="recharge(<?= $item['id'] ?>)">充值</a></td>
                        <td>
                            <?php if (empty($item['playType']) || $item['playType'] == 0): ?>
                                <div class="switch-mod switch-mod-open switch-hover" style="margin-left:0px">
                                    <span>启用</span>
                                    <input class="show-notes input-switch " type="checkbox" name="show-notes"
                                           checked="checked"
                                           value="<?= $item["id"] . "," . $item["roomId"] ?>"
                                           onclick="noplay(<?= $item['id'] ?>,<?= $item['roomId'] ?>)">
                                    <label for="show-notes" class="lable-switch-mod-open"></label>
                                </div>
                            <?php elseif ($item['playType'] == 1 || $item['playType'] == 2 || $item['playType'] == 3 || $item['playType'] == 4): ?>
                                <div class="switch-mod switch-mod-open switch-hover" style="margin-left:0px">
                                    <span>停用</span>
                                    <input class="show-notes input-switch" type="checkbox" name="show-notes"
                                           checked="checked"
                                           value="<?= $item["id"] . "," . $item["roomId"] ?>"
                                           onclick="recovery(<?= $item['id'] ?>,<?= $item['roomId'] ?>)">
                                    <label for="show-notes" class="lable-switch-mod"></label>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div>
            <p class="s-gift-count" style="padding-top: 20px;">共 <span class="s-page-font-color"><?= $count ?></span>
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

<!--禁播弹框start-->
<div style="display: none;" id="forbid_frame">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close">关闭</div>
            <div class="s-banlive-content">
                <button class="c-btn s-banlive-btn" data-val="1">禁播24h</button>
                <button class="c-btn s-banlive-btn c-btn-primary" data-val="2">禁播30天</button>
                <button class="c-btn s-banlive-btn" data-val="3">永久禁播</button>
                <button class="c-btn s-banlive-btn" data-val="4">封禁账号</button>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm" id="noplay-comfirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--禁播弹框end-->

<!--恢复状态提示框start-->
<div style="display: none;" id="recovery_frame">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close s-banlive-close2">关闭</div>
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">确认恢复用户正常状态？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm" id="recovery-confirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel2">取消</button>
            </div>
        </div>
    </div>
</div>
<!--恢复状态提示框start-->

<!--编辑弹框-->
<div id="edit_frame" style="display: none;">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banword">
        <div class="c-modal">
            <div class="c-modal-close s-banword-close recharge-close">关闭</div>
            <div class="c-modal_header">充值</div>
            <div class="s-banword-content">
                <input class="c-input s-banword-input" type="number" placeholder="请输入正整数充值金额" maxlength="10"
                       id="user-balance">
            </div>
            <div class="c-modal-footer s-banword-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banword-confirm recharge-confirm">确认</button>
                <button class="c-btn c-btn--large s-banword-cancel recharge-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--编辑弹框end-->
<!--提示框start-->
<div style="display: none;" id="tip_frame">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text-tip">确认恢复用户正常状态？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm" id="tip-confirm">确认</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(".datepicker-pop").datetimepicker({
        todayHighlight: true,
        todayBtn: true,
        autoclose: true,
        minView: 3,
        format: 'yyyy-mm-dd',
        language: 'zh-CN'
    });
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    $("#cleanBtn").click(function () {
        $(this).closest('form').find("input[type=text]").val("")
    });

    //关闭禁播
    $(".s-banlive-close").unbind('click').bind('click', function () {
        $("#forbid_frame").css("display", "none");
    });
    $(".s-banlive-close2").unbind('click').bind('click', function () {
        $("#recovery_frame").css("display", "none");
    });

    $(".s-banlive-cancel").click(function () {
        $("#forbid_frame").css("display", "none");
    });

    $(".s-banlive-cancel2").click(function () {
        $("#recovery_frame").css("display", "none");
    });

    $(".s-banlive-btn").click(function () {
        // console.log($(this).attr("data-val"));
        $(this).siblings().removeClass("c-btn-primary");
        $(this).addClass("c-btn-primary");
    });

    //禁播方法
    function noplay(userId, roomId) {
        $("#forbid_frame").css("display", "block");
        $("#noplay-comfirm").unbind('click').bind('click', function () {
            var type = 0;
            $(".s-banlive-btn").each(function () {
                if ($(this).hasClass("c-btn-primary")) {
                    // console.log($(this).attr("data-val"));
                    type = $(this).attr("data-val")
                }
            });
            var params = {};
            params.userId = userId;
            params.roomId = roomId;
            params.type = type;
            $("#forbid_frame").css("display", "none");
            $.ajax({
                type: 'post',
                url: '/user/noplay',
                data: params,
                dataType: 'json',
                // timeout: 5000
            }).done(function (data) {
                if (data.code == 0) {
                    window.location.reload();
                }
                else {
                    tip("禁播失败");
                }
            });
        });
    }

    //恢复禁播状态
    function recovery(userId, roomId) {
        $("#recovery_frame").css("display", "block");
        $("#recovery-confirm").unbind('click').bind('click', function () {
            $("#recovery_frame").css("display", "none");
            var params = {};
            params.userId = userId;
            params.roomId = roomId;
            $("#recovery_frame").css("display", "none");
            $.ajax({
                type: 'post',
                url: '/user/recovery',
                data: params,
                dataType: 'json',
                // timeout: 1000
            }).done(function (data) {
                if (data.code == 0) {
                    window.location.reload();
                }
                else {
                    tip("恢复失败");
                }
            });
        });
    }

    //充值
    function recharge(userId) {
        $("#edit_frame").css("display", "block");
        $(".recharge-confirm").unbind("click").bind("click", function () {
            var balance = $("#user-balance").val();

            if (balance == '' || balance == undefined || balance == null || balance <= 0) {
                return false;
            }
            $("#edit_frame").css("display", "none");
            var params = {};
            params.userId = userId;
            params.balance = balance;
            $.ajax({
                type: 'post',
                url: '/user/recharge',
                data: params,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 0) {
                        window.location.reload();
                    }
                    else {
                        tip("充值失败！");
                    }
                }
            });
        });
        //取消
        $(".recharge-cancel").unbind("click").bind("click", function () {
            $("#edit_frame").css("display", "none");
        });
        //关闭
        $(".recharge-close").unbind("click").bind("click", function () {
            $("#edit_frame").css("display", "none");
        });
    }

    //提示框
    function tip(message) {
        $("#tip_frame").css("display", "block");
        $(".s-banlive-confirm-text-tip").text(message);
        $("#tip-confirm").unbind("click").bind("click", function () {
            $(".s-banlive-confirm-text-tip").text("");
            $("#tip_frame").css("display", "none");
        });
    }
</script>
