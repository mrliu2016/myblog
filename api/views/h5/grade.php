<?php
$this->title = '评分';
?>
<style>
    .row {
        margin-left: 0px;
        margin-right: 0px;
    }

    .weui-flex-left {
        width: 100%;
    }

    /* .weui-flex-right{
         width: 35%;
     }*/
</style>
<div class="main">
    <div class="solider-bodys">
        <div class="height-line"></div>

        <div class="solider-main">
            <div class="solider-text">
                <p>拖动分数滑块，给文章打分</p>
                <p>即可领取启动金发起评分任务挣钱</p>
                <p>已有<span><?= $number ?></span>人为该文章评分</p>
            </div>
            <div class="weui-slider-box solider-box" id='slider2'>
                <div id="sliderValue" class="weui-slider-box__value slider-value">75</div>
                <div class="weui-slider solider-inner">
                    <div id="sliderInner" class="weui-slider__inner">
                        <div id="sliderTrack" style="width: 75%;" class="weui-slider__track"></div>
                        <div id="sliderHandler" style="left: 75%;" class="weui-slider__handler"></div>
                    </div>
                </div>

            </div>
            <div class="solider-button">
                <a href="javascript:;" class="weui-btn weui-btn_primary solider-but">提交</a>
            </div>
        </div>
        <div class="row intro">
            <div class="col-xs-10 col-xs-offset-1 ">
                <?php if (!empty($couponList)): ?>
                    <?php foreach ($couponList as $coupon): ?>
                        <div class="weui-flex">
                            <div class="weui-flex_item weui-flex-left">
                                <div class="placeholder col-xs-12">启动金<?= $coupon['price'] / 100 ?> 元</div>
                            </div>
                            <!-- <div class=" weui-flex-right">
                                 <a class="placeholder-but col-xs-12" href="/h5/personal-center">点击领取</a>
                             </div>-->
                        </div>
                    <?php endforeach; ?>
                    <a href="/user/info?state=--flag__1" class="weui-btn weui-btn_primary receive-but">立即领取启动资金</a>
                <?php endif; ?>

                <!--<a href="/h5/receive" class="weui-btn weui-btn_primary receive-but">立即领取启动资金</a>-->
                <div class="intro_main">
                    <p style="margin-bottom: 20px;">说明：</p>
                    <h5>谁说吃瓜群众只能围观？</h5>
                    <p>这篇文章写得好不好，你来打个分！</p>
                </div>
                <div class="intro_main">
                    <h5>谁说吃瓜群众没钱赚？</h5>
                    <p>即刻领取200元新人现金抵用券</p>
                    <p>存入您的个人账户</p>
                    <p>第一桶金就这么到手了！</p>
                </div>
                <div class="intro_main">
                    <h5>同时您还晋升为CAO(首席评价官)了！</h5>
                    <p>CAO虽不是CEO，但权力可不小</p>
                    <p>您的评分决定了作者的前（钱）途！</p>
                </div>
                <div class="intro_main">
                    <h5>做CAO有什么好处？</h5>
                    <p>每天看文章，打打分</p>
                    <p>消磨时光又增长见闻</p>
                    <p>还躺着把钱挣了！</p>
                </div>
                <div class="intro_main">
                    <h5>到底能挣多少钱啊？</h5>
                    <p>这么说吧</p>
                    <p>用你个人账户的200元发起一个打分任务</p>
                    <p>邀请认识或不认识的好友参加你的任务</p>
                    <p>只要每天坚持，就能轻松拿到数倍奖金</p>
                    <p>不赚钱只有一种可能，就是——</p>
                    <p>你没有坚持做任务</p>
                    <p>点击<a href="/h5/rules">《新手攻略》</a>查看具体规则</p>
                </div>
                <div class="intro_main">
                    <h5>如何领取和使用现金抵用券？</h5>
                    <p>第一步：点击“确定”给文章打分</p>
                    <p>第二步：打分完成后点击“领取”按钮</p>
                    <p>第三步：在分播个人账户查看确认到账</p>
                    <p>第四步：立即发起一个评分任务</p>
                </div>

            </div>
        </div>
    </div>

    <div class="weui-cells">
        <h5 style="text-align: center">今日TOP10收入排名</h5>
        <div class="weui-cell">
            <div class="weui-cell__hd cell-list">
                <p style="width: 30px;">1</p>
            </div>
            <div class="weui-cell__hd "><img src="/img/thumb.png" class="thumb"></div>
            <div class="weui-cell__bd cell-list-center">说明文字</div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd cell-list">
                <p style="width: 30px;">2</p>
            </div>
            <div class="weui-cell__hd "><img src="/img/thumb.png" class="thumb"></div>
            <div class="weui-cell__bd cell-list-center">说明文字</div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd cell-list">
                <p style="width: 30px;">3</p>
            </div>
            <div class="weui-cell__hd "><img src="/img/thumb.png" class="thumb"></div>
            <div class="weui-cell__bd cell-list-center">说明文字</div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd cell-list">
                <p style="width: 30px;">4</p>
            </div>
            <div class="weui-cell__hd "><img src="/img/thumb.png" class="thumb"></div>
            <div class="weui-cell__bd cell-list-center">说明文字</div>
        </div>

    </div>
</div>
<div class="weui-msg" style="display: none">
    <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">打分成功</h2>
    </div>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a href="/user/info?state=--flag__1" class="weui-btn weui-btn_primary task-next">下一步</a>
        </p>
    </div>

</div>
<script>
    $(function () {
        FastClick.attach(document.body);
    });
    $('#slider2').slider(function (per) {
        //console.log(per);
    });
    $(".solider-but").on("click",function () {
        var point =$("#sliderValue").html();
        var unionId=getUrlParam("unionid");
        var aid=getUrlParam("aid");
        var tid=getUrlParam("tid");
        $.ajax({
            type: "post",
            url: '/task/article-grade',
            dadatype: "json",
            data: {
                unionId: unionId,
                aid: aid,
                tid: tid,
                point:point

            },
            success: function (res) {
            $(".main").hide();
            $(".weui-msg").show();
            }
        })

    })
</script>
