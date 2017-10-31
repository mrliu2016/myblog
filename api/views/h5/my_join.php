<?php
$this->title = '我参与的任务';
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
   .taskdetail{
       display: block;
       list-style: none;
       color: #333;
   }
</style>
<div class="container">
    <div class="weui-tab tab-top">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on" href="#tab0" id="begin" onclick="main(0)">
                未开始
            </a>
            <a class="weui-navbar__item" href="#tab1" id="progress" data-index="1" onclick="main(1)">
               进行中
            </a>
            <a class="weui-navbar__item" href="#tab2" id="over" data-index="2" onclick="main(2)">
                已结束
            </a>
        </div>
        <div class="weui-tab__bd">
            <div id="tab0" class="weui-tab__bd-item weui-tab__bd-item--active">

            </div>
            <div id="tab1" class="weui-tab__bd-item mention-history">


            </div>
            <div id="tab2" class="weui-tab__bd-item mention-history">


            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        main(0);
    })
    function main(type) {
        var unionid=getUrlParam("unionId");
     var role=0;
     var type=type;
        $.ajax({
            type:"get",
            url:"/task/my-task?unionId="+unionid+"&role="+role+"&type="+type,
            dataType: "json",
            success: function (data) {
                info = eval(data);
                var data=info.data;
                $("#tab"+type+"").empty();
                $("#tasknum").html(""+data.todoCount+"");
                $("#taskmoney").html(""+data.priceCoupon+"");
                var tasklist =data.task.list;
                var length =tasklist.length;
                for(i=0;i<length;i++){
                var html='';
                html+='<a class="taskdetail" href="/h5/taskdetail?id='+tasklist[i].id+'&unionId?='+unionid+'"><div class="task-list"> <div class="row task-name"><h4 class="pull-left">'+tasklist[i].name+'</h4> <p class="pull-right"> '+tasklist[i].taskStatus+'</p></div>';
                html+='<ul><li>任务类别：<span>'+tasklist[i].taskType+'</span></li> <li>开始时间：<span>'+tasklist[i].startTime+'</span></li><li>结束时间：<span>'+tasklist[i].endTime+'</span></li>';
                html+=' </ul></div></a>';
                $("#tab"+type+"").append(html);
                }


            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        })


    }
</script>

