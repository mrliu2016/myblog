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
<div class="s-gift-manage">
    <div class="s-gift-manage_title">新增机器人</div>
    <a class="s-gift-manage_back" href="/robot/index">返回</a>
    <div class="s-robot-form">
        <!-- <div class="s-robot-form_title">用户详情</div>-->
        <form action="" method="post" enctype="multipart/form-data" id="robotForm">
            <div class="s-robot-form_upload">
                <div class="s-robot-form_selectimg">
                    <div class="s-robot-form_selectimg-icon1"></div>
                    <!--<div class="s-robot-form_selectimg-icon2"></div>-->
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
                    <span class="c-form_item-title">昵称：</span>
                    <input class="c-input c-form_item-input" placeholder="0-10个字符长度" id="nickName" name="nickName" maxlength="10" autocomplete="off"/>
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
                            <select class="c-select u-radius--0 s-robot-form_address-select" name="province" default="0" id="province" onchange="if(this.value != '') setCity(this.options[this.selectedIndex].value);">
                                <option value="未知星球">未知星球</option>
                                 <option value="北京市">北京市</option>
                                 <option value="上海市">上海市</option>
                                 <option value="重庆市">重庆市</option>
                                 <option value="天津市">天津市</option>
                                 <option value="江苏省">江苏省</option>
                                 <option value="浙江省">浙江省</option>
                                 <option value="河北省">河北省</option>
                                 <option value="山西省">山西省</option>
                                 <option value="辽宁省">辽宁省</option>
                                 <option value="吉林省">吉林省</option>
                                 <option value="黑龙江省">黑龙江省</option>
                                 <option value="安徽省">安徽省</option>
                                 <option value="福建省">福建省</option>
                                 <option value="江西省">江西省</option>
                                 <option value="山东省">山东省</option>
                                 <option value="河南省">河南省</option>
                                 <option value="湖北省">湖北省</option>
                                 <option value="湖南省">湖南省</option>
                                 <option value="广东省">广东省</option>
                                 <option value="海南省">海南省</option>
                                 <option value="福建省">福建省</option>
                                 <option value="四川省">四川省</option>
                                 <option value="贵州省">贵州省</option>
                                 <option value="云南省">云南省</option>
                                 <option value="陕西省">陕西省</option>
                                 <option value="甘肃省">甘肃省</option>
                                 <option value="青海省">青海省</option>
                                 <option value="台湾省">台湾省</option>

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
                    <input class="c-input c-form_item-input" id="followees_cnt" name="followees_cnt" autocomplete="off"/>
                </p>
                <p class="c-form_item">
                    <span class="c-form_item-title">粉丝数：</span>
                    <input class="c-input c-form_item-input" id="followers_cnt" name="followers_cnt" autocomplete="off"/>
                </p>
                <p class="c-form_item">
                    <span class="c-form_item-title">收到礼物：</span>
                    <span class="s-robot-form_receive-wrap">
                            <input class="c-input c-form_item-input s-robot-form_receive" id="receivedGift" name="receivedGift" autocomplete="off"/>
                        </span>
                </p>
                <p class="c-form_item">
                    <span class="c-form_item-title">送出礼物：</span>
                    <span class="s-robot-form_give-wrap">
                            <input class="c-input c-form_item-input s-robot-form_give" id="sendGift" name="sendGift" autocomplete="off"/>
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
        <div>
            <button class="c-btn" id="confirm">确定</button>
            <button class="c-btn">取消</button>
        </div>
    </div>
</div>
<!--确认是否删除start-->
<div id="confirm_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">确认删除此机器人？</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
            </div>
        </div>
    </div>
</div>

<script>
    function tip($message) {
        $("#confirm_frame").css("display","block");
        $(".s-banlive-confirm-text").text($message);
        $(".s-banlive-confirm").unbind("click").bind("click",function () {
            $("#confirm_frame").css("display","none");
        });
    }
    $("#confirm").click(function(){

        var fileEl = $('#profileButton1');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            alert("请选择头像");
            event.preventDefault();
            return;
        }
        var nickName = $("#nickName").val();
        var sex = $("#sex").val();
        var province = $("#province").val();
        var city = $("#city").val();
        var followees_cnt = $("#followees_cnt").val();
        var followers_cnt = $("#followers_cnt").val();
        var receivedGift = $("#receivedGift").val();
        var sendGift = $("#sendGift").val();
        var description = $("#description").val();

        if(nickName == undefined || nickName == '' || nickName == null || nickName.length >10){
            tip("请输入正确用户昵称！");
            return false;
        }

        if(!isNaN(Number(followees_cnt))){
            tip("关注数只能是纯数字");
            return false;
        }
        if(!isNaN(Number(followers_cnt))){
            tip("粉丝数只能是纯数字");
            return false;
        }
        if(!isNaN(Number(receivedGift))){
            tip("收到礼物只能是纯数字");
            return false;
        }
        if(!isNaN(Number(sendGift))){
            tip("送出礼物只能是纯数字");
            return false;
        }

        // if(parseFloat(price).toString() == "NaN"){
        //     alert('请输入正确的礼物价格.');
        //     return false;
        // }
        // var params  = {};
        // params.nickName  = nickName;
        // params.sex  = sex;
        // params.province = province;
        // params.city = city;
        // params.followees_cnt = followees_cnt;
        // params.followers_cnt = followers_cnt;
        // params.receivedGift = receivedGift;
        // params.sendGift = sendGift;
        // params.description = description;

        // $.ajax({
        //     url: "/robot/add-submit",
        //     type: "post",
        //     data: params,
        //     dataType: "json",
        //     success: function (data) {
        //         if(data != undefined && data.code == 0){
        //             window.location.href='/robot/list';
        //         }
        //         else{
        //             alert('编辑失败！');
        //         }
        //     }
        // });

        $("#robotForm").attr("action","/robot/add-robot");
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
        if (file.size >=1024*1024) {
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

</script>

<script language="JavaScript" type="text/javascript">
        <!--
            /*
             * 说明：将指定下拉列表的选项值清空
             * 转自：Gdong Elvis ( http://www.gdcool.net )
             *
             * @param {String || Object]} selectObj 目标下拉选框的名称或对象，必须
             */
            function removeOptions(selectObj)
            {
                if (typeof selectObj != 'object')
                {
                    selectObj = document.getElementById(selectObj);
                }
                // 原有选项计数
                var len = selectObj.options.length;
                for (var i=0; i < len; i++) {
                    // 移除当前选项
                    selectObj.options[0] = null;
                }
            }
    /*
    * @param {String || Object]} selectObj 目标下拉选框的名称或对象，必须
    * @param {Array} optionList 选项值设置 格式：[{txt:'北京', val:'010'}, {txt:'上海', val:'020'}] ，必须
    * @param {String} firstOption 第一个选项值，如：“请选择”，可选，值为空
    * @param {String} selected 默认选中值，可选
    */
    function setSelectOption(selectObj, optionList, firstOption, selected) {
        if (typeof selectObj != 'object')
        {
            selectObj = document.getElementById(selectObj);
        }
        // 清空选项
        removeOptions(selectObj);
        // 选项计数
        var start = 0;
        // 如果需要添加第一个选项
        if (firstOption) {
            selectObj.options[0] = new Option(firstOption, '');
            // 选项计数从 1 开始
            start ++;
        }
        var len = optionList.length;
        for (var i=1; i < len; i++) {
            // 设置 option
            selectObj.options[start] = new Option(optionList[i].txt, optionList[i].val);
            // 选中项
            if(selected == optionList[i].val)  {
                selectObj.options[start].selected = true;
            }
            // 计数加 1
            start ++;
        }
    }
    //-->
</script>
<script language="javaScript" type="text/javascript">
    var cityArr = [];
    cityArr['江苏省'] =
        [
            {txt:'徐州市', val:'徐州市'}, {txt:'无锡市', val:'无锡市'},
            {txt:'宿迁市', val:'宿迁市'}, {txt:'苏州市', val:'苏州市'},
            {txt:'连云港市', val:'连云港市'}, {txt:'南京市', val:'南京市'},
            {txt:'淮安市', val:'淮安市'}, {txt:'扬州市', val:'扬州市'},
            {txt:'盐城市', val:'盐城市'}, {txt:'泰州市', val:'泰州市'},
            {txt:'南通市', val:'南通市'}, {txt:'常州市', val:'常州市'}
        ];
    cityArr['浙江省'] =
        [
            {txt:'杭州市', val:'杭州市'},{txt:'宁波市', val:'宁波市'},{txt:'温州市', val:'温州市'},{txt:'嘉兴市', val:'嘉兴市'},
            {txt:'湖州市', val:'湖州市'},{txt:'绍兴市', val:'绍兴市'},{txt:'金华市', val:'金华市'},{txt:'衢州市', val:'衢州市'},
            {txt:'舟山市', val:'舟山市'},{txt:'台州市', val:'台州市'},{txt:'丽水市', val:'丽水市'}

        ];
    cityArr['河北省'] =
        [
            {txt:'邢台市', val:'邢台市'},{txt:'承德市', val:'承德市'},
            {txt:'石家庄市', val:'石家庄市'},{txt:'衡水市', val:'衡水市'},
            {txt:'沧州市', val:'沧州市'},{txt:'保定市', val:'保定市'},
            {txt:'廊坊市', val:'廊坊市'}, {txt:'张家口市', val:'张家口市'},
            {txt:'唐山市', val:'唐山市'}, {txt:'秦皇岛市', val:'秦皇岛市'},
            {txt:'邯郸市', val:'邯郸市'},
        ];
    cityArr['山西省'] =
        [
            {txt:'太原市', val:'太原市'},{txt:'大同市', val:'大同市'},
            {txt:'朔州市', val:'朔州市'},{txt:'阳泉市', val:'阳泉市'},
            {txt:'长治市', val:'长治市'},{txt:'晋城市', val:'晋城市'},
            {txt:'忻州市', val:'忻州市'}, {txt:'晋中市', val:'晋中市'},
            {txt:'临汾市', val:'临汾市'}, {txt:'运城市', val:'运城市'},
            {txt:'吕梁市', val:'吕梁市'},
        ];

    cityArr['辽宁省'] =
        [
            {txt:'沈阳市', val:'沈阳市'},{txt:'大连市', val:'大连市'},
            {txt:'鞍山市', val:'鞍山市'},{txt:'抚顺市', val:'抚顺市'},
            {txt:'本溪市', val:'本溪市'},{txt:'丹东市', val:'丹东市'},
            {txt:'锦州市', val:'锦州市'}, {txt:'营口市', val:'营口市'},
            {txt:'阜新市', val:'阜新市'}, {txt:'辽阳市', val:'辽阳市'},
            {txt:'盘锦市', val:'盘锦市'},{txt:'铁岭市', val:'铁岭市'},
            {txt:'朝阳市', val:'朝阳市'},{txt:'葫芦岛市', val:'葫芦岛市'},
        ];

    cityArr['吉林省'] =
        [
            {txt:'长春市', val:'长春市'},{txt:'吉林市', val:'吉林市'},
            {txt:'四平市', val:'四平市'},{txt:'辽源市', val:'辽源市'},
            {txt:'通化市', val:'通化市'},{txt:'白山市', val:'白山市'},
            {txt:'松原市', val:'松原市'}, {txt:'白城市', val:'白城市'},
        ];
    cityArr['黑龙江省'] =
        [
            {txt:'哈尔滨市', val:'哈尔滨市'},{txt:'齐齐哈尔市', val:'齐齐哈尔市'},
            {txt:'牡丹江市', val:'牡丹江市'},{txt:'佳木斯市', val:'佳木斯市'},
            {txt:'大庆市', val:'大庆市'},{txt:'鸡西市', val:'鸡西市'},
            {txt:'双鸭山市', val:'双鸭山市'}, {txt:'伊春市', val:'伊春市'},
            {txt:'鹤岗市', val:'鹤岗市'},{txt:'七台河市', val:'七台河市'},
            {txt:'鹤岗市', val:'鹤岗市'}, {txt:'黑河市', val:'黑河市'},
            {txt:'绥化市', val:'绥化市'}, {txt:'大兴安岭地区', val:'大兴安岭地区'},

        ];

    cityArr['安徽省'] =
        [
            {txt:'合肥市', val:'合肥市'},{txt:'芜湖市', val:'芜湖市'},
            {txt:'蚌埠市', val:'蚌埠市'},{txt:'淮南市', val:'淮南市'},
            {txt:'马鞍山市', val:'马鞍山市'},{txt:'淮北市', val:'淮北市'},
            {txt:'铜陵市', val:'铜陵市'}, {txt:'安庆市', val:'安庆市'},
            {txt:'黄山市', val:'黄山市'},{txt:'阜阳市', val:'阜阳市'},
            {txt:'宿州市', val:'宿州市'}, {txt:'滁州市', val:'滁州市'},
            {txt:'六安市', val:'六安市'}, {txt:'宣城市', val:'宣城市'},
            {txt:'巢湖市', val:'巢湖市'}, {txt:'池州市', val:'池州市'},
            {txt:'亳州市', val:'亳州市'}
        ];

    cityArr['福建省'] =
        [
            {txt:'福州市', val:'福州市'},{txt:'泉州市', val:'泉州市'},
            {txt:'漳州市', val:'漳州市'},{txt:'南平市', val:'南平市'},
            {txt:'三明市', val:'三明市'},{txt:'龙岩市', val:'龙岩市'},
            {txt:'莆田市', val:'莆田市'}, {txt:'宁德市', val:'宁德市'},
        ];


    cityArr['江西省'] =
        [
            {txt:'南昌市', val:'南昌市'},{txt:'赣州市', val:'赣州市'},
            {txt:'吉安市', val:'吉安市'},{txt:'萍乡市', val:'萍乡市'},
            {txt:'宜春市', val:'宜春市'},{txt:'新余市', val:'新余市'},
            {txt:'景德镇市', val:'景德镇市'}, {txt:'抚州市', val:'抚州市'},
            {txt:'九江市', val:'九江市'},{txt:'上饶市', val:'上饶市'},
            {txt:'鹰潭市', val:'鹰潭市'}
        ];


    cityArr['山东省'] =
        [
            {txt:'枣庄市', val:'枣庄市'},{txt:'济南市', val:'济南市'},
            {txt:'德州市', val:'德州市'},{txt:'济宁市', val:'济宁市'},
            {txt:'临沂市', val:'临沂市'},{txt:'青岛市', val:'青岛市'},
            {txt:'泰安市', val:'泰安市'}, {txt:'威海市', val:'威海市'},
            {txt:'淄博市', val:'淄博市'},{txt:'菏泽市', val:'菏泽市'},
            {txt:'烟台市', val:'烟台市'},{txt:'莱芜市', val:'莱芜市'},
            {txt:'滨州市', val:'滨州市'},{txt:'东营市', val:'东营市'},
            {txt:'聊城市', val:'聊城市'},{txt:'日照市', val:'日照市'},
            {txt:'潍坊市', val:'潍坊市'}
        ];

    cityArr['河南省'] =
        [
            {txt:'郑州市', val:'郑州市'},{txt:'开封市', val:'开封市'},
            {txt:'洛阳市', val:'洛阳市'},{txt:'平顶山市', val:'平顶山市'},
            {txt:'安阳市', val:'安阳市'},{txt:'鹤壁市', val:'鹤壁市'},
            {txt:'新乡市', val:'新乡市'}, {txt:'焦作市', val:'焦作市'},
            {txt:'濮阳市', val:'濮阳市'},{txt:'许昌市', val:'许昌市'},
            {txt:'漯河市', val:'漯河市'}
        ];


    cityArr['湖北省'] =
        [
            {txt:'武汉市', val:'武汉市'},{txt:'黄石市', val:'黄石市'},
            {txt:'十堰市', val:'十堰市'},{txt:'荆州市', val:'荆州市'},
            {txt:'宜昌市', val:'宜昌市'},{txt:'襄阳市', val:'襄阳市'},
            {txt:'鄂州市', val:'鄂州市'}, {txt:'荆门市', val:'荆门市'},
            {txt:'孝感市', val:'孝感市'},{txt:'黄冈市', val:'黄冈市'},
            {txt:'咸宁市', val:'咸宁市'},{txt:'随州市', val:'随州市'},
            {txt:'恩施市', val:'恩施市'},
        ];

    cityArr['湖南省'] =
        [
            {txt:'长沙市', val:'长沙市'},{txt:'株洲市', val:'株洲市'},
            {txt:'湘潭市', val:'湘潭市'},{txt:'衡阳市', val:'衡阳市'},
            {txt:'永州市', val:'永州市'},{txt:'邵阳市', val:'邵阳市'},
            {txt:'岳阳市', val:'岳阳市'}, {txt:'张家界市', val:'张家界市'},
            {txt:'常德市', val:'常德市'},{txt:'益阳市', val:'益阳市'},
            {txt:'娄底市', val:'娄底市'},{txt:'郴州市', val:'郴州市'},
            {txt:'怀化市', val:'怀化市'},
        ];

    cityArr['广东省'] =
        [
            {txt:'广州市', val:'广州市'},{txt:'韶关市', val:'韶关市'},
            {txt:'深圳市', val:'深圳市'},{txt:'珠海市', val:'珠海市'},
            {txt:'汕头市', val:'汕头市'},{txt:'佛山市', val:'佛山市'},
            {txt:'江门市', val:'江门市'}, {txt:'湛江市', val:'湛江市'},
            {txt:'茂名市', val:'茂名市'},{txt:'肇庆市', val:'肇庆市'},
            {txt:'惠州市', val:'惠州市'},{txt:'梅州市', val:'梅州市'},
            {txt:'汕尾市', val:'汕尾市'},{txt:'河源市', val:'河源市'},
            {txt:'阳江市', val:'阳江市'},{txt:'清远市', val:'清远市'},
            {txt:'东莞市', val:'东莞市'},{txt:'中山市', val:'中山市'},
            {txt:'潮州市', val:'潮州市'},{txt:'揭阳市', val:'揭阳市'},
            {txt:'云浮市', val:'云浮市'}
        ];

    cityArr['海南省'] =
        [
            {txt:'海口市', val:'海口市'},{txt:'文昌市', val:'文昌市'},
            {txt:'三亚市', val:'三亚市'},{txt:'五指山市', val:'五指山市'},
            {txt:'琼海市', val:'琼海市'},{txt:'儋州市', val:'儋州市'},
            {txt:'万宁市', val:'万宁市'}
        ];

    cityArr['四川省'] =
        [
            {txt:'成都市', val:'成都市'},{txt:'自贡市', val:'自贡市'},
            {txt:'攀枝花市', val:'攀枝花市'},{txt:'泸州市', val:'泸州市'},
            {txt:'德阳市', val:'德阳市'},{txt:'绵阳市', val:'绵阳市'},
            {txt:'广元市', val:'广元市'},
            {txt:'遂宁市', val:'遂宁市'},{txt:'内江市', val:'内江市'},{txt:'乐山市', val:'乐山市'},
            {txt:'南充市', val:'南充市'},{txt:'眉山市', val:'眉山市'},{txt:'宜宾市', val:'宜宾市'},
            {txt:'广安市', val:'广安市'},{txt:'达州市', val:'达州市'},{txt:'雅安市', val:'雅安市'},
            {txt:'巴中市', val:'巴中市'},{txt:'资阳市', val:'资阳市'}
        ];

    cityArr['贵州省'] =
        [
            {txt:'贵阳市', val:'贵阳市'},{txt:'遵义市', val:'遵义市'},
            {txt:'安顺市', val:'安顺市'},{txt:'首府都匀市', val:'首府都匀市'},
            {txt:'首府凯里市', val:'首府凯里市'},{txt:'首府铜仁市', val:'首府铜仁市'},
            {txt:'首府毕节市', val:'首府毕节市'},{txt:'六盘水市', val:'六盘水市'},
            {txt:'首府兴义市', val:'首府兴义市'},
        ];
    cityArr['云南省'] =
        [
            {txt:'昆明市', val:'昆明市'},{txt:'曲靖市', val:'曲靖市'},
            {txt:'昭通市', val:'昭通市'},{txt:'玉溪市', val:'玉溪市'},
            {txt:'楚雄州', val:'楚雄州'},{txt:'红河州', val:'红河州'},
            {txt:'文山州', val:'文山州'},{txt:'普洱市', val:'普洱市'},
            {txt:'版纳州', val:'版纳州'},
            {txt:'大理州', val:'大理州'},{txt:'保山市', val:'保山市'},
            {txt:'德宏州', val:'德宏州'},{txt:'丽江市', val:'丽江市'},
            {txt:'怒江州', val:'怒江州'},{txt:'迪庆州', val:'迪庆州'},
            {txt:'临沧市', val:'临沧市'}
        ];

    cityArr['陕西省'] =
        [
            {txt:'西安市', val:'西安市'},{txt:'铜川市', val:'铜川市'},
            {txt:'宝鸡市', val:'宝鸡市'},{txt:'咸阳市', val:'咸阳市'},
            {txt:'渭南市', val:'渭南市'},{txt:'汉中市', val:'汉中市'},
            {txt:'安康市', val:'安康市'},{txt:'商洛市', val:'商洛市'},
            {txt:'延安市', val:'延安市'},{txt:'榆林市', val:'榆林市'},
        ];

    cityArr['甘肃省'] =
        [
            {txt:'兰州市', val:'兰州市'},{txt:'嘉峪关市', val:'嘉峪关市'},
            {txt:'金昌市', val:'金昌市'},{txt:'白银市', val:'白银市'},
            {txt:'天水市', val:'天水市'},{txt:'酒泉市', val:'酒泉市'},
            {txt:'张掖市', val:'张掖市'},{txt:'武威市', val:'武威市'},
            {txt:'定西市', val:'定西市'},{txt:'陇南市', val:'陇南市'},
            {txt:'平凉市', val:'平凉市'},{txt:'庆阳市', val:'庆阳市'},
        ];

    cityArr['青海省'] =
        [
            {txt:'西宁市', val:'西宁市'},{txt:'海东市', val:'海东市'},
            {txt:'玉树市', val:'玉树市'},{txt:'德尔哈市', val:'德尔哈市'},
            {txt:'格尔木市', val:'格尔木市'}
        ];
    cityArr['台湾省'] =
        [
            {txt:'基隆市', val:'基隆市'},{txt:'新竹市', val:'新竹市'},
            {txt:'台中市', val:'台中市'},{txt:'嘉义市', val:'嘉义市'},
            {txt:'台南市', val:'台南市'}
        ];

    cityArr['北京市'] =[{txt:'北京市', val:'北京市'},];
    cityArr['上海市'] =[{txt:'上海市', val:'上海市'},];
    cityArr['重庆市'] =[{txt:'重庆市', val:'重庆市'},];
    cityArr['天津市'] =[{txt:'天津市', val:'天津市'},];
    cityArr['未知星球'] =[{txt:'未知星球', val:'未知星球'},];

    function setCity(province)
    {
        setSelectOption('city', cityArr[province], cityArr[province][0].txt);
    }
</script>

