<?php
use yii\widgets\LinkPager;
$this->title = '用户管理';
?>

<style>
    .switch-disable {
        color: #ccc;
    }

    .switch-list-def {

    }

    .switch-hover {
        color: #448AFF
    }

    .switch-list-def span {
        color: #ccc;
    }

    .switch-list-on span {
        color: #448AFF;
    }

    .switch-mod{
        display: inline-block;
        position: relative;
        margin-left: 25px;
    }
    .input-switch{
        opacity: 0;
        position: absolute;
        width: 20px;
        height: 20px;
        z-index: 999;
        margin-top: 0px;
    }
    .lable-switch-mod{
        width: 20px;
        height: 12px;
        background: #898989;
        box-shadow: inset 0 1px 3px 0 rgba(0,0,0,.3);
        border-radius: 6px;
        cursor: pointer;
        transition: all .2s ease;
        margin-bottom: 0px;
    }
    .lable-switch-mod:after{
        position: absolute;
        content: "";
        right: 10px;
        width: 10px;
        height: 12px;
        background-image: linear-gradient(-180deg,#F8F9F6 0,#F5F5F5 100%);
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.3);
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
            <form method="get" action="/user/index" id="searchForm" name="searchForm">
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
                <div class="s-gift-search-item">
                    <span>手机号</span>
                    <input class="c-input s-gift-search-input" type="text" name="mobile">
                </div>
                    <br/>
                <div class="s-gift-search-item">
                    <span>是否认证</span>
                    <span class="select-wrap">
					<select class="c-input s-gift-search-select" name="isAuth" id="isAuth" default="0">
						<option value="0">全部</option>
                        <option value="1">已认证</option>
						<option value="2">未认证</option>
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
                    <input type="text" style="width: 120px" id="startTime" name="startTime"
                           class="form-control datepicker-pop">
                </div>
                <div class="s-gift-search-item">
                    <input type="text" style="width: 120px" id="startTime" name="endTime"
                           class="form-control datepicker-pop">
                </div>
                <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn">查询</button>

            </div>
            </form>
        </div>
        <!--<div class="s-gitf-operate">
            <button class="c-btn u-radius--circle c-btn-primary">新增</button>
            <a class="c-a s-gift-setting">设置连击</a>
        </div>-->
        <div class="s-gift-table-wrap">
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
                    <th>操作</th>
                </tr>
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
                            <a href="/user/detail?id=<?=$item['id']?>"><?= $item['nickName'] ?></a>
                        </td>
                        <td>
                            <?= (isset($item['sex'])&&$item['sex']==1)?'男':'女'?>
                        </td>
                        <td>
                            <?= $item['roomId']?>
                        </td>
                        <td>
                            <?= $item['mobile'] ?>
                        </td>
                        <td>
                            <?= $item['idCard'] ?>
                        </td>
                        <td>
                            <?= (isset($item['isValid'])&&$item['isValid']==1)?'已认证':'未认证'?>
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
                            <?= date('Y-m-d H:i',$item['created']) ?>
                        </td>
                        <td>
                            <?php if(empty($item['playType']) || $item['playType'] == 0):?>
                                <span class="s-basic_item-value">正常</span>
                            <?php elseif($item['playType'] == 1 || $item['playType'] == 2):?>
                                <span class="s-basic_item-value">禁播中</span>
                            <?php elseif($item['playType'] == 3):?>
                                <span class="s-basic_item-value">永久禁播</span>
                            <?php elseif($item['playType'] == 4):?>
                                <span class="s-basic_item-value">停用</span>
                            <? endif ?>
                        </td>
                        <td>
                            <?php if(empty($item['playType']) || $item['playType'] == 0):?>
                                <div class="switch-mod switch-mod-open switch-hover"><span>停用</span>
                                    <input class="show-notes input-switch" type="checkbox" name="show-notes"
                                           checked="checked"
                                           value="<?= $item["id"]. ",".$item["roomId"]?>" onclick="noplay(<?= $item['id']?>,<?=$item['roomId']?>)">
                                    <label for="show-notes" class="lable-switch-mod" ></label>
                                </div>
                            <?php elseif($item['playType'] == 1 || $item['playType'] == 2):?>
                                <div class="switch-mod switch-mod-open switch-hover"><span> 启用</span>
                                    <input class="show-notes input-switch" type="checkbox" name="show-notes"
                                           checked="checked"
                                           value="<?= $item["id"]. ",".$item["roomId"]?>" onclick="recovery(<?= $item['id']?>,<?=$item['roomId']?>)">
                                    <label for="show-notes" class="lable-switch-mod" ></label>
                                </div>
                            <?php endif ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="s-gift-count">共 <?= $count ?> 条记录</p>

    </div>
    <nav class="text-center">
        <table>
            <tr>
                <td> <?= LinkPager::widget(['pagination' => $pagination]) ?></td>
                <td>共<?= $count ?> 条</td>
            </tr>
        </table>
    </nav>
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

<!--恢复状态提示框start-->
<div style="display: none;" id="recovery_frame">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="c-modal-close s-banlive-close">关闭</div>
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">确认恢复用户正常状态？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
                <button class="c-btn c-btn--large s-banlive-cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--恢复状态提示框start-->

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

    $('.show-notes').change(function () {
        // var appId = $(this).val();
        var that = this;
        if ($(this).is(':checked')) {
            $(that).parent().removeClass("switch-list-def");
            $(that).parent().addClass("switch-list-on");
            $(that).prev().html("启用");
            // $(".s-banlive").css("display","block");
            // $("")
        }
        else {
            //禁播
            // $(".s-banlive").css("display","block");
            // alert(2222);
            $(that).prev().html("停用");
            $(that).parent().removeClass("switch-list-on");
            $(that).parent().addClass("switch-list-def");
        }
    });

    //关闭禁播
    $(".s-banlive-close").click(function () {
        $("#forbid_frame").css("display","none");
    });

    $(".s-banlive-cancel").click(function () {
        $("#forbid_frame").css("display","none");
    });

    $(".s-banlive-btn").click(function () {
        // console.log($(this).attr("data-val"));
        $(this).siblings().removeClass("c-btn-primary");
        $(this).addClass("c-btn-primary");
    });
    //禁播方法
    function noplay(userId,roomId) {
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
            params.type = type;

            console.log(params);
            $("#forbid_frame").css("display","none");

            $.ajax({
                type: 'post',
                url: '/user/noplay',
                data: params,
                dataType: 'json',
                // timeout: 5000
            }).done(function (data) {
                if(data.code == 0){
                    alert('禁播成功');
                }
                else{
                    alert('禁播失败');
                }
            });
        });
    }

    //恢复禁播状态
    function recovery(){
        $("#recovery_frame").css("display","block");
        $(".s-banlive-confirm").unbind('click').bind('click',function () {
            $("#recovery_frame").css("display","block");
            var params = {};
            params.userId = userId;
            params.roomId = roomId;

            console.log(params);
            $.ajax({
                type: 'post',
                url: '/user/recovery',
                data: params,
                dataType: 'json',
                // timeout: 1000
            }).done(function (data) {
                if(data.code == 0){
                    alert("恢复成功");
                }
                else{
                    alert("恢复失败");
                }
            });
        });
    }
</script>
