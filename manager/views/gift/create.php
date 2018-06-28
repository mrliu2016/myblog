<?php
$this->title = '礼物管理';
?>
<style>
    #profileButton1{
        position: absolute;
        top:0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        width:200px;
    }
    .cover-img img{
        width: 100%;
        height: 100%;
    }
</style>
<div class="s-gift-manage">
    <div class="s-gift-manage_title">礼物管理</div>
    <a class="s-gift-manage_back" href="/gift/index">返回</a>
    <div class="s-robot-form">
        <form action="" method="post" enctype="multipart/form-data" id="giftForm">
            <div class="s-robot-form_upload">
                <div class="s-robot-form_selectimg">
                    <div class="s-robot-form_selectimg-icon1"></div>
                    <img src="" class="s-robot-form_selectimg-icon2" id="selectImg1">
                    <input type="file" name="imgSrc" id="profileButton1" onchange="changepic()">
                </div>
                <img class="s-robot-form_head-img" src="http://3tdoc.oss-cn-beijing.aliyuncs.com/img/2018/05/11/13/1835_6351.png" alt="用户头像" name="img" id="headImg">
                <div class="s-robot-form_headimg-close" style="display: none;"></div>

                <div class="s-robot-form_img-tips">
                    <p>图片格式：JPG、JPEG、PNG</p>
                    <p>图片大小：小于一M</p>
                </div>
            </div>
            <div class="s-robot-form-details">
                <p class="c-form_item">
                    <span class="c-form_item-title">礼物名称：</span>
                    <input class="c-input c-form_item-input" placeholder="0-10个字符长度" name="name" id="name" maxlength="10" autocomplete="off"/>
                </p>
                <p class="c-form_item">
                    <span class="c-form_item-title">价格：</span>
                    <span class="s-robot-form_give-wrap">
                        <input class="c-input c-form_item-input s-robot-form_give" name="price" id="price" autocomplete="off"/>
                    </span>
                </p>
                <p class="c-form_item">
                    <span class="c-form_item-title">是否连发：</span>
                    <input type="radio" value="1" name="fire">是
                    <input type="radio" value="0" name="fire" checked>否
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
    $("#confirm").unbind('click').bind('click',function () {
        var fileEl = $('#profileButton1');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            tip("请选择头像");
            event.preventDefault();
            return;
        }
        //判断输入的礼物名称
        var name = $("#name").val();
        if(name == '' || name == undefined || name == null){
            tip("请输入正确的礼物名称");
            return;
        }
        if(name.length>10){
            tip("礼物名称10个字符");
            return;
        }
        var price = $("#price").val();
        if(price == '' || price == undefined || price == null){
            tip("请输入礼物价格");
            return;
        }
        $("#giftForm").attr('action','/gift/create');
        $("#giftForm").submit();
    });
    $("#cancel").unbind('click').bind('click',function () {
        $("#name").val("");
        $("#price").val("");
        $("input[name=fire]:eq(1)").attr("checked",'checked');
    });
    function getPath(obj, fileQuery, transImg) {
        var imgSrc = '', imgArr = [], strSrc = '';
        var file = fileQuery.files[0];
        var reader = new FileReader();
        if (file.size >=1024*1024) {
            // $(".delect-check").click();
            tip("上传的图片大于1M");
        } else {
            // 在这里需要判断当前所有文件中
            var fileExt = file.name.substr(file.name.lastIndexOf(".")).toLowerCase();//获得文件后缀名
            if (fileExt == ".png"|| fileExt == ".jpg" || fileExt == ".jpeg") {
                reader.onload = function (e) {
                    imgSrc = fileQuery.value;
                    imgArr = imgSrc.split('.');
                    strSrc = imgArr[imgArr.length - 1].toLowerCase();
                    obj.setAttribute("src", e.target.result);

                };
                reader.readAsDataURL(file);
            } else {
                tip("图片格式只能为：JPG、JPEG、PNG");
            }
        }
    }
    function changepic() {
        var file_img = document.getElementById("headImg");
        $(".s-robot-form_headimg-close").css('display','block');
        var iptfileupload = document.getElementById('profileButton1');
        getPath(file_img, iptfileupload, file_img);
        $(".profileButton1").css("font-size", "0px");
    }

    $(".s-robot-form_headimg-close").unbind('click').bind("click",function(){
        $("#profileButton1").outerHTML = $("#profileButton1").outerHTML;
        $("#headImg").attr('src','http://3tdoc.oss-cn-beijing.aliyuncs.com/img/2018/05/11/13/1835_6351.png');
        $(".s-robot-form_headimg-close").css("display","none")
    })

    //提示框
    function tip(message){
        $("#tip_frame").css("display","block");
        $(".s-banlive-confirm-text").text(message);
        $(".s-banlive-confirm").unbind('click').bind('click',function () {
            $("#tip_frame").css("display","none");
        });
    }
</script>




