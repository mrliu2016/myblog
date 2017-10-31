<?php
$this->title = '我的任务';
?>
<style>
    p{
        margin-bottom:5px;
        margin-top:5px;
    }
    .weui-tabbar p{
        margin-bottom:5px;
        margin-top:5px;
    }
    .weui-footer_fixed-bottom{
        bottom:0;
    }
    .task-list{
        border:1px solid #f0f0f0;
        background-color: #fff;
        border-radius: 8px;
        margin-top:15px;
        padding-left: 30px;
        font-size: 1em;
        line-height: 2em;
        margin-bottom:15px;

    }
    .task-list h4{
        font-size:1.5em;
    }
    .task-list ul li{
        list-style: none;
    }
    .task-name{
        margin-left:0px;
        margin-right:15px;
    }
    .weui-tab__bd{
        margin-bottom: 30px;
    }
</style>
<div class="container">
    <div class="weui-cells mention-top">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>待做任务：<span id="tasknum"></span>个</p>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>待用启动金：<span id="taskmoney"></span>元</p>
            </div>
        </div>
    </div>

    <div class="weui-tab__bd">
        <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">


        </div>

    </div>
</div>
<div class="weui-tabbar weui-footer_fixed-bottom">
    <a href="/h5/index" class="weui-tabbar__item ">

        <p class="weui-tabbar__label">首页</p>
    </a>

    <a href="/user/info?state=--flag__2" class="weui-tabbar__item weui-bar__item--on">

        <p class="weui-tabbar__label">任务</p>
    </a>
    <a href="/user/info?state=--flag__1" class="weui-tabbar__item">

        <p class="weui-tabbar__label">我的</p>
    </a>
</div>
<script>
$(document).ready(function () {
    var unionid=getUrlParam("unionid");
   $.ajax({
       type:"get",
       url:"/task/my-task?unionId="+unionid,
       dataType: "json",
       success: function (data) {
           info = eval(data);
           var data=info.data;
           $("#tasknum").html(""+data.todoCount+"");
           $("#taskmoney").html(""+data.priceCoupon+"");
           var tasklist =data.task.list;
           var length =tasklist.length;
           for(i=0;i<length;i++){
               var html='';
               html+='<div class="task-list"> <div class="row task-name"><h4 class="pull-left">'+tasklist[i].name+'</h4> <p class="pull-right"> '+tasklist[i].taskStatus+'</p></div>';
               html+='<ul><li>任务类别：<span>'+tasklist[i].taskType+'</span></li> <li>开始时间：<span>'+tasklist[i].startTime+'</span></li><li>结束时间：<span>'+tasklist[i].endTime+'</span></li>';
               html+=' </ul></div>';
               $("#tab1").append(html);
           }

       },
       error: function (XMLHttpRequest, textStatus, errorThrown) {
           alert('get issue');
       }
   })

})
</script>
