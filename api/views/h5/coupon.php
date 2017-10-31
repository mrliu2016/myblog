<?php
$this->title = '新人体验金';
?>
<style>
    .container{
        background:#f7ee07;
        height: 100%;

    }
    .weui-flex-left{
        width: 95%;
    }

    .main{
        margin-top:30px;
    }
    .receive-but{
        margin-top: 10px;
        margin-bottom: 50px;
    }
</style>
<div class="container">
    <div class="row main">
        <div class="col-xs-10 col-xs-offset-1 couponlist">

        </div>


    </div>
    <a href="javascript:;" class="weui-btn weui-btn_primary receive-but">领取体验金</a>
</div>
<script>
    $(document).ready(function () {
        var unionid = getUrlParam("unionId");
        $.ajax({
            type:"get",
            dataType:"json",
            url:"/coupon/available?unionid="+unionid,
            success:function (data) {
                if(data.code==0){
                    var info=eval(data);
                    var data=info.data;
                    var length=data.length;
                    for(i=0;i<length;i++){
                        var html='';
                        html+='';
                        html+=' <div class="weui-flex"><div class="weui-flex_item weui-flex-left col-md-12"><div class="placeholder col-xs-12">体验金'+data[i].price+'元</div>';
                        html+=' </div></div>';
                        $(".couponlist").append(html);
                    }
                }


            }
        })

    });
    $(".receive-but").on("click",function () {
        var unionid = getUrlParam("unionId");
        $.ajax({
            type:"get",
            dataType:"json",
            url:"/coupon/send?unionid="+unionid,
            success:function (data) {
           $.alert(领取成功);


            }
        })
    })
</script>
