<?php
$this->title = '收益提现';
?>
<style>
    .container{
        background-color: #f5f5f5;
    }
    .mention-top{
        margin-top:10px;
    }
    p{
        margin-bottom:0px;
        font-size: 0.8em;
    }
    .tab-top{
        margin-top:30px;
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
    .mention-history p{
        font-size: 1em;
        margin-top:15px;
        margin-bottom: 30px;
        line-height: 1.8em;
    }
    .mention-cells-title{
        font-size: 0.8em;
        color:red;
        margin:30px auto;
    }
    .cells-name{
        border:none;
        text-align: right;
        line-height: 2em;
        font-size: 0.8em;
    }
    .primary-but{
     left:50%;

    }
    .default-but{
     left:5%;
    }
    .mention-cells{
        position: absolute;
        top:0;
        left:0;
        right:0;
        display: none;
        z-index: 999;
        background-color: #fff;
    }
</style>
<div class="container">
    <div class="weui-cells mention-top">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>历史总收益：<span id="priceRealHistory"></span>元</p>
            </div>
        </div>
    </div>
    <div class="weui-cells  mention-top">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>可提现金额（满1元才可提现）：<span id="priceReal"></span>元 </p>
            </div>
            <div class="weui-cell__ft"><a href="javascript:;" class="weui-btn weui-btn_mini weui-btn_primary mention-moneys">提现</a></div>
        </div>
    </div>
    <div class="weui-cells  mention-top">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>待用启动资金：<span id="priceCoupon"></span>元 </p>
            </div>
            <div class="weui-cell__ft"><a href="/h5/instructions" style="font-size: 0.6em;">如何使用启动资金？</a></div>
        </div>
    </div>
    <div class="weui-tab tab-top">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on" href="#tab1">
               收益明细
            </a>
            <a class="weui-navbar__item" href="#tab2">
                提现记录
            </a>
        </div>
        <div class="weui-tab__bd">
            <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">

            </div>
            <div id="tab2" class="weui-tab__bd-item mention-history">
               <p>微信支付的结算周期为T+2，提现申请后，2天后自动到账。</p>

            </div>
        </div>
    </div>
    <!--弹出-->
    <div class="mention-cells">
        <div class="weui-cells__title mention-cells-title">提示：请在下方认证后每笔能提现上限2万。</div>
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>姓名</p>
                </div>
                <div class="weui-cell__ft"><input type="text" placeholder="微信提现实名认证" value="" class="cells-name realname"></div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>提现金额</p>
                </div>
                <div class="weui-cell__ft"><input type="number" placeholder="提现金额必须大于一元" value="" class="cells-name cells-money"></div>
            </div>

        </div>
        <div class="button_sp_area" style="margin-bottom: 20px;">
            <a href="javascript:;" class="weui-btn weui-btn_mini weui-btn_default default-but">取消</a>
            <a href="javascript:;" class="weui-btn weui-btn_mini weui-btn_primary primary-but">确认</a>
        </div>
    </div>
<script>
    $(document).ready(
        function () {
            var unionid=getUrlParam("unionId");
            var priceRealHistory=getUrlParam("priceRealHistory");
            var priceReals=getUrlParam("priceReal");
            var priceCoupon=getUrlParam("priceCoupon");
            $("#priceRealHistory").html(""+priceRealHistory+"");
            $("#priceReal").html(""+priceReals+"");
            $("#priceCoupon").html(""+priceCoupon+"");
            mains();
            $.ajax({
                type:"get",
                url:"/task/my-income?unionId="+unionid,
                dataType:"json",
                success:function (data) {
                    var info=eval(data);
                    if(info.code==0){
                        var data=info.data;
                        var lists=data.list;
                        var length=lists.length;
                        for(i=0;i<length;i++){
                            var html='';
                            html+='<div class="task-list"><h4>'+lists[i].taskName+'</h4><ul><li>任务类别：<span>'+lists[i].taskType+'</span></li> <li>开始时间：<span>'+lists[i].startTime+'</span></li>';
                            html+=' <li>结束时间：<span>'+lists[i].endTime+'</span></li><li>任务收益：<span>'+lists[i].priceReal+'元</span></li></ul></div>';
                            $("#tab1").append(html);
                        }
                    }

                }
            });


        });
    $(".mention-moneys").on("click",function (){
        var priceReals=getUrlParam("priceReal");
        if(parseInt(priceReals)>1) {
            $(".mention-cells").show();
        }else{
            $.alert("可提现金额小于1元");

        }
    });
    $(".default-but").on("click",function () {
        $(".mention-cells").hide();
    });
    $(".primary-but").on("click",function () {
        var unionid=getUrlParam("unionId");
        var priceReal=getUrlParam("priceReal");
        var price=$(".cells-money").val();
        var name=$(".realname").val();
        if(name == ''){
            $.alert("姓名不能为空")

        }else if(price == ''){
            $.alert("金额不能为空")
        }else if(parseInt(price)<1){
            $.alert("提现金额不能小于一元")
        }else if(parseInt(price)>20000){
            $.alert("每笔提现申请金额不能大于20000元")
        } else{
            $.ajax({
                type:"post",
                url:"/withdraw/apply-withdrew",
                dataType:"json",
                data:{
                    unionId:unionid,
                    priceReal:price,
                    name:name

                },
                success:function (data) {
                    if(data.code==0){
                        var moneys=priceReal-price;
                        $("#priceReal").html(""+moneys+"");
                        $(".mention-cells").hide();
                        $(".taskList").remove();
                        mains();
                        $.alert(""+data.message+"") ;

                    }else{
                        $.alert(""+data.message+"") ;
                    }
                }

            })
        }




    });
function mains() {
    var unionid=getUrlParam("unionId");
    $.ajax({
        type:"get",
        url:"/withdraw/my-withdraw?unionId="+unionid,
        dataType:"json",
        success:function (data) {
            var info=eval(data);
            if(info.code==0){
                var data=info.data;
                var lists=data.list;
                var length=lists.length;
                for(i=0;i<length;i++){
                    if(lists[i].status==0){
                        var html='';
                        html+='<div class="task-list taskList"><ul> <li>提现时间：<span>'+lists[i].created+'</span></li>';
                        html+=' <li>提现金额：<span>'+lists[i].priceReal+'元</span></li> <li>提现金额：<span>未处理</span></li></ul></div>';
                        $("#tab2").append(html);
                    }else if(lists[i].status==1){
                        var html='';
                        html+='<div class="task-list taskList"><ul> <li>提现时间：<span>'+lists[i].created+'</span></li>';
                        html+=' <li>提现金额：<span>'+lists[i].priceReal+'元</span></li> <li>提现金额：<span>通过</span></li></ul></div>';
                        $("#tab2").append(html);
                    }else if(lists[i].status==-1){
                        var html='';
                        html+='<div class="task-list taskList"><ul> <li>提现时间：<span>'+lists[i].created+'</span></li>';
                        html+=' <li>提现金额：<span>'+lists[i].priceReal+'元</span></li> <li>提现金额：<span>拒绝</span></li></ul></div>';
                        $("#tab2").append(html);
                    }else if(lists[i].status==2){
                        var html='';
                        html+='<div class="task-list taskList"><ul> <li>提现时间：<span>'+lists[i].created+'</span></li>';
                        html+=' <li>提现金额：<span>'+lists[i].priceReal+'元</span></li> <li>提现金额：<span>成功</span></li></ul></div>';
                        $("#tab2").append(html);
                    }else if(lists[i].status==-2){
                        var html='';
                        html+='<div class="task-list taskList"><ul> <li>提现时间：<span>'+lists[i].created+'</span></li>';
                        html+=' <li>提现金额：<span>'+lists[i].priceReal+'元</span></li> <li>提现金额：<span>失败</span></li></ul></div>';
                        $("#tab2").append(html);
                    }
                }
            }

        }
    });

}

</script>
