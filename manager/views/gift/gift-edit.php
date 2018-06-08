<?php
$this->title = '编辑礼物';
?>
<style>
    .gift-edit{
        padding-top: 20px;
        padding-left: 20px;
    }
</style>
<div class="gift-edit">
    <div class="img">

    </div>
    <div>
        <table class="table table-hover">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td id="id"><?= $item['id'] ?></td>
                </tr>
                <tr>
                    <td>礼物名称*:</td>
                    <td><input type="text" placeholder="0-5个字符长度" id="name" value="<?=$item['name']?>"/></td>
                </tr>
                <tr>
                    <td>价格</td>
                    <td><input type="text" id="price" value="<?=$item['price']?>"/>豆</td>
                </tr>
                <tr>
                    <td>是否可以连发</td>
                    <td>
                        <input type="radio" value="1" name="fire">是
                        <input type="radio" value="0" name="fire" checked>否
                    </td>
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
</div>

<script>
    $(".confirm").click(function(){

        var id = $("#id").text();
        var name = $("#name").val();
        var price = $("#price").val();
        if(name == undefined || name == '' || name == null || name.length >5){
            alert('请输入正确的礼物名称.');
            return false;
        }
        if(parseFloat(price).toString() == "NaN"){
            alert('请输入正确的礼物价格.');
            return false;
        }

        var params  = {};
        params.id    = id;
        params.name  = name;
        params.price = price;
        params.isFire = $('input:radio:checked').val();

        $.ajax({
            url: "/gift/gift-submit",
            type: "post",
            data: params,
            dataType: "json",
            success: function (data) {
                if(data != undefined && data.code == 0){
                    window.location.href='/gift/template';
                }
                else{
                    alert('编辑失败！');
                }
            }
        });
    });
    $(".cancel").click(function () {
        $("#name").val("");
        $("#price").val("");
        $("input[name=fire]:eq(1)").attr("checked",'checked');
    });
</script>


