<?php
$this->title = '消息管理';
?>

<div class="s-message-push-wrap">
    <div class="s-message-push">
        <p class="s-message-push_title">消息推送</p>
        <div class="s-message-push_content">
            <textarea class="c-input u-radius--0 s-message-push_input" id="message"></textarea>
            <div class="s-message-push_select-wrap">
                <button class="c-btn c-btn-dull s-message-push_selectall">全部用户</button>
                <!--<button class="c-btn c-btn-dull s-message-push_selectuser">选择用户</button>-->
                <a href="/message/select-page" class="c-btn c-btn-dull s-message-push_selectuser">选择用户</a>

            </div>
            <div>
                <div class="s-message-push_user">
                    <span>昵称-12345</span>
                    <button class="c-btn-circle-close s-message-push_userremote"></button>
                </div>
                <div class="s-message-push_user">
                    <span>昵称-12345</span>
                    <button class="c-btn-circle-close s-message-push_userremote"></button>
                </div>
                <div class="s-message-push_user">
                    <span>昵称-12345</span>
                    <button class="c-btn-circle-close s-message-push_userremote"></button>
                </div>
                <div class="s-message-push_user">
                    <span>昵称-12345</span>
                    <button class="c-btn-circle-close s-message-push_userremote"></button>
                </div>
            </div>
            <button class="c-btn c-btn-primary u-radius--circle s-message-push_submit">发送</button>
        </div>
    </div>
</div>



<script type="text/javascript">

    $("#selectUser").click(function () {

    });
    var data = {};
    $(".s-message-push_submit").unbind('click').bind('click',function () {

        var message = $("#message").val();
        var params = {};
        // params.userId = 800060;
        // params.roomId = 100008;
        params.data = data;
        params.message = message;
        // params.roomId = '';


        $.ajax({
            url: "/message/message",
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

</script>
