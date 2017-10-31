<?php
$this->title = '支付契约金';
?>
<style>
    p{
        margin-bottom:0;
    }
    .footer{
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }
    #ces{
        padding: 10px;
        width: 300px;
        word-wrap:break-word;
        height: 10em;
    }
    #ces1{
        padding: 10px;
        width: 300px;
        word-wrap:break-word;
        height: 10em;
    }
    .weui-agree{
        display: inline-block;
        padding:.5em 5px;
    }
</style>
<div class="container">
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>任务团名称</p>
            </div>
            <div class="weui-cell__ft task-name"></div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>任务周期</p>
            </div>
            <div class="weui-cell__ft pay-time">2017.09.10至2017.10.30</div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>契约金</p>
            </div>
            <div class="weui-cell__ft price-pay">0元</div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>支付方式</p>
            </div>

        </div>
    </div>
    <div class="weui-cells weui-cells_radio">
        <label class="weui-cell weui-check__label" for="x12">

            <div class="weui-cell__bd">
                <p>微信现金支付</p>
            </div>
            <div class="weui-cell__ft">
                <input type="radio" name="radio1" class="weui-check" id="x12" checked="checked" value="1">
                <span class="weui-icon-checked"></span>
            </div>
        </label>

    </div>
    <label for="weuiAgree" class="weui-agree">
        <input id="weuiAgree" type="checkbox" class="weui-agree__checkbox">
        <span class="weui-agree__text">
        阅读并同意
      </span>
    </label><a href="/h5/agreement">《相关条款》</a>
    <div class="col-xs-10" id="ces">
    </div>
    <div class="footer">
        <a href="javascript:;" class="weui-btn weui-btn_primary" id="pay_money">立即支付</a>
        <div class="task-intro">
            <p style="text-align: center;line-height: 40px;">设置任务 > 支付契约金 > 邀请成员</p>
        </div>
    </div>


</div>
<div class="weui-msg" style="display: none">
    <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">支付成功</h2>
    </div>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a href="/h5/index" class="weui-btn weui-btn_primary task-next">我已经分享给好友</a>
        </p>
    </div>

</div>
<input type="hidden" class="title" value="<?= $title ?>">
<input type="hidden" class="link" value="<?= $link ?>">
<input type="hidden" class="imgurl" value="<?= $imgUrl ?>">
<input type="hidden" class="content" value="<?= $content ?>">
<script>
    var title =$(".title").val();
    var link=$(".link").val();
    var imgurl=$(".imgurl").val();
    var content=$(".content").val();
    window.shareData = {
        "imgUrl": imgurl,
        "timeLineLink": link,
        "sendFriendLink":link ,
        "weiboLink": link,
        "tTitle": title,
        "tContent": content
    };
</script>
<?= $share ?>
<script>
    $(document).ready(
        function (){
            var price=getUrlParam("price");
            $(".price-pay").html(""+price+"元");
            var taskid=getUrlParam("taskid");
            $.ajax({
                type:"get",
                url:'/task/task-detail?taskId='+taskid,
                dataType:"json",
                success:function (data) {
                    var info=eval(data);
                    if(info.code==0){
                        var data=info.data;
                        var start=data.startTime;
                        var end=data.endTime;
                        var name=data.name;
                        $(".pay-time").html(""+start+"至"+end+"");
                        $(".task-name").html(""+name+"");



                    }
                }

            })
        });
    var aid=getUrlParam("aid");
    var unionid=getUrlParam("unionId");
    var taskid=getUrlParam("taskid");
    var price=getUrlParam("price");
    $("#pay_money").on("click",function () {

        var type=$('input[type=radio]:checked').val();
        if($('#weuiAgree').is(':checked')) {
            $.ajax({
                type:"post",
                url:'/payment/wei-xin-js-pay',
                dadatype:"json",
                data:{
                    unionId:unionid,
                    price:price,
                    type:type,
                    taskId:taskid,
                    appId:aid

                },
                success:function(data){
                    if(type==1){
                        if(data.code==0){
                            var  orderId=data.data.orderId;
                            WeixinJSBridge.invoke(
                                'getBrandWCPayRequest',
                                $.parseJSON(data.data.prepayId),
                                function(res){

                                    if(res.err_msg == "get_brand_wcpay_request:ok"){
                                        $.ajax({
                                            type: "post",
                                            url: '/order-query/h5-order-query',
                                            dadatype: "json",
                                            data: {
                                                orderId:orderId,
                                                status:1

                                            },

                                        });
                                        $(".container").hide();
                                        $(".weui-msg").show();


                                    }else{

                                        $.ajax({
                                            type: "post",
                                            url: 'http://dev.api.wechat.3ttech.cn/order-query/h5-order-query',
                                            dadatype: "json",
                                            data: {
                                                orderId:orderId,
                                                status:-1

                                            },

                                        })
                                    }

                                }
                            );
                        }else{
                            $.alert(""+data.message+"");
                        }
                    }else{
                        if(data.code==0){
                            $(".container").hide();
                            $(".weui-msg").show();
                        }else{
                            $.alert(""+data.message+"");
                        }
                    }
                },

                faile:function (data) {
                    $("#ces").html("网络错误");
                }
            })
        }else{
            $.alert("请阅读并同意相关条款")
        }


    })
</script>

