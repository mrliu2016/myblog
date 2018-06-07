<?php
use yii\widgets\LinkPager;

$this->title = '创建礼物';
?>
<style type="text/css">
    tr td {
        padding-top: 20px;
        padding-left: 30px;
    }

    .input_list, .form-control {
        width: 200px;
        height: 40px;
        border: 1px solid #428bca;
        border-radius: 5px;

    }

    .input_list1 {
        width: 200px;
        height: 40px;
        border-top: 1px solid #428bca;
        border-left: 1px solid #428bca;
        border-right: 1px solid #428bca;
        border-radius: 5px;

    }

    .submit_but {
        width: 150px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 10px;
        background-color: #428bca;
        color: #fff;
        border: 1px solid #428bca;
        margin-bottom: 100px;
    }

    .pull_right_up {
        position: relative;
        margin-top: 30px;
        margin-left: 50px;
    }

    .profileButton1 {
        width: 90px;
        height: 30px;
        background-color: #337ab7;
        border-radius: 10px;
        color: #fff;
        line-height: 30px;
        display: inline-block;
        text-align: center;
    }

    #profileButton1 {
        position: absolute;
        width: 90px;
        height: 30px;
        opacity: 0;
        top: 0;
    }

    #selectImg1 {
        width: 200px;
        height: 200px;
    }

    #serverSelect {
        position: absolute;
        top: 58px;
        background-color: #fff;
        border: 1px solid #428bca;
        border-radius: 5px;
    }

    ul li {
        list-style: none;
    }

    .hidden-list {
        display: none;
    }

    #show-hid {
        display: none;
    }

    .hidden-list-1 {
        padding: 5px 20px;
        display: inline-block;
        background-color: #0d47a1;
        border-radius: 5px;
        color: #fff;
    }

</style>
<script type="text/javascript" src="/js/detaile.js"></script>
<form action="/gift/create" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>图片</td>
            <td>
                <div class="profile_pic_left clearfix pull-left">
                    <div class=" pull_left_img">
                        <img src="" class="img-rounded" id="selectImg1"
                             onerror="javascript:this.src='/img/nopic3.jpg';">
                    </div>
                    <div class=" pull_right_up">
                        <span class="profileButton1">选择文件</span>

                        <input type="file" name="imgSrc" id="profileButton1" onchange="changepic()">
                    </div>
                </div>

        </tr>
        <tr>
            <td>礼物名称*:</td>
            <td><input class="input_list" type="text" name="name"></td>
        </tr>
        <tr>
            <td>价格</td>
            <td><input class="input_list" type="text" name="price"></td>
        </tr>
        <tr>
            <td>是否连发</td>
            <td>
                <input type="radio" value="1" name="fire">是
                <input type="radio" value="0" name="fire" checked>否</td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="提交" class="submitBtn"></td>
        </tr>
    </table>
</form>



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
            <span class="c-form_item-title">礼物名称：</span>
            <span class="s-robot-form_receive-wrap">
					<input class="c-input c-form_item-input s-robot-form_receive" id="name"/>
				</span>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">价格：</span>
            <span class="s-robot-form_give-wrap">
					<input class="c-input c-form_item-input s-robot-form_give" id="price"/>
				</span>
        </p>
        <p class="c-form_item">
            <span class="c-form_item-title">是否连发：</span>
            <span class="s-robot-form_give-wrap">
					<input type="radio" value="1" name="fire">是
                <input type="radio" value="0" name="fire" checked>否</td>
				</span>
        </p>
    </div>
    <div>
        <button class="c-btn" id="confirm">确定</button>
        <button class="c-btn">取消</button>
    </div>
</div>



<script type="text/javascript">
    $("#show-list").on("click", function () {
        $(".hidden-list").show();
        $("#show-list").hide();
        $("#show-hid").show();
    });
    $("#show-hid").on("click", function () {
        $(".hidden-list").hide();
        $("#show-list").show();
        $("#show-hid").hide();
    });

    // $(".submitBtn").click(function () {
    //     alert(111);
    // });

</script>




