<?php
$this->title = '任务详情';
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
        <img src="" id="taskimg" class="taskimg-1">
    </div>
    <div class="task-time">
        <ul>
            <li>发起人：<span id="taskpeople"></span></li>
            <li>开始时间：<span id="starttime"></span></li>
            <li>结束时间：<span id="endtime"></span></li>
            <li>契约金：<span id="taskmoney"></span></li>

        </ul>
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
            <img src="" class="taskimg2 taskimg-1">
        </div>
        <div class="cells-list">
            <h5>第二步</h5>
            <p>点击打卡，进入"传送门"页面</p>
        </div>
        <div class="cells-list">
            <h5>第三步：长按二维码进入公众号</h5>
            <img src="" class="taskimg1 taskimg-1">
        </div>
        <div class="cells-list">
            <h5>第4步</h5>
            <p>阅读当天头条文章，并点击底部"阅读原文"为该文章评分</p>
        </div>


    </div>


</div>

<script>
    $(document).ready(function () {
        var unionid=getUrlParam("unionId");
        var taskid=getUrlParam("id");
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
                    $("#days").html(""+data.days+"");
                    $("#descripe").html(""+data.description+"");
                    $("#taskmoney").html(""+data.price+"");
                    $("#taskpeople").html(""+originator.nickName+"")
                    $("#taskimg").attr("src",""+data.taskQrCode+"");
                    $(".taskimg1").attr("src",""+data.pushQrCode+"");
                    $(".taskimg2").attr("src",""+data.qrCode+"");



                }
            }
        })

    });

</script></script>
