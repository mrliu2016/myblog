<?php
$this->title = '机器人管理';
?>

<div class="s-robot-form">
   <!-- <div class="s-robot-form_title">用户详情</div>-->
    <div class="s-robot-form_upload">
        <div class="s-robot-form_selectimg">
            <div class="s-robot-form_selectimg-icon1"></div>
            <div class="s-robot-form_selectimg-icon2"></div>
        </div>
        <img class="s-robot-form_headimg" src="http://3tdoc.oss-cn-beijing.aliyuncs.com/img/2018/05/11/13/1835_6351.png" alt="用户头像">
        <div class="s-robot-form_img-tips">
            <p>图片格式：JPG、JPEG、PNG</p>
            <p>图片大小：小于一M</p>
        </div>
    </div>
    <div class="s-robot-form-details">
        <p class="c-form_item">
            <span class="c-form_item-title">ID：</span>
            <input type="hidden" value="<?=$id?>" id="id">
            <?=$id?>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">昵称：</span>
            <input class="c-input c-form_item-input" placeholder="0-10个字符长度" id="nickName"/>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">性别：</span>
            <span class="c-select-wrap">
					<select class="c-select c-form_item-input" default="0" id="sex">
						<option value="1">男</option>
						<option value="0">女</option>
					</select>
				</span>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">所在地：</span>
            <span class="c-select-wrap">
					<select class="c-select u-radius--0 s-robot-form_address-select" name="bursts" default="0" id="province">
						<option value="0">未知星球</option>
                        <option value="1">北京市</option>
                        <option value="2">河北</option>
					</select>
				</span>
            -
            <span class="c-select-wrap">
					<select class="c-select u-radius--0 s-robot-form_address-select" name="bursts" default="0" id="city">
						<option value="0">未知星球</option>
                        <option value="1">北京市</option>
                        <option value="2">邢台市</option>
					</select>
				</span>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">关注数：</span>
            <input class="c-input c-form_item-input" id="followees_cnt"/>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">粉丝数：</span>
            <input class="c-input c-form_item-input" id="followers_cnt"/>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">收到礼物：</span>
            <span class="s-robot-form_receive-wrap">
					<input class="c-input c-form_item-input s-robot-form_receive" id="receivedGift"/>
				</span>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">送出礼物：</span>
            <span class="s-robot-form_give-wrap">
					<input class="c-input c-form_item-input s-robot-form_give" id="sendGift"/>
				</span>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">个性签名：</span>
            <!--<input class="c-input c-form_item-input s-robot-form_signature" />-->
            <select id="description" class="c-input c-form_item-input s-robot-form_signature" >
                <option>这个人太忙，没有留下签名！</option>
                <option>我的名字什么时候是你拒绝别人的理由！</option>
                <option>撩是忽冷忽热 追是认真且怂！</option>
                <option>我很有趣.是值得你过一辈子的人！</option>
                <option>最初不相识，最终不相认！</option>
                <option>闭上眼睛，我看到了我的前途......</option>
                <option>我有一生时间 半生记你 半生忘你！</option>
                <option>我怕我每个眼神都像在表白!</option>
                <option>你好吗 好久不见 后来的你 喜欢了谁？</option>
                <option>怕鬼就是太幼稚了，我带你去看看人心...</option>
            </select>
        </p>
    </div>
    <div class="s-robot-form_btns">
        <button class="c-btn s-robot-form_confirm" id="confirm">确定</button>
        <button class="c-btn s-robot-form_cancel">取消</button>
    </div>
</div>

<script>
    $("#confirm").click(function(){
        var id = $("#id").val();
        var nickName = $("#nickName").val();
        var sex = $("#sex").val();
        var province = $("#province").val();
        var city = $("#city").val();
        var followees_cnt = $("#followees_cnt").val();
        var followers_cnt = $("#followers_cnt").val();
        var receivedGift = $("#receivedGift").val();
        var sendGift = $("#sendGift").val();
        var description = $("#description").val();

        // if(name == undefined || name == '' || name == null || name.length >5){
        //     alert('请输入正确的礼物名称.');
        //     return false;
        // }
        // if(parseFloat(price).toString() == "NaN"){
        //     alert('请输入正确的礼物价格.');
        //     return false;
        // }

        var params  = {};
        params.id = id;
        params.nickName  = nickName;
        params.sex  = sex;
        params.province = province;
        params.city = city;
        params.followees_cnt = followees_cnt;
        params.followers_cnt = followers_cnt;
        params.receivedGift = receivedGift;
        params.sendGift = sendGift;
        params.description = description;

        $.ajax({
            url: "/robot/edit-robot",
            type: "post",
            data: params,
            dataType: "json",
            success: function (data) {
                if(data != undefined && data.code == 0){
                    window.location.href='/robot/list';
                }
                else{
                    alert('编辑失败！');
                }
            }
        });
    });
    $(".cancel").click(function () {
        $("#name").val("");
        $("#price").val("");
        $("input[name=fire]:eq(1)").attr("checked",'checked');
    });
</script>


