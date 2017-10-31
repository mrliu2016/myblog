<?php
$this->title = '个人中心';
?>
<style>
    .weixin-thumb {
        border-radius: 30px;
    }

    .person-top {
        background-color: #f7ee07;
    }

    .persosn-title {
        margin-top: 0px;
    }

    p {
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .weui-tabbar p {
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .weui-footer_fixed-bottom {
        bottom: 0;
    }
    .tiyanjin a{
        color: red;
    }
</style>
<div class="weui-panel weui-panel_access persosn-title">
    <div class="weui-panel__bd person-top">
        <div class="weui-media-box weui-media-box_appmsg">
            <div class="weui-media-box__hd">
                <img class="weui-media-box__thumb weixin-thumb" src="<?= $user['avatar'] ?>">
            </div>
            <div class="weui-media-box__bd">
                <h4 class="weui-media-box__title"> <?= $user['nickName'] ?></h4>
                <!--<p><a href="/h5/usecoupon">待使用启动资金：<?= $user['priceCoupon'] ?>元</a></p>-->
                <p><a href="/h5/instructions">如何使用启动金？</a></p>
                <input type="hidden" value="<?= $user['couponAvailable'] ?>" id="coupon">
             <p class="tiyanjin"><a href="#" class="nextcoupon">你还有<?= $user['couponAvailable'] ?>元新人体验金未领取</a></p>
                <input type="hidden" id="priceRealHistory" value="<?= $priceRealHistory ?>">
                <input type="hidden" id="priceReal" value=" <?= $user["priceReal"] ?> ">
                <input type="hidden" id="priceCoupon" value=" <?= $user['priceCoupon'] ?> ">
                <input type="hidden" id="taskFqCount" value=" <?= $taskFqCount ?> ">
                <input type="hidden" id="taskCyCount" value=" <?= $taskCyCount ?> ">

            </div>
        </div>
    </div>
</div>
<div class="weui-cells">

    <a class="weui-cell weui-cell_access mymention" href="">
        <div class="weui-cell__hd"><img
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII="
                alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui-cell__bd">
            <p>我的收益</p>
        </div>
        <div class="weui-cell__ft" ></div>
    </a>
    <a class="weui-cell weui-cell_access myjoin" href="/h5/my-join">
        <div class="weui-cell__hd"><img
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII="
                alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui-cell__bd">
            <p>我参与的任务</p>
        </div>
        <div class="weui-cell__ft cycoupon"></div>
    </a>
    <a class="weui-cell weui-cell_access initiating" href="/h5/initiatingtask">
        <div class="weui-cell__hd"><img
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII="
                alt="" style="width:20px;margin-right:5px;display:block"></div>
        <div class="weui-cell__bd">
            <p>我发起的任务</p>
        </div>
        <div class="weui-cell__ft fqcoupon"></div>
    </a>
    <!-- <a class="weui-cell weui-cell_access" href="javascript:;">
         <div class="weui-cell__hd"><img
                 src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII="
                 alt="" style="width:20px;margin-right:5px;display:block"></div>
         <div class="weui-cell__bd">
             <p>我的课程</p>
         </div>
         <div class="weui-cell__ft"></div>
     </a>-->
</div>
<div class="weui-tabbar weui-footer_fixed-bottom">
    <a href="/h5/index" class="weui-tabbar__item ">

        <p class="weui-tabbar__label">首页</p>
    </a>

    <!--<a href="/user/info?state=--flag__2" class="weui-tabbar__item">

        <p class="weui-tabbar__label">任务</p>
    </a>-->
    <a href="/h5/price-real-top" class="weui-tabbar__item">

        <p class="weui-tabbar__label">排行榜</p>
    </a>
    <a href="/user/info?state=--flag__1" class="weui-tabbar__item weui-bar__item--on">

        <p class="weui-tabbar__label">我的</p>
    </a>
</div>
<script>
    $(document).ready(

        function () {
            var unionid = getUrlParam("unionid");
            var type=$("#coupon").val();
            if(type==0){
                $(".tiyanjin").hide();
            }else{
               $(".nextcoupon").attr("href","/h5/coupon?unionId=" + unionid);
            };

            var priceCoupon = $("#priceCoupon").val();
            var priceRealHistory = $("#priceRealHistory").val();
            var priceReal = $("#priceReal").val();
            var taskCyCount=$("#taskCyCount").val();
            var taskFqCount=$("#taskFqCount").val();
            $(".cycoupon").html(""+taskCyCount+"个");
            $(".fqcoupon").html(""+taskFqCount+"个");
            $('.mymention').attr('href', "/h5/mention?unionId=" + unionid + "&priceCoupon=" + priceCoupon + "&priceRealHistory=" + priceRealHistory + "&priceReal=" + priceReal);
            $(".myjoin").attr('href', "/h5/my-join?unionId=" + unionid);
            $(".initiating").attr('href', "/h5/initiatingtask?unionId=" + unionid);
        }
    )
</script>
