<?php
$this->title = '新增违禁词';
?>
<div class="s-robot-form">
    <div class="s-robot-form_title">用户详情<span style="float: right;"><a href="/contraband/list">返回</a></span></div>
    <div class="s-robot-form-details">
        <p class="c-form_item">
            <span class="c-form_item-title">违禁词：</span>
            <input class="c-input c-form_item-input" placeholder="0-10个字符长度" id="word" autocomplete="off"/>
        </p>
    </div>
    <div>
        <button class="c-btn" id="confirm">确定</button>
        <button class="c-btn" id="cancel">取消</button>
    </div>
</div>
<script>
$("#confirm").click(function(){
    var word = $("#word").val();
    if(word == undefined || word == '' || word == null || word.length >10){
        alert('请输入正确的违禁词');
        return false;
    }
    var params  = {};
        params.word  = word;
        $.ajax({
            url: "/contraband/add-save",
            type: "post",
            data: params,
            dataType: "json",
            success: function (data) {
                if(data != undefined && data.code == 0){
                    window.location.href='/contraband/list';
                }
                else{
                    alert('新增失败！');
                }
            }
        });
    });
    $("#cancel").click(function () {
        $("#word").val("");
    });
</script>