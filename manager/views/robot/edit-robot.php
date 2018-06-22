<?php
$this->title = '机器人管理';
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
    <form action="" method="post" enctype="multipart/form-data" id="robotForm">
        <div class="s-robot-form_upload">
            <div class="s-robot-form_selectimg">
                <div class="s-robot-form_selectimg-icon1"></div>
                <img src="" class="s-robot-form_selectimg-icon2" id="selectImg1">
                <input type="file" name="imgSrc" id="profileButton1" onchange="changepic()">
            </div>

            <?php if(!empty($item['imgSrc'])):?>
                <input type="hidden" name="img" value="<?=$item['imgSrc']?>">
                <img class="s-robot-form_head-img" src="<?=$item['imgSrc']?>" alt="用户头像" id="headImg">
                <div class="s-robot-form_headimg-close" style="display: block;"></div>
            <?php else:?>
                <input type="hidden" name="img" value="http://userservice.oss-cn-beijing.aliyuncs.com/gift/2018/06/20/14/3410_3765.png">
                <img class="s-robot-form_head-img" src="http://userservice.oss-cn-beijing.aliyuncs.com/gift/2018/06/20/14/3410_3765.png" alt="用户头像" id="headImg">
                <div class="s-robot-form_headimg-close" style="display: none;"></div>
            <?php endif;?>
            <input type="hidden" id="uploadType" value="1" name="uploadType">
            <!--<img class="s-robot-form_headimg" src="http://3tdoc.oss-cn-beijing.aliyuncs.com/img/2018/05/11/13/1835_6351.png" alt="用户头像">-->
            <div class="s-robot-form_img-tips">
                <p>图片格式：JPG、JPEG、PNG</p>
                <p>图片大小：小于一M</p>
            </div>
        </div>
        <div class="s-robot-form-details">
            <p class="c-form_item">
                <span class="c-form_item-title">ID：</span>
                <input type="hidden" value="<?=$id?>" id="id" name="id">
                <?=$id?>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">昵称：</span>
                <input class="c-input c-form_item-input" placeholder="0-10个字符长度" id="nickName" name="nickName" autocomplete="off"/>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">性别：</span>
                <span class="c-select-wrap">
                        <select class="c-select c-form_item-input" default="0" id="sex" name="sex">
                            <option value="1">男</option>
                            <option value="0">女</option>
                        </select>
                    </span>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">所在地：</span>
                <span class="c-select-wrap">
                        <select class="c-select u-radius--0 s-robot-form_address-select" name="province" default="0" id="province">
                            <option value="未知星球">未知星球</option>
                            <option value="北京市">北京市</option>
                            <option value="河北">河北</option>
                        </select>
                    </span>
                -
                <span class="c-select-wrap">
                        <select class="c-select u-radius--0 s-robot-form_address-select" name="city" default="0" id="city">
                            <option value="未知星球">未知星球</option>
                            <option value="北京市">北京市</option>
                            <option value="邢台市">邢台市</option>
                        </select>
                    </span>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">关注数：</span>
                <input class="c-input c-form_item-input" id="followees_cnt" name="followees_cnt"  autocomplete="off"/>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">粉丝数：</span>
                <input class="c-input c-form_item-input" id="followers_cnt" name="followers_cnt"  autocomplete="off"/>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">收到礼物：</span>
                <span class="s-robot-form_receive-wrap">
                        <input class="c-input c-form_item-input s-robot-form_receive" id="receivedGift" name="receivedGift"  autocomplete="off"/>
                    </span>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">送出礼物：</span>
                <span class="s-robot-form_give-wrap">
                        <input class="c-input c-form_item-input s-robot-form_give" id="sendGift" name="sendGift"  autocomplete="off"/>
                    </span>
            </p>
            <p class="c-form_item">
                <span class="c-form_item-title">个性签名：</span>
                <!--<input class="c-input c-form_item-input s-robot-form_signature" />-->
                <select id="description" class="c-input c-form_item-input s-robot-form_signature" name="description">
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
    </form>
    <div class="s-robot-form_btns">
        <button class="c-btn s-robot-form_confirm" id="confirm">确定</button>
        <button class="c-btn s-robot-form_cancel">取消</button>
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

<script>
    $("#confirm").click(function(){
        var fileEl = $('#profileButton1');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            tip("请选择头像")
            event.preventDefault();
            return false;
        }
        var nickName = $("#nickName").val();
        if(nickName=="" || nickName.length>10){
            tip("请输入0-10个字符长度的昵称！");
            return false;
        }
        $("#robotForm").attr("action","/robot/edit-robot");
        $("#robotForm").submit();
    });
    $(".cancel").click(function () {
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
                $("#uploadType").val(2);
            } else {
                $(".showintro").click();
            }
        }
    }

    function changepic() {
        var file_img = document.getElementById("headImg");
        console.log(file_img);
        $(".s-robot-form_headimg-close").css('display','block');
        var iptfileupload = document.getElementById('profileButton1');
        getPath(file_img, iptfileupload, file_img);
        $(".profileButton1").css("font-size", "0px");
    }

    $(".s-robot-form_headimg-close").unbind('click').bind("click",function(){
        $("#profileButton1").outerHTML = $("#profileButton1").outerHTML;
        $("#headImg").attr('src','http://userservice.oss-cn-beijing.aliyuncs.com/gift/2018/06/20/14/3410_3765.png');
        $(".s-robot-form_headimg-close").css("display","none");
        $("#uploadType").val(1);
    })

    $(".delete-img").on("click",function(){
        $("#profileButton1").outerHTML = $("#profileButton1").outerHTML;
        // $("#selectImg1").attr('src','/img/course/coverimg.png');
        $("#headImg").attr('src','/img/course/coverimg.png');
        $(".delete-img").css("display","none");
    });
    //提示框
    function tip(message){
        $("#tip_frame").css("display","block");
        $(".s-banlive-confirm-text").text(message);
        $(".s-banlive-confirm").unbind('click').bind('click',function () {
            $("#tip_frame").css("display","none");
        });
    }
</script>


