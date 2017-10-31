<?php
use yii\widgets\LinkPager;

$this->title = '轮播图添加';
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
</style>
<script type="text/javascript" src="/js/detaile.js"></script>
<form action="/index/banner-add" method="post" enctype="multipart/form-data">
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

                        <input type="file" name="avatar" id="profileButton1" onchange="changepic()">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><p>温馨提示:封面图大小按16:9上传(640*400)</p></td>
        </tr>
        <tr>
            <td>链接地址</td>
            <td><input class="input_list" type="text" name="url"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="提交" class="submit_but"></td>
        </tr>
    </table>
</form>


