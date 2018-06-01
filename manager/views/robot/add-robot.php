<?php
$this->title = '新增机器人';
?>
<style>
    .add-robot{
        padding-left: 20px;
        padding-top: 10px;
    }
</style>
<div class="add-robot">
    <div class="img">
        <a href="/robot/list">返回</a>
    </div>
    <div>
        <table class="table table-hover">
            <tbody>
            <tr>
                <td>昵称*:</td>
                <td><input type="text" placeholder="0-10个字符长度" id="nickName"/></td>
            </tr>
            <tr>
                <td>性别:</td>
                <td>
                    <select id="sex">
                        <option value="0">女</option>
                        <option value="1">男</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>所在地：</td>
                <td>
                    <select id="province">
                        <option>未知星球</option>
                        <option>北京市</option>
                        <option>河北</option>
                    </select>
                    <select id="city">
                        <option>未知星球</option>
                        <option>北京市</option>
                        <option>邢台市</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>关注数*：</td>
                <td><input type="text" id="followees_cnt"></td>
            </tr>
            <tr>
                <td>粉丝数*：</td>
                <td><input type="text" id="followers_cnt"></td>
            </tr>
            <tr>
                <td>收到礼物*：</td>
                <td><input type="text" id="receivedGift">币</td>
            </tr>
            <tr>
                <td>送出礼物*：</td>
                <td><input type="text" id="sendGift">豆</td>
            </tr>
            <tr>
                <td>个性签名：</td>
                <td>
                    <select id="description">
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
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="button" value="确定" class="confirm">
                    <input type="button" value="取消" class="cancel">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(".confirm").click(function(){
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
            url: "/robot/add-submit",
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


