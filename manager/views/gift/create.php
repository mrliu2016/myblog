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
<style type="text/css">
    body {
        font: 12px/1.5 Arial;
    }

    input {
        float: left;
        font-size: 12px;
        width: 150px;
        font-family: arial;
        padding: 3px;
        border: 1px solid black;
    }

    input.error {
        border: 1px solid red;
    }

    #tips {
        float: left;
        margin: 2px 0 0 20px;
    }

    #tips span {
        float: left;
        width: 50px;
        height: 20px;
        color: white;
        background: green;
        margin-right: 2px;
        line-height: 20px;
        text-align: center;
    }
</style>
<script type="text/javascript" src="/js/detaile.js"></script>
<form action="/gift/create" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>名字</td>
            <td style="position: relative">
                <input type="text" name="name" id="selectId" class=" input_list1"
                       autocomplete="off" style="width: 170px;">
            </td>
        </tr>
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
            <td>价钱</td>
            <td><input class="input_list" type="text" name="price"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="提交" class="submit_but"></td>
        </tr>
    </table>
</form>
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
    })

</script>




