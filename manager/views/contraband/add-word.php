<?php
$this->title = '违禁词管理';
?>
<div class="s-robot-form">
    <div class="s-robot-form_title">新增违禁词<span style="float: right;"><a href="/contraband/list">返回</a></span></div>
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

<!--提示语start-->
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
    $("#confirm").click(function () {
        var word = $("#word").val();
        if (word == undefined || word == '' || word == null || word.length > 10) {
            tip('请输入正确的违禁词');
            return false;
        }
        var params = {};
        params.word = word;
        $.ajax({
            url: "/contraband/add-save",
            type: "post",
            data: params,
            dataType: "json",
            success: function (data) {
                if (data != undefined && data.code == 0) {
                    window.location.href = '/contraband/list';
                }
                else {
                    tip("新增违禁词失败！");
                }
            }
        });
    });
    $("#cancel").click(function () {
        $("#word").val("");
    });

    function tip(message) {
        $("#tip_frame").css("display", "block");
        $(".s-banlive-confirm-text").text(message);
        $(".s-banlive-confirm").unbind("click").bind("click", function () {
            $("#tip_frame").css("display", "none");
        });
    }
</script>