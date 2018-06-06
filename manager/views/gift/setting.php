<?php
$this->title="连发设置";
?>
<div class="container-fluid">
    <div>编辑</div><a><a href="/gift/template">返回</a></div>
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>连发礼物*</th>
                        <th>代表含义*</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <?php if(!empty($list)):?>
                <tbody>
                    <?php foreach ($list as $key => $val){?>
                    <tr>
                        <input type="hidden" class="id" value="<?=$val['id']?>">
                        <td><input type="text" class="number" value="<?=$val['number']?>" readOnly></td>
                        <td><input type="text" class="meaning" value="<?=$val['meaning']?>" readOnly></td>
                        <td>
                            <a href="#" class="edit">编辑</a>
                            <!--<a href="#" class="updateSave" style="display: none;">保存</a>-->
                        </td>
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
                        <td><input type="text" class="number"></td>
                        <td><input type="text" class="meaning"></td>
                    </tr>
                    <tr>
                        <input type="hidden" class="id" value="1">
                        <td><input type="text" class="number"></td>
                        <td><input type="text" class="meaning"></td>
                    </tr>
                    <tr>
                        <input type="hidden" class="id" value="1">
                        <td><input type="text" class="number"></td>
                        <td><input type="text" class="meaning"></td>
                    </tr>
                    <tr>
                        <input type="hidden" class="id" value="1">
                        <td><input type="text" class="number"></td>
                        <td><input type="text" class="meaning"></td>
                    </tr>
                    <tr>
                        <input type="hidden" class="id" value="1">
                        <td><input type="text" class="number"></td>
                        <td><input type="text" class="meaning"></td>
                    </tr>
                    <tr>
                        <input type="hidden" class="id" value="1">
                        <td><input type="text" class="number"></td>
                        <td><input type="text" class="meaning"></td>
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

        var number='';
        $(".number").each(function(){
            reg = /^[0-9]+.?[0-9]*$/;
            if (!reg.test($(this).val())) {
                alert('输入有误');
                return false;
            }
            number=number+ $(this).val()+',';
        });
        number=(number.substring(number.length-1)==',')?number.substring(0,number.length-1):number;
        var meaning='';
        $(".meaning").each(function(){
            meaning=meaning+ $(this).val()+',';
        });
        meaning=(meaning.substring(meaning.length-1)==',')?meaning.substring(0,meaning.length-1):meaning;

        var params = {};
        params.id = id;
        params.number = number;
        params.meaning = meaning;
        $.ajax({
            url: "/gift/setting-save",
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
        // $(this).parent('td').siblings('td').children('input').attr('readOnly','true');
        $(this).parent('td').siblings('td').children('input').removeAttr('readOnly');
        // $(this).siblings('a').css('display','block');
        $(".updateSave").css('display','block');
    });
</script>