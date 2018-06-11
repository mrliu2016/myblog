<?php
$this->title = '消息推送';
?>

<div class="s-message-push-wrap">
    <div class="s-message-push">
        <p class="s-message-push_title">消息推送</p>
        <div class="s-message-push_content">
            <textarea class="c-input u-radius--0 s-message-push_input" id="message"></textarea>
            <div class="s-message-push_select-wrap">
<!--                <button class="c-btn c-btn-dull s-message-push_selectall">全部用户</button>-->
                <span class="s-message-push_selectall"><input class="s-user_all_select" type="checkbox" onclick="selectAllUser()"/>全部用户</span>
                <!--<button class="c-btn c-btn-dull s-message-push_selectuser">选择用户</button>-->
                <a href="#" class="c-btn c-btn-dull s-message-push_selectuser" id="selectUser">选择用户</a>
            </div>
            <div id="select-push-user">

            </div>
            <button class="c-btn c-btn-primary u-radius--circle s-message-push_submit">发送</button>
        </div>
    </div>
</div>

<!--选择用户start-->
<div class="select-user" style="display: none;">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-message-push-m_">
        <div class="c-modal">
            <div class="c-modal-close s-message-push-m_close">关闭</div>
            <div class="c-modal_header">选择用户</div>

            <div class="s-message-push-m_content">
                <div class="s-message-push-m_search-content">
                    <div class="s-message-push-m_search-item">
                        <span>ID</span>
                        <input class="c-input s-message-push-m_search-input" type="text" id="id">
                    </div>
                    <div class="s-message-push-m_search-item">
                        <span>昵称</span>
                        <input class="c-input s-message-push-m_search-input" type="text" id="nickName">
                    </div>
                    <div class="s-message-push-m_search-item">
                        <span>房间号</span>
                        <input class="c-input s-message-push-m_search-input" type="text" id="roomId">
                    </div>
                    <div class="s-message-push-m_search-item">
                        <span>手机号</span>
                        <input class="c-input s-message-push-m_search-input" type="text" id="mobile">
                    </div>
                    <button class="c-btn c-btn-primary u-radius--circle s-message-push-m_searchbtn">查询</button>
                </div>
                <div class="s-message-push-m_table-wrap">
                    <table class="c-table s-message-push-m_table">
                        <thead class="c-table-thead s-message-push-m_thead">
                        <tr>
                            <th></th>
                            <th>序号</th>
                            <th>ID</th>
                            <th>昵称</th>
                            <th>房间号</th>
                            <th>手机号</th>
                        </tr>
                        </thead>
                        <tbody class="c-table-tbody s-message-push-m_tbody" id="tbody">

                        </tbody>
                    </table>
                </div>
                <p class="s-message-push-m_count">共 <span class="count"></span> 条记录</p>
            </div>
            <div class="s-gift-table-pages"  id="pageBanner">
                <a disabled class="c-btn s-gift-page s-gift-prepage">.</a>
                <a class="c-btn s-gift-page" onclick="handPaging(1)">1</a>
                <a class="c-btn s-gift-page" onclick="handPaging(2)">2</a>
                <a class="c-btn s-gift-page" onclick="handPaging(3)">3</a>
                <a class="c-btn s-gift-page s-gift-nextpage">.</a>
                <!--<a href="" class="c-btn s-gift-page s-gift-prepage"></a>-->
            </div>
            <div class="c-modal-footer s-message-push-m_operate">
                <button class="c-btn c-btn-primary c-btn--large s-message-push-m_confirm">确认</button>
                <button class="c-btn c-btn--large s-message-push-m_cancel">取消</button>
            </div>
        </div>
    </div>
</div>
<!--选择用户end-->
<script type="text/javascript">

    var dataObj = {};//定义全局存储选择用户的对象
    var data = '';//存储用户ID
    $("#selectUser").click(function () {
        $(".select-user").css("display","block");
    });
    //发送消息
    $(".s-message-push_submit").unbind('click').bind('click',function () {
        var message = $("#message").val();
        var params = {};
        params.data = data;
        params.message = message;

        if(dataObj == {} || dataObj == undefined){
            alert("请选择要推送消息的用户.");
            return false;
        }
        if(message == '' || message == undefined || message == null){
            alert("请输入要推送的内容.");
            return false;
        }
        $.ajax({
            url: "/message/send-message",
            type: "post",
            data: params,
            // cache: false,
            dataType: "json",
            success: function (data) {
                if(data.code == 0){
                    window.location.reload();
                }
                else{
                    alert("消息推送失败！");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('异常错误，正在紧急排查！');
            }
        });
    });

    //选择全部用户
    function selectAllUser() {
        if($(".s-user_all_select").prop('checked')){
            //将选择用户不可点
            // console.log(111);
            data = "<?=$userStr?>"
            console.log(dataObj);

        }
        else{
            // console.log(222);
            data = {};
            $("#select-push-user").remove();
        }
    }
/*-------------------------
    选择用户部分
--------------------------*/

    //选择用户取消
    $(".s-message-push-m_close").unbind('click').bind('click',function () {
        dataObj = {};
        $(".select-user").css("display","none");
    });
    $(".s-message-push-m_cancel").unbind('click').bind('click',function () {
        dataObj = {};
        $(".select-user").css("display","none");
    });
    function toObj(userId,roomId){//组成对象的方法
        var data = {};
        data[userId] = roomId;
        return data;
    }

    //选中添加用户  取消去掉用户
    function checkboxOnclick(pageNo,key,userId,roomId,nickName){
        var cName = "page"+pageNo+'_'+key;
        if($('.'+cName).prop('checked')){//将用户Id，房间Id添加到对象中
            //添加对象
            dataObj[userId] = [roomId,nickName];
        }
        else {//将用户Id，房间Id从对象移除
            for(var key in dataObj){
                if(key == userId){
                    delete dataObj[userId];
                }
            }
        }
    }
    //查询
    $(".s-message-push-m_searchbtn").unbind('click').bind('click',function () {

        var id = $("#id").val();
        var nickName = $("#nickName").val();
        var roomId = $("#roomId").val();
        var mobile = $("#mobile").val();

        if(id == '' && nickName == '' && roomId == '' && mobile == ''){
            handPaging(1);
            return false;
        }
        var params = {};
        params.id = id;
        params.nickName = nickName;
        params.roomId = roomId;
        params.mobile = mobile;

        $.ajax({
            url: "/message/search",
            type: "post",
            data: params,
            // cache: false,
            dataType: "json",
            success: function (data) {
                var list = data.data.list;
                var pageNo = 1;
                var tbody = '';
                $.each(list,function (k,v) {
                    // console.log(k);
                    tbody += '<tr>';
                    tbody += '<td><input type="checkbox" class="s-message-push-m_select page'+pageNo+'_'+k+'" onclick="checkboxOnclick('+pageNo+','+k+','+v.id+','+v.roomId+',\''+v.nickName+'\')"/></td>';
                    tbody += '<td>'+(k+1)+'</td>';
                    tbody += '<td>'+v.id+'</td>';
                    tbody += '<td>'+v.nickName+'</td>';
                    tbody += '<td>'+v.roomId+'</td>';
                    tbody += '<td>'+v.mobile+'</td>';
                    tbody += '</tr>';
                });
                $("#tbody").html(tbody);
                $(".count").text(data.data.count);
                $("#pageBanner").html('');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    });

    handPaging(1);
    //分页
    function handPaging(val) {
        var params = {};
        params.page = val;
        $.ajax({
            url: "/message/page",
            type: "post",
            data: params,
            // cache: false,
            dataType: "json",
            success: function (data) {
                if(data.code == 0){
                    var list = data.data.list;
                    var pageNo = data.data.pageNo;
                    var tbody = '';
                    $.each(list,function (k,v) {
                        tbody += '<tr>';
                        tbody += '<td><input type="checkbox" class="s-message-push-m_select page'+pageNo+'_'+k+'" onclick="checkboxOnclick('+pageNo+','+k+','+v.id+','+v.roomId+',\''+v.nickName+'\')"/></td>';
                        tbody += '<td>'+(k+1)+'</td>';
                        tbody += '<td>'+v.id+'</td>';
                        tbody += '<td>'+v.nickName+'</td>';
                        tbody += '<td>'+v.roomId+'</td>';
                        tbody += '<td>'+v.mobile+'</td>';
                        tbody += '</tr>';

                    });
                    $("#tbody").html(tbody);
                    var pageBanner = data.data.pageBanner;
                    $("#pageBanner").html(pageBanner);
                    $(".count").text(data.data.count);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('get issue');
            }
        });
    }
    //选择用户确定
    $(".s-message-push-m_confirm").unbind('click').bind('click',function () {
        var str = '';
        for(var key in dataObj){
            // data[key] = dataObj[key][0];
            // nickName.push(dataObj[key][1]);
            str += '<div class="s-message-push_user">\n' +
                '<span>'+dataObj[key][1]+'</span>\n' +
                '<button class="c-btn-circle-close s-message-push_userremote" data-val="'+key+'"></button>\n' +
                '</div>';
        }
        //使用nickName显示
        //使用data 推送消息
        $(".select-user").css("display","none");
        $("#select-push-user").html(str);//显示选择用户
        $(".c-btn-circle-close").unbind('click').bind('click',function () {
            // console.log(dataObj);
            var userId = $(this).attr('data-val');//保存userId
            $(this).parent('div').css("display","none");
            for(var key in dataObj){//移除userId
                if(key == userId){
                    delete dataObj[userId];
                }
            }
            // console.log(dataObj);
        });

        for(var key in dataObj){
            data += key + ',';
        }
        data =data.substring(0,data.length-1);
    });
</script>
