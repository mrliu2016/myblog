<?php
$this->title = '创建礼物';
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

<div class="s-robot-form">
    <!-- <div class="s-robot-form_title">用户详情</div>-->
    <form action="" method="post" enctype="multipart/form-data" id="giftForm">
        <div class="s-robot-form_upload">
            <div class="s-robot-form_selectimg">
                <div class="s-robot-form_selectimg-icon1"></div>
                <!--<div class="s-robot-form_selectimg-icon2" id="uploadImg"></div>-->
                <!--<input type="file" class="s-robot-form_selectimg-icon2" name="imgSrc" id="uploadImg"/>-->
                <img src="" class="s-robot-form_selectimg-icon2" id="selectImg1">
                <input type="file" name="imgSrc" id="profileButton1" onchange="changepic()">
            </div>
           <!-- <input type="file" name="imgSrc" class="s-robot-form_selectimg-icon2"/>-->
            <img class="s-robot-form_headimg" src="http://3tdoc.oss-cn-beijing.aliyuncs.com/img/2018/05/11/13/1835_6351.png" alt="用户头像" name="img" id="headImg">
            <!--<button class="s-robot-form_headimg-close"></button>-->
            <div class="s-robot-form_headimg-close" style="display: none;"></div>

            <div class="s-robot-form_img-tips">
                <p>图片格式：JPG、JPEG、PNG</p>
                <p>图片大小：小于一M</p>
            </div>
        </div>
        <div class="s-robot-form-details">
            <p class="c-form_item">
                <span class="c-form_item-title">礼物名称：</span>
                <input class="c-input c-form_item-input" placeholder="0-10个字符长度" name="name" id="name"/>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">价格：</span>
                <span class="s-robot-form_give-wrap">
                        <input class="c-input c-form_item-input s-robot-form_give" name="price" id="price"/>
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


<script type="text/javascript">


    $("#confirm").unbind('click').bind('click',function () {

        var fileEl = $('#profileButton1');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            alert("请选择头像");
            event.preventDefault();
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

        if (file.size >=1048576) {
            $(".delect-check").click();
        } else {
            // 在这里需要判断当前所有文件中
            var fileExt = file.name.substr(file.name.lastIndexOf(".")).toLowerCase();//获得文件后缀名
            if (fileExt == ".png" || fileExt == ".gif" || fileExt == ".jpg" || fileExt == ".jpeg" ||fileExt == ".bmp") {
                reader.onload = function (e) {
                    imgSrc = fileQuery.value;
                    imgArr = imgSrc.split('.');
                    strSrc = imgArr[imgArr.length - 1].toLowerCase();
                    obj.setAttribute("src", e.target.result);

                };
                reader.readAsDataURL(file);

            } else {
                $(".showintro").click();
            }
        }
    }

    function changepic() {
        // var file_img = document.getElementById("selectImg1");
        var file_img = document.getElementById("headImg");

        console.log(file_img);
        $(".s-robot-form_headimg-close").css('display','block');
        var iptfileupload = document.getElementById('profileButton1');
        getPath(file_img, iptfileupload, file_img);
        $(".profileButton1").css("font-size", "0px");

    }

    $(".s-robot-form_headimg-close").unbind('click').bind("click",function(){
        $("#profileButton1").outerHTML = $("#profileButton1").outerHTML;
        // $("#selectImg1").attr('src','/img/course/coverimg.png');
        $("#headImg").attr('src','http://3tdoc.oss-cn-beijing.aliyuncs.com/img/2018/05/11/13/1835_6351.png');

        $(".s-robot-form_headimg-close").css("display","none")

    })
</script>




