<?php
$this->title = $masterUserInfo['nickName'] . '的直播间';
?>
<script src="/img/3ttech/dist/js/video.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/3.0.2/videojs-contrib-hls.js"></script>

<script type="text/javascript" src="/img/3ttech/dist/js/queue.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/swiper.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/txbb-pop.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/template.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/constants.js"></script>

<script type="text/javascript">
    var webSocket = '<?= $webSocket ?>';
    var public = '/img/3ttech/dist/';
    var IMG_PATH = '/stylebak';
    var masterUserInfo = JSON.parse('<?= json_encode($masterUserInfo) ?>');
    var userInfo = JSON.parse('<?= json_encode($userInfo) ?>');
    var streamId = <?= $streamId['streamId'] ?>;
    var fly = "";

    var myPlayer;
    var h = window.screen.height;
    var isLive = <?= $liveInfo['isLive'] ?>;
    $(function () {
        if (isLive == 0) {
            $("#state").show();
            $("#top_box").hide();
        } else {
            $("#state").hide();
            $("#top_box").show();
        }
        setInterval("connectChange()", 1000);
        $n = 0;
    });

    function connectChange() {
        myPlayer = videojs("videoHLS");
        // 暂停播放
        myPlayer.on('pause', function () {
            $("#top_box").show();
        });
    }
</script>
<!--视频-->
<section class="section1">
    <article class="jwplayer jw-reset jw-stretch-fill">
        <div class="jw-media jw-reset">
            <div style="width:100%;height:100%;position:absolute;top:0;left:0;overflow:hidden;">
                <video id="videoHLS" class="video-js vjs-big-play-centered" style="width:100%;"
                       data-setup='{"preload": "auto"}'
                       webkit-playsinline playsinline x-webkit-airplay="true" x5-video-player-type="h5"
                       x5-video-player-fullscreen="true"
                       poster="">
                    <source src="<?= $pullWapStream; ?>" type="application/x-mpegURL">
                </video>
            </div>
            <div id="state"
                 style="text-align:center;line-height:40px;position:absolute;top:35%;z-index:11;color:#fff;padding:20px;display:none;">
                <h2>抱歉，主播已退出房间</h2>
            </div>
            <!--<div class="jw-preview jw-reset" style="background-image: url('{$user['head_url']}')"></div>-->
            <div class="jw-preview jw-reset"
                 style="background: url(/img/3ttech/dist/images/live_background.png);background-size: 100% 100%;"></div>
        </div>
        <script type="text/javascript">
            $("#videoHLS").height(h);
        </script>
    </article>

    <article class="section1_box" id="section1_box">
        <header class="header clearfix">
            <div class="clearfix">
                <div class="userinfo">
                    <img src="<?= $masterUserInfo['avatar'] ?>" userid="<?= $masterUserInfo['userId'] ?>">
                    <span class="ulive"><?= $masterUserInfo['nickName'] ?></span>
                    <span class="unum"><?= $masterUserInfo['roomId'] ?></span>
                </div>
                <div class="userimg" id="userimg">
                    <ul class="userpic clearfix" id="userpic"></ul>
                </div>
            </div>
        </header>
        <div class="download"></div>
        <article class="msg-box" id="upchat_hall">
            <div class="msg-con" id="chat_hall"></div>
        </article>
        <article class="chat_input">
            <div class="chat_barrage">
                <span style="line-height: 31px;height: 31px;">弹幕</span>
            </div>
            <span class="text_input">
                <input id="message" name="textfield" type="text" class="input" placeholder="我来说两句" value="">
            </span>
            <span class="send"
                  style="height:35px;line-height:35px;width: 70px;background: url(/img/3ttech/dist/images/xiazaibt3x.png);background-size: 100% 100%;text-align: center;color: #fff;">
                <a id="chat" href="javascript:void(0);"
                   style="height:35px;line-height:35px;width: 100%;color: #fff;">发送</a>
                <!--<img id="chat" src="/img/3ttech/dist/images/xiazaibt3x.png" align="absmiddle">-->
            </span>
        </article>

        <!--礼物列表-->
        <article class="chat_gift">
            <div class="swiper-container">
                <div class="swiper-wrapper" id="swiper-wrapper"></div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="chat_gift_send">
                <div class="balance">余额：<span class="bglance_money"><?= $userInfo['balance'] ?></span> 3T币<a
                            onclick="Ctrfn.balanceFn();">充值</a></div>
            </div>
        </article>
        <!--礼物列表-->

        <article id="heart">
            <canvas id="canvas"></canvas>
        </article>

        <figure class="share_box">
            <figcaption class="share_title">分享至</figcaption>
            <div class="iShare iShare-32 iShare3">
                <a href="javascript:void(0);" class="iShare_wechat"><i class="icon iconfont wechat"
                                                                       style="vertical-align: -2px;">&#xe60a;</i>
                    <p>微信</p></a>
                <a href="javascript:void(0);" class="iShare_qq"><i class="icon iconfont qq"
                                                                   style="vertical-align: 1px;">&#xe60c;</i>
                    <p>QQ</p></a>
                <!--<a href="#" class="iShare_qzone"><i class="icon iconfont qzone">&#xe60d;</i><p>QQ空间</p></a>-->
                <!--<a href="#" class="iShare_tencent"><i class="icon iconfont tencent" style="vertical-align: -2px;">&#xe60b;</i><p>腾讯微博</p></a>-->
                <!--<a href="#" class="iShare_weibo"><i class="icon iconfont weibo">&#xe60e;</i><p>新浪微博</p></a>-->
            </div>
        </figure>

        <nav class="chat-tool">
            <ul>
                <li><img src="/img/3ttech/dist/images/talk.png" id="talk-btn"></li>
                <li><img src="/img/3ttech/dist/images/sentgift.png" id="gift-btn"></li>
                <li>
                    <img src="/img/3ttech/dist/images/ic_room_flash_normal.png" id="more-btn">
                    <div class="more_list">
                        <a class="more_share" id="more_share">分享</a>
                        <if condition=" $userinfo.islogin eq 'false' ">
                            <a href="/wap/login?streamId=<?= $streamId['streamId'] ?>" class="more_center"
                               id="more_center">登陆</a>
                            <else/>
                            <a href="/wap/profile?userId=<?= $userInfo['userId'] ?>&streamId=<?= $streamId['streamId'] ?>"
                               class="more_center" style="display: none;" id="more_center">个人中心</a>
                        </if>
                    </div>
                </li>
            </ul>
        </nav>

        <article id="top_box">
            <button id="play"><img src="/img/3ttech/dist/images/play.png" width="61"></button>
        </article>

        <!--礼物显示效果-->
        <article id="gift_show_box" class="gift_show_box"></article>
        <article id="big_gift_show_box" class="big_gift_show_box"></article>


        <!--弹幕-->
        <div class="chat_barrage_box"></div>
    </article>
    <section class="touchbox" id="touchbox"></section>
    <!--排行版-->
    <section id="contributionval"></section>
</section>
<!--视频-->

<!--请登录微信-->
<section id="weui_dialog_alert" class="weui_dialog_alert" style="display:none;">
    <div class="weui_dialog">
        <img class="weui_dialog_hd" onclick="closedialog();" src="/img/3ttech/dist/images/close3x.png">
        <div class="weui_dialog_bd" id="weui_dialog_content">用微信登陆，与主播亲密互动吧</div>
        <div class="weui_dialog_ft">
            <a id="closedialog" href="/wap/login?streamId=<?= $streamId['streamId'] ?>"
               class="weui_btn_dialog">确定</a>
        </div>
    </div>
</section>
<!--请登录微信-->

<!--充值-->
<section id="chongzhi_alert" class="chongzhi_alert">
    <figure class="chongzhi_con">
        <figcaption class="chongzhi_title">
            余额充值
            <span><img src="/img/3ttech/dist/images/close3x.png" onclick="Ctrfn.closeCzhi();"></span>
        </figcaption>
        <div class="chongzhi_num"><label for="number">充值金额</label><input type="number" id="chongzhi_number"
                                                                         placeholder="填写金额"/><label for="number"
                                                                                                    class="yuan">元</label>
        </div>
        <div class="total_money">总金额：<span><?= $userInfo['balance'] ?></span></div>
        <div class="zhifu_btn" style="text-align: center;">
            <a class="weixin_pay" href="javascript:void(0);">微信支付</a>
        </div>
    </figure>
</section>
<!--充值-->

<!--送礼物-->
<section id="red_alert" class="red_alert">
    <figure class="red_con">
        <figcaption class="red_title">
            送礼物
            <span><img src="/img/3ttech/dist/images/close3x.png" onclick="Ctrfn.closeRed();"></span>
        </figcaption>
        <div class="red_num"><label for="number">个数</label><input type="number" id="red_number" value="1"/><label
                    for="number" class="yuan">个</label></div>
        <div class="red_num"><label for="number">总金额</label><input type="number" id="total_number" value=""
                                                                   placeholder="3T币" readonly/><label for="number"
                                                                                                      class="yuan"></label>
        </div>
        <div class="total_money">余额：<span><?= $userInfo['balance'] ?></span><a href="javascript:Ctrfn.balanceFn();"
                                                                               class="chongzhi">充值</a></div>
        <div class="send_red"><a class="red_btn" href="javascript:;">发送</a></div>
    </figure>
</section>
<!--送礼物-->

<!--QQ 微信分享提示-->
<section id="share_alert">
    <article class="share_prompt">
        <p></p>
    </article>
</section>
<!--QQ 微信分享提示-->

<!--点击用户头像显示信息-->
<section class="user_info_con" id="user_info_con"></section>


<!--排行榜模板-->
<script id="ranklist" type="text/html">
    <article class="contributionval_content">
        <div class="contr_close" onclick="Ctrfn.contr_close();"></div>
        <div class="contr_val"><img src="/img/3ttech/dist/images/me_ranking_yingpiao.png">(* wealth *)</div>
        <div class="contr_list">
            <ul class="contr_three">
                (*each list as value i*)
                (*if i <= 2*)
                <li class="contr_one">
                    <small>NO.(* i+1 *)</small>
                    <img class="contr_user" onerror="javascript:this.src='/dist/img/0_big.jpg'" src="(*value.avatar*)">
                    <div class="contr_pic"></div>
                    <div class="contr_nickname">(*value.username*)
                        (*if value.sex == 1*)
                        <img class="sex1" src="/img/3ttech/dist/images/sex1.png"/>
                        (*else*)
                        <img class="sex1" src="/img/3ttech/dist/images/sex0.png"/>
                        (*/if*)
                        <img class="contr_lev"
                             src="/img/3ttech/dist/images/level/public_icon_vip(*value.levelid*)@2x.png">
                    </div>
                    <div class="contr_nickname_num">3T币：<span>(*value.coin*)</span></div>
                </li>
                (*/if*)
                (*/each*)
            </ul>
            <ul class="contr_ord">
                (*each list as value i*)
                (*if i > 2*)
                <li class="clearfix">
                    <small class="fl">NO.(*i+1*)</small>
                    <span class="contr_ord_mld fl"><img onerror="javascript:this.src='/dist/img/0_big.jpg'"
                                                        src="(*value.avatar*)"></span>
                    <div class="contr_ord_right">
                        <p>
                            <a>(*value.username*) </a>
                            (*if value.sex == 1*)
                            <img class="sex1" src="/img/3ttech/dist/images/sex1.png"/>
                            (*else*)
                            <img class="sex1" src="/img/3ttech/dist/images/sex0.png"/>
                            (*/if*)
                            <img class="contr_lev"
                                 src="/img/3ttech/dist/images/level/public_icon_vip(*value.levelid*)@2x.png"></p>
                        <p>3T币：<span>(*value.coin*)</span></p>
                    </div>
                </li>
                (*/if*)
                (*/each*)
            </ul>
        </div>
    </article>
</script>
<!--排行榜模板-->

<!--礼物列表模板-->
<script id="giftlist" type="text/html">
    (*each pagenum as v k*)
    <article class="swiper-slide">
        (*each giftlist as value key*)
        (*if key>=(k)*8&&key<=(k+1)*8-1 *)
        <div data-id="(*value.id*)" data-giftname="(*value.giftname*)">
            <img src="(*value.gifticon*)" data-money="(*value.needcoin*)">
            <p>(*value.needcoin*)</p>
        </div>
        (*/if*)
        (*/each*)
    </article>
    (*/each*)
</script>
<!--礼物列表模板-->

<!--用户信息模板-->
<script id="userinfo" type="text/html">
    <div class="user_top clearfix">
        <img class="user_close" src="/img/3ttech/dist/images/close3x.png">
    </div>
    <div class="user_photo">
        <img onerror="this.src=\'/style/avatar/0/0_big.jpg\'" src="(*avatar*)">
    </div>
    <div class="user_name">(*nickname*)
        (*if sex == 1*)
        <img class="sex1" src="/img/3ttech/dist/images/sex1.png"/>
        (*else*)
        <img class="sex1" src="/img/3ttech/dist/images/sex0.png"/>
        (*/if*)
        <img src="/img/3ttech/dist/images/level/public_icon_vip(*emceelevel*)@2x.png" width="30">
    </div>
    <div class="">
        ID: (*id*)
        <span>
        <img src="/img/3ttech/dist/images/user_dre.png">
        (*if city != ''*)
        (*province*)  (*city*)
        (*else*)
        火星
        (*/if*)
        </span>
    </div>
    <div class="user_authentication">
        <span class="sel"><img src="/style/wushuang/dist/images/sel.png"></span>
        认证：还没有哦
    </div>
    (*if intro !=null *)y
    <div class="user_autograph">(*intro*)</div>
    (*else*)
    <div class="user_autograph">这家伙很懒，什么都没有留下</div>
    (*/if*)
    <div class="user_follow">
        <div><span><small>关注： (*followees_cnt*)</small></span>|<span class="user_fw_span">粉丝： (*followers_cnt*)</span>
        </div>
        <div>
            <span class="user_fw_sn">送出： (*total_contribution*)</span>
            |
            <span><small>3T币： (*beanorignal*)</small></span>
        </div>
    </div>
</script>
<!--用户信息模板-->

<!--主播信息模板-->
<script id="anchorInfo" type="text/html">
    <div class="user_top clearfix">
        <if condition=" $userinfo.islogin == 'true'">
            <button class="user_followed" data-follow="0" value="(*id*)">
                (*if is_attention == "0"*)
                关注
                (*else*)
                已关注
                (*/if*)
            </button>
        </if>
        <img class="user_close" src="/img/3ttech/dist/images/close3x.png">
    </div>
    <div class="user_photo">
        <img onerror="this.src=\'/style/avatar/0/0_big.jpg\'" src="(*avatar*)">
    </div>
    <div class="user_name">(*nickname*)
        (*if sex == 1*)
        <img class="sex1" src="/img/3ttech/dist/images/sex0.png"/>
        (*else*)
        <img class="sex1" src="/img/3ttech/dist/images/sex1.png"/>
        (*/if*)
        <img src="/img/3ttech/dist/images/level/public_icon_vip(*emceelevel*)@2x.png" width="30">
    </div>
    <div class="">
        ID: (*id*)
        <span>
        <img src="/img/3ttech/dist/images/user_dre.png">
        (*if city != ''*)
            (*province*)  (*city*)
        (*else*)
        火星
        (*/if*)
        </span>
    </div>
    <div class="user_authentication">
        <span class="sel"><img src="/style/wushuang/dist/images/sel.png"></span>
        认证：还没有哦
    </div>
    (*if intro !=null*)
    <div class="user_autograph">(*intro*)</div>
    (*else*)
    <div class="user_autograph">这家伙很懒，什么都没有留下</div>
    (*/if*)
    <div class="user_follow">
        <div><span><small>关注： (*followees_cnt*)</small></span>|<span class="user_fw_span">粉丝： (*followers_cnt*)</span>
        </div>
        <div>
            <span class="user_fw_sn">送出： (*total_contribution*)</span>
            |
            <span><small>3T币： (*beanorignal*)</small></span>
        </div>
    </div>
</script>
<!--主播信息模板-->
<script type="text/javascript" src="/img/3ttech/dist/js/heart.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/common.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/iShare.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/jquery.md5.js"></script>
<script type="text/javascript" src="/img/3ttech/dist/js/ws.js"></script>

<script type="text/javascript">

    (new iShare({
        container: '.iShare3', config: {
            title: '<?= $masterUserInfo['nickName'] ?>' + '的直播间',
            description: '欢迎进入：<?= $masterUserInfo['nickName'] ?>的直播间',
            url: '<?= $shareUrl ?>',
        }
    }));

    var mode = 1;//代表手机直播手机观看
    //点击微信分享
    $(".iShare_wechat").click(function () {
        var objbtn = $(this);
        Ctrfn.iShare(objbtn);
    })
    //点击QQ分享
    $(".iShare_qq").click(function () {
        var objbtn = $(this);
        Ctrfn.iShare(objbtn);
    })
    $("#share_alert").click(function () {
        $(this).hide();
    })
    //微信支付
    $(".weixin_pay").click(function () {
        Ctrfn.wxPay();
    })
    //分享
    $("#more_share").click(function (e) {
        Ctrfn.moreShare();
    })
    $("#more-btn").click(function (e) {
        Ctrfn.moreBtn();
    })

    //弹幕
    $(".chat_barrage span").click(function () {
        if ($(this).parent().hasClass("animte")) {
            $(this).parent().removeClass("animte");
            fly = ""
        } else {
            $(this).parent().addClass("animte");
            $("#message").val("").focus();
            fly = "FlyMsg"
        }
    })
    $("#chat").click(function () {
        var url = '__ROOT__/OpenAPI/v1/Gift/sendBarrage'
        Ctrfn.onmessage(url);
    })
    var focusstatus = 0;
    $(document).on("click", ".user_followed", function () {
        if ($.trim($(this).text()) == '已关注') {
            var url = "/OpenAPI/v1/User/unfollow";
        } else {
            var url = "/OpenAPI/v1/User/follow";
        }
        var attentionid = $(this).attr("value");
        var _this = $(this);
        $.ajax({
            type: 'POST',
            url: url,
            data: {'uid': attentionid},
            dataType: 'json',
            success: function (data) {
                if (data.code == 0) {
                    if (data.data == "关注成功!") {
                        _this.text('已关注');
                    } else {
                        _this.text('关注');
                    }
                }
            }
        });
    })
    //禁言用户
    $(document).on("click", ".user_gag", function () {
        if ($.trim($(this).text()) == "禁言") {
            var id = 0;
        } else {
            var id = 1;
        }
        var hituid = $(this).val();
        var _this = $(this);
        $.ajax({
            type: 'POST',
            url: "{{url()->Route('user::profile::anchorgag')}}",
            data: {"id": id, "hituid": hituid},
            dataType: 'json',
            success: function (data) {
                if (data['status'] == 0) {
                    _this.text('解禁');
                } else {
                    _this.text('禁言');
                }
            }
        });
    })

    //设置充值总金额值
    $(".total_money span").text($(".bglance_money").text());

    //点击聊天按钮，显示输入框
    $("#talk-btn").click(function (e) {
        1 ? Ctrfn.talkBtn(e) : $("#weui_dialog_alert").show();
    })

    //点击礼物tool
    $("#gift-btn").click(function () {
        1 ? Ctrfn.giftTool() : $("#weui_dialog_alert").show();
    })

    //阻止事件冒泡
    $(".chat_input").click(function (e) {
        e.stopPropagation();
    });

    //点击播放按钮
    $(document).on("click", "#play", function () {
        var objbtn = $(this);
        Ctrfn.play(objbtn);
    })
    //点击3T币
    //    $(".charmval").click(function(){
    //        var objbtn=$(this);
    //        var url='__ROOT__/OpenAPI/V1/user/sharecontributelist';
    //        Ctrfn.charmval(objbtn,url);
    //    })

    //点击发送禮物
    $(".red_btn").click(function () {
        var url = '__ROOT__/OpenAPI/V1/Gift/send';
        Ctrfn.sendBtn(url);
    })

    //点击关注
    //    $(document).on("click",".user_followed",function(){
    //         var objbtn=$(this);
    //        Ctrfn.userFollowed(objbtn);
    //    })

    //聊天提示时关闭提示框
    function closechatdialog() {
        $('#weui_dialog_alert').css('display', 'none');
        $('#message').focus();
    }

    //关闭提示框
    function closedialog() {
        $('#weui_dialog_alert').css('display', 'none');
    }

    //点击礼物
    $(document).on("click", ".swiper-slide > div", function (e) {
        var objbtn = $(this);
        Ctrfn.giftBtn(objbtn);
    })

    //红包个数发生改变时
    var red_val = '';
    $("#red_number").keyup(function (e) {
        red_val = $(this).val();
        $("#total_number").val(red_val * giftmoney);
    })

    //加载礼物tool

    //滑动清屏
    var viewport = document.getElementById("touchbox");
    var obj = document.getElementById("section1_box");
    document.addEventListener('touchstart', function (e) {
        var touch = e.touches[0];
        startX = touch.pageX;
        startY = touch.pageY;
    }, false)
    document.addEventListener('touchmove', function (e) {
        var touch = e.touches[0];
        var deltaX = touch.pageX - startX;
        var deltaY = touch.pageY - startY;
        //如果X方向上的位移大于Y方向，则认为是左右滑动
        if (Math.abs(deltaX) > Math.abs(deltaY) && deltaX > 50) {
            obj.className = "section1_box animte";
            $(".chat_gift").fadeOut();
            $(".chat_input").hide();
            $(".chat_barrage ").removeClass("animte");
            fly = ""
            $(".chat-tool").show();
        } else {
            obj.className = "section1_box";
        }
    }, false)

    //点击用户头像
    $(document).on("click", ".userpic li > img", function () {
        var objbtn = $(this);
        var url = '__ROOT__/OpenAPI/V1/user/shareProfile';
        Ctrfn.userpicBtn(objbtn, url);
    })
    $(document).on("click", ".user_close", function () {
        $('.user_info_con').hide();
    });

    //点击主播头像显示详情
    $(".section1_box .userinfo > img").click(function () {
//        var objbtn=$(this);
//        if(User.islogin == "true"){
//            var url='__ROOT__/OpenAPI/V1/user/profile';
//        }else{
//            var url='__ROOT__/OpenAPI/V1/user/shareProfile';
//        }
//        Ctrfn.userinfoBtn(objbtn,url);
    })
    // window.onload=function(){
    //     var div='<button id="play"><img src="/img/3ttech/dist/images/play.png" width="61"></button>';
    //     $("#top_box").append(div);
    // }



    window.shareData = {};
</script>
