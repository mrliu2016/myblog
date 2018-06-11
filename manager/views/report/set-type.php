<?php
$this->title="举报管理";
?>
<div class="container-fluid">
    <div><a href="#" class="edit">编辑</a> </div><a><a href="/report/report">返回</a></div>
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th>举报类型</th>
            </tr>
            </thead>
            <?php if(!empty($list)):?>
                <tbody>
                <?php foreach ($list as $key => $val){?>
                    <tr>
                        <input type="hidden" class="id" value="<?=$val['id']?>">
                        <td><input type="text" class="content" value="<?=$val['content']?>" readOnly></td>
                    </tr>
                <?php }?>
                <tr style="display: none;" class="updateSave">
                    <td colspan="2">
                        <input type="button" value="保存" class="save">
                    </td>
                </tr>
                </tbody>
            <?php else:?>
                <tbody>
                <tr>
                    <input type="hidden" class="id" value="1">
                    <td><input type="text" class="content"></td>
                </tr>
                <tr>
                    <input type="hidden" class="id" value="1">
                    <td><input type="text" class="content"></td>
                </tr>
                <tr>
                    <input type="hidden" class="id" value="1">
                    <td><input type="text" class="content"></td>
                </tr>
                <tr>
                    <input type="hidden" class="id" value="1">
                    <td><input type="text" class="content"></td>
                </tr>
                <tr>
                    <input type="hidden" class="id" value="1">
                    <td><input type="text" class="content"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="保存" class="save">
                        <input type="button" value="取消" class="cancel">
                    </td>
                </tr>
                </tbody>
            <?php endif ?>
        </table>
    </div>
</div>
</div>

<script>
    $(".save").unbind('click').bind('click',function () {
        var id = '';
        $(".id").each(function () {
            id = id + $(this).val()+',';
        });
        id=(id.substring(id.length-1)==',')?id.substring(0,id.length-1):id;

        var content='';
        $(".content").each(function(){
            content=content+ $(this).val()+',';
        });
        content=(content.substring(content.length-1)==',')?content.substring(0,content.length-1):content;

        var params = {};
        params.id = id;
        params.content = content;
        $.ajax({
            url: "/report/set-save",
            type: "post",
            cache: false,
            data:params,
            dataType: "json",
            success: function (data) {
                console.log(data);
                if(data.code == 0){
                    window.location.reload();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('error');
            }
        });
    });
    $(".cancel").unbind('click').bind('click',function () {
        $(".meaning").val("");
        $(".number").val("");
    });
    //编辑
    $(".edit").click(function () {
        //输入框可编辑
        $(".content").removeAttr('readOnly');
        $(".updateSave").css('display','block');
    });
</script>