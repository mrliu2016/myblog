<?php
$this->title = '选择用户';
?>
<style>
    .select{
        color: white;
        background: #1AC2AD;
        border-color: #1AC2AD
    }
</style>
<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <!--<p class="s-gift-search-title">礼物管理</p>-->
            <!--<form method="get" action="/live/live-record" id="searchForm" name="searchForm">-->
                <div class="s-gift-search-content">
                    <div class="s-gift-search-item">
                        <span>ID</span>
                        <input class="c-input s-gift-search-input" type="text" name="id" id="id">
                    </div>
                    <div class="s-gift-search-item">
                        <span>昵称</span>
                        <input class="c-input s-gift-search-input" type="text" name="nickName">
                    </div>
                    <div class="s-gift-search-item">
                        <span>房间号</span>
                        <input class="c-input s-gift-search-input" type="text" name="roomId">
                    </div>
                    <div class="s-gift-search-item">
                        <span>手机号</span>
                        <input class="c-input s-gift-search-input" type="text" name="mobile">
                    </div>
                    <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn"  id="searchBtn">查询</button>
                </div>
            <!--</form>-->
        </div>
        <div class="s-gift-table-wrap">
            <table class="c-table s-gift-table">
                <thead class="c-table-thead s-gift-thead">
                <tr>
                <tr>
                    <th></th>
                    <th>序号</th>
                    <th>ID</th>
                    <th>昵称</th>
                    <th>房间号</th>
                    <th>手机号</th>
                </tr>
                </tr>
                </thead>
                <tbody class="c-table-tbody s-gift-tbody" id="tbody">

                </tbody>
            </table>
        </div>
        <p class="s-gift-count">共 <span class="count"></span> 条记录</p>
    </div>
    <div class="s-gift-table-pages"  id="pageBanner">
        <a disabled class="c-btn s-gift-page s-gift-prepage">.</a>
        <a class="c-btn s-gift-page" onclick="handPaging(1)">1</a>
        <a class="c-btn s-gift-page" onclick="handPaging(2)">2</a>
        <a class="c-btn s-gift-page" onclick="handPaging(3)">3</a>
        <a class="c-btn s-gift-page s-gift-nextpage">.</a>
        <!--<a href="" class="c-btn s-gift-page s-gift-prepage"></a>-->
    </div>
    <div>
        <button id="selectBtn">确定</button>
        <button id="cancelBtn">取消</button>
    </div>
</div>

<script>
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    function toObj(userId,roomId){
        var data = {};
        data[userId] = roomId;
        return data;
    }
    // var obj1 = toObj('name','nurdun');
    var dataObj = {};
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
        // console.log(dataObj);
    }

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
                        tbody += '<td><input type="checkbox" class="page'+pageNo+'_'+k+'" onclick="checkboxOnclick('+pageNo+','+k+','+v.id+','+v.roomId+',\''+v.nickName+'\')"/></td>';
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

    var data = {};
    var nickName = [];
    //选择用户确定
    $("#selectBtn").unbind('click').bind('click',function () {
        for(var key in dataObj){
            data[key] = dataObj[key][0];
            nickName.push(dataObj[key][1]);
        }
        console.log(dataObj);
        //使用nickName显示

        console.log(nickName);
        //使用data 推送消息
        console.log(data);

    });
    //选择用户取消
    $("#cancelBtn").unbind('click').bind('click',function () {
        dataObj = {};
    });

    //查询
    $("#searchBtn").unbind('click').bind('click',function () {

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
                    tbody += '<td><input type="checkbox" class="page'+pageNo+'_'+k+'" onclick="checkboxOnclick('+pageNo+','+k+','+v.id+','+v.roomId+',\''+v.nickName+'\')"/></td>';
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

</script>
