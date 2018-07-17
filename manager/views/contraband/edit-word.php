<?php
$this->title = '违禁词编辑';
?>
<div>
    <table class="table table-hover">
        <tbody>
        <tr>
            <td>ID</td>
            <td id="id"><?= $id ?></td>
        </tr>
        <tr>
            <td>违禁词名称*:</td>
            <td><input type="text" placeholder="0-10个字符长度" id="word"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" value="确定" class="confirm">
                <input type="button" value="取消" class="cancel">
            </td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    $(".cancel").unbind('click').bind('click', function () {
        $("#word").val("");
    });

    $(".confirm").click(function () {
        var id = $("#id").text();
        var word = $("#word").val();
        if (word == undefined || word == '' || word == null) {
            alert('请输入正确的礼物名称.');
            return false;
        }
        if (parseFloat(price).toString() == "NaN") {
            alert('请输入正确的礼物价格.');
            return false;
        }
        var params = {};
        params.id = id;
        params.word = name;
        $.ajax({
            url: "/contraband/word-submit",
            type: "post",
            data: params,
            dataType: "json",
            success: function (data) {
                if (data != undefined && data.code == 0) {
                    window.location.href = '/contraband/list';
                }
                else {
                    alert('编辑失败！');
                }
            }
        });
    });

</script>

