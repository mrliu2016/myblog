<?php
$this->title = '加入任务团';
?>
<style>
    .task-name {
        text-align: center;
    }

    .task-time ul {
        margin-top: 2em;
    }

    .task-time ul li {
        list-style: none;
        padding-left: 2em;
        font-size: 1em;
        line-height: 2em;
    }

    .weui-cell__bd .taskimg {
        width: 28px;
        height: 28px;
        border-radius: 14px;
        display: inline-block;
        margin-left: 2px;
    }
    .weui-cell__hd .taskimg {
        width: 28px;
        height: 28px;
        border-radius: 14px;
        display: inline-block;
        margin-left: 2px;
    }
    p{
        margin-bottom: 0px;
        font-size: 0.8em;
    }
    .task-but{
        margin:5px 15px;
    }

    .cells-list{
        margin:0 auto;
        width: 70%;
    }
    .cells-list img{
        text-align: center;
    }
    .taskimg-1{
        width: 250px;
        height: 250px;
        text-align: center;
    }
</style>
<div class="container">
    <div class="task-name">
        <h5 id="taskname"></h5>
        <img src="/img/erwei.png" id="taskimg" class="taskimg-1">
    </div>
    <div class="task-time">
        <ul>
            <li>开始时间：<span id="starttime"></span></li>
            <li>结束时间：<span id="endtime"></span></li>
            <li>契约金：<span id="taskmoney"></span></li>
        </ul>
    </div>
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access members-list" href="/h5/task-members">
            <div class="weui-cell__bd memberlist">
            </div>
            <div class="weui-cell__ft">
                <p><span id="tasknum"></span>人已经报名</p><p><span id="days"></span>天后平分<span id="sumprice"></span>元</p>
            </div>
        </a>
        <a href="javascript:;" class="weui-btn weui-btn_primary task-but">报名</a>
    </div>
    <div class="weui-cells__title">任务描述</div>
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="javascript:;">
            <div class="weui-cell__bd " id="descripe">

            </div>

        </a>
    </div>
    <div class="weui-cells__title">任务步骤</div>
    <div class="weui-cells">
        <div class="cells-list">
            <h5>第一步：长按关注，接收任务通知信息</h5>
            <img src="/img/erwei.png" class="taskimg2 taskimg-1">
        </div>
        <div class="cells-list">
            <h5>第二步</h5>
            <p>点击打卡，进入"传送门"页面</p>
        </div>
        <div class="cells-list">
            <h5>第三步：长按二维码进入公众号</h5>
            <img src="/img/erwei.png" class="taskimg1 taskimg-1">
        </div>
        <div class="cells-list">
            <h5>第4步</h5>
            <p>阅读当天头条文章，并点击底部"阅读原文"为该文章评分</p>
        </div>


    </div>
    <div class="weui-cells">
        <h5 style="text-align: center">今日TOP10收入排名</h5>
            <div class="weui-cell">
                <div class="weui-cell__hd cell-list">
                    <p style="width: 30px;">1</p>
                </div>
                <div class="weui-cell__hd "><img src="/img/01.jpg" class="thumb"></div>
                <div class="weui-cell__bd cell-list-center">获得奖金10元</div>
            </div>
        <div class="weui-cell">
            <div class="weui-cell__hd cell-list">
                <p style="width: 30px;">1</p>
            </div>
            <div class="weui-cell__hd "><img src="/img/01.jpg" class="thumb"></div>
            <div class="weui-cell__bd cell-list-center">获得奖金10元</div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd cell-list">
                <p style="width: 30px;">1</p>
            </div>
            <div class="weui-cell__hd "><img src="/img/01.jpg" class="thumb"></div>
            <div class="weui-cell__bd cell-list-center">获得奖金10元</div>
        </div>

    </div>
    <div class="weui-cells__title">发起人信息</div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__hd"><img src="/img/01.jpg" class="taskimg originimg"></div>
            <div class="weui-cell__bd">
                <p class="originname">微信昵称</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
    </div>
    <div class="weui-cells__title">任务团其他信息</div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>大家都来参与啊</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
    </div>
</div>
<div class="weui-msg" style="display: none">
    <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">报名成功</h2>
    </div>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a href="#" class="weui-btn weui-btn_primary task-next">进入支付页面</a>
        </p>
    </div>

</div>
<script>
    $(document).ready(function () {
        var unionid=getUrlParam("unionid");
        var taskid=getUrlParam("taskid");
        $.ajax({
            type:"get",
            url:'/task/task-detail?taskId='+taskid+"&unionId="+unionid,
            dataType:"json",
            success:function (data) {
                var info=eval(data);
                if(info.code==0){
                    var data=info.data;
                    var members=data.memberList;
                    var originator =data.originator;
                    var price=data.price;
                    var aid=data.applicationId;
                    $("#starttime").html(""+data.startTime+"");
                    $("#endtime").html(""+data.endTime+"");
                    $("#days").html(""+data.remainTime+"");
                    $("#descripe").html(""+data.description+"");
                    $("#taskmoney").html(""+data.price+"");
                    $("#taskimg").attr("src",""+data.qrCode+"");
                    $(".taskimg1").attr("src",""+data.qrCode+"");
                    $(".taskimg2").attr("src",""+data.qrCode+"");
                    var registerStatus=data.registerStatus;
                    if(registerStatus==2){
                        $(".task-but").addClass("weui-btn_disabled");
                    }else if(registerStatus==1){
                        $(".task-but").addClass("weui-btn_disabled");
                        $(".task-but").html("已报名");
                    }else if(registerStatus==0){
                        $(".task-but").on("click",function () {
                            var unionid=getUrlParam("unionid");
                            var taskid=getUrlParam("taskid");
                            $.ajax({
                                type:"post",
                                dataType:"json",
                                url:"/task/join-task",
                                data:{
                                    taskId:taskid,
                                    unionId:unionid,
                                },
                                success:function (data) {
                                    var info=eval(data);
                                    if(info.code==0){
                                        $(".container").hide();
                                        $(".weui-msg").show();
                                    }
                                }

                            })

                        });
                        $(".task-next").attr("href","/h5/pay-join?unionId="+unionid+"&aid="+aid+"&taskid="+taskid+"&price="+price);

                    };
                    var length=members.length;
                    for(i=0;i<length;i++){
                        var html='';
                        html+='<img src='+members[i].avatar+' class="taskimg">';
                        $(".memberlist").append(html);
                    };
                    $("#tasknum").html(""+data.sumMember+"");
                    $("#sumprice").html(""+data.sumPriceReal+"");
                    $(".originimg").attr("src",""+originator.avatar+"");
                    $(".originname").html(""+originator.nickName+"");

                    $(".members-list").attr("href","/h5/task-members?taskid="+taskid);


                }
            }
        })

    });

</script>

