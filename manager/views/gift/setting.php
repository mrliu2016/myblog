<?php
$this->title="连发设置";
?>
<div class="s-gift-manage">
    <div class="s-gift-manage_title">礼物管理</div>
    <a class="s-gift-manage_back" href="/gift/index">返回</a>
    <div class="s-gift-manage_content">
        <h3 class="s-gift-manage_type-title">
            <span class="s-gift-manage_label">连发数量*</span>
            <span class="s-gift-manage_label">代表含义*</span>
            <button class="c-btn s-gift-manage_type-edit-btn">编辑</button>
        </h3>
        <?php if(!empty($list)):?>
            <?php foreach ($list as $key => $val){?>
                <input type="hidden" class="id" value="<?=$val['id']?>">
                <input type="text" class="c-input c-form_item-input s-gift-manage_type-input number" value="<?=$val['number']?>" readOnly>
                <span> — </span>
                <input type="text" class="c-input c-form_item-input s-gift-manage_type-input meaning" value="<?=$val['meaning']?>" readOnly>
                <br/>
            <?php }?>
            <div class="updateSave" style="display: none;">
                <button class="c-btn s-gift-manage_confirm-btn">确认</button>
            </div>
        <?php else:?>
        <div class="s-gift-manage_type-wrap">
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <span> — </span>
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <br>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <span> — </span>
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <br>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <span> — </span>
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <br>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <span> — </span>
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <br>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <span> — </span>
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <br>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
            <span> — </span>
            <input class="c-input c-form_item-input s-gift-manage_type-input" />
        </div>
        <div class="s-gift-manage_operate">
            <button class="c-btn s-gift-manage_confirm-btn">确认</button>
            <button class="c-btn s-gift-manage_cancel-btn">取消</button>
        </div>
        <?php endif ?>
    </div>
</div>

<!--确认是否删除start-->
<div id="confirm_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text">编辑失败！</span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".s-gift-manage_confirm-btn").unbind('click').bind('click',function () {
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
                else if(data.code == -1){
                    $("#confirm_frame").css("display","block");
                    $(".s-banlive-confirm").unbind('click').bind('click',function () {
                        $("#confirm_frame").css("display","none");
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('error');
            }
        });
    });
    $(".s-gift-manage_cancel-btn").unbind('click').bind('click',function () {
        $(".meaning").val("");
        $(".number").val("");
    });
    //编辑
    $(".s-gift-manage_type-edit-btn").click(function () {

        $(".number").removeAttr('readOnly');
        $(".meaning").removeAttr('readOnly');
        $(".updateSave").css('display','block');

    });
</script>