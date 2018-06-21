<?php
$this->title = '分享管理';
?>
<style>
    .menu{
        padding-top: 20px;
    }
    .edit{
        padding-top: 20px;
        padding-left: 20px;
        /*float: right;*/
    }
    .card{
        padding-top: 50px;
        padding-left: 20px;
    }
</style>
<div>
    <div class="menu">
        <a href="#" class="edit">编辑</a>
    </div>
    <div class="card">
        <?php if(!empty($item)):?>
            <input type="hidden" value="<?=$item['id']?>" id="id">
            分享标语*:<input type="text" value="<?= trim($item['title']) ?>" placeholder="0-20个字符" id="title" readonly><br/>
            分享引导语*：<br/>
            <textarea rows="10" cols="50" id="content" readonly>
                <?= trim($item['content']) ?>
            </textarea>
        <?php else:?>
            分享标语*:<input type="text" value="" placeholder="0-20个字符" id="title"><br/>
            分享引导语*：<br/>
            <textarea rows="10" cols="50" id="content">

            </textarea>
            <input type="button" value="保存" id="save">
        <?php endif ?>

        <br/>
        <input type="button" value="保存" id="save" style="display: none;">
    </div>
</div>

<script>
    $(".edit").unbind('click').bind('click',function () {

        $("#title").removeAttr('readOnly');
        $("#content").removeAttr('readOnly');
        $("#save").css("display","block");
    });

    $("#save").unbind('click').bind('click',function () {
        var id = $("#id").val();
        var title = $("#title").val();
        var content = $("#content").val();
        var params = {};
        params.id = id;
        params.title = title;
        params.content = content;
        $.ajax({
            url: "/share/title-save",
            type: "post",
            cache: false,
            data:params,
            dataType: "json",
            success: function (data) {
                if(data.code == 0){
                    window.location.reload();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('error');
            }
        });
    });
</script>