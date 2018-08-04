<?php
$this->title = '直播管理';
?>
<style>
    #profileButton1 {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        width: 200px;
    }

    .cover-img img {
        width: 100%;
        height: 100%;
    }

    input::-webkit-input-placeholder {
        color: #D9D9D9;
        font-size: 12px;
        text-align: left;
        line-height: 17px;
    }
</style>
<div class="s-gift-manage">
    <div class="s-gift-manage_title">直播管理</div>
    <a class="s-gift-manage_back" href="/live/live-img">返回</a>
    <div class="s-robot-form">
        <form action="" method="post" enctype="multipart/form-data" id="giftForm">
            <div class="s-robot-form_upload">
                <div class="s-robot-form_selectimg">
                    <div class="s-robot-form_selectimg-icon1"></div>
                    <div class="s-robot-form_selectimg-icon2"></div>
                </div>
                <input type="file" name="imgSrc" id="profileButton1" onchange="changepic()">
                <div class="s-robot-form_headimg-close" style="display: none;"></div>
                <div class="s-robot-form_img-tips">
                    <p>图片格式：JPG、JPEG、PNG</p>
                    <p>图片大小：小于1M</p>
                </div>
            </div>
            <div class="s-robot-form-details">
                <p class="c-form_item">
                    <span class="c-form_item-title">图片名称：</span>
                    <input class="c-input c-form_item-input" placeholder="0-10个字符长度" name="name" id="name"
                           maxlength="10" autocomplete="off"/>
                </p>
            </div>
        </form>
        <div>
            <button class="c-btn" id="confirm">确定</button>
            <button class="c-btn" id="cancel">取消</button>
        </div>
    </div>
</div>
<!--提示框-->
<div id="tip_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text"></span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //图片名称聚焦与失去焦点
    $("#name").focus(function () {
        $(this).attr("placeholder", "")
    }).blur(function () {
        $(this).attr("placeholder", "0-10个字符长度")
    });

    $("#confirm").unbind('click').bind('click', function () {
        var headImg = $("#headImg").attr("src");
        if (headImg == '' || headImg == undefined || headImg == null) {
            tip("请选择上传图片");
            event.preventDefault();
            return;
        }
        //判断输入的图片名称
        var name = $("#name").val();
        if (name == '' || name == undefined || name == null) {
            tip("请输入正确的图片名称");
            return;
        }
        if (name.length > 10) {
            tip("图片名称10个字符");
            return;
        }
        $("#giftForm").attr('action', '/live/add-img');
        $("#giftForm").submit();
    });
    $("#cancel").unbind('click').bind('click', function () {
        window.location.href = '/live/live-img';
    });

    function getPath(fileQuery, headImg) {
        var imgSrc = '', imgArr = [], strSrc = '';
        var file = fileQuery.files[0];
        var reader = new FileReader();
        if (headImg != undefined && file == undefined) {
            $("#headImg").attr("src", headImg);
            return;
        }
        else {
            if (file.size >= 1024 * 1024) {
                tip("上传的图片大于1M");
            } else {
                // 在这里需要判断当前所有文件中
                var fileExt = file.name.substr(file.name.lastIndexOf(".")).toLowerCase();//获得文件后缀名
                if (fileExt == ".png" || fileExt == ".jpg" || fileExt == ".jpeg") {
                    reader.onload = function (e) {
                        imgSrc = fileQuery.value;
                        imgArr = imgSrc.split('.');
                        strSrc = imgArr[imgArr.length - 1].toLowerCase();

                        var str = '<img src="" alt="图片" name="imgSrc" id="headImg" class="s-headimg-size"/>';
                        $(".s-robot-form_selectimg").html(str);
                        var file_img = document.getElementById("headImg");
                        file_img.setAttribute("src", e.target.result);
                        // $("#profileButton1").attr("disabled",true);
                    };
                    reader.readAsDataURL(file);
                } else {
                    if(headImg != undefined){
                        $("#headImg").attr("src", headImg);
                    }
                    else{
                        uploadHeadImg();
                    }
                    tip("图片格式只能为：JPG、JPEG、PNG");
                }
            }
        }
    }

    function changepic() {
        $(".s-robot-form_headimg-close").css('display', 'block');
        var headImg = $("#headImg").attr("src");
        var iptfileupload = document.getElementById('profileButton1');
        getPath(iptfileupload, headImg);
    }

    $(".s-robot-form_headimg-close").unbind('click').bind("click", function () {
        uploadHeadImg();
    });

    //上传头像控件
    function uploadHeadImg() {

        $(".s-robot-form_headimg-close").css("display", "none");
        var str = '';
        str += '<div class="s-robot-form_selectimg-icon1"></div>';
        str += '<div class="s-robot-form_selectimg-icon2"></div>';
        $(".s-robot-form_selectimg").html(str);
        $("#profileButton1").removeAttr("disabled");
        $("#profileButton1").val("");
    }

    //提示框
    function tip(message) {
        $("#tip_frame").css("display", "block");
        $(".s-banlive-confirm-text").text(message);
        $(".s-banlive-confirm").unbind('click').bind('click', function () {
            $("#tip_frame").css("display", "none");
        });
    }
</script>




