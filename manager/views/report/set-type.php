<?php
$this->title="举报管理";
?>
<div class="s-accuse">
    <div class="s-accuse_title">举报管理</div>
    <a class="s-accuse_back" href="/report/index">返回</a>
    <div class="s-accuse_content">
        <h3 class="s-accuse_type-title">
            <span>举报类型</span>
            <button class="c-btn s-accuse_type-edit-btn">编辑</button>
        </h3>
        <?php if(!empty($list)):?>
        <div class="s-accuse_type-wrap">
            <?php foreach ($list as $key => $val){?>
                <input type="hidden" class="id" value="<?=$val['id']?>">
                <input type="text" class="c-input c-form_item-input s-accuse_type-input content" value="<?=$val['content']?>" readOnly>
            <?php }?>
                <div class="updateSave" style="display: none;">
                    <button class="c-btn s-accuse_confirm-btn">确认</button>
                </div>
            <?php else:?>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-accuse_type-input content" value="1"/>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-accuse_type-input content" value="1"/>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-accuse_type-input content" value="1"/>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-accuse_type-input content" value="1"/>
            <input type="hidden" class="id" value="1">
            <input class="c-input c-form_item-input s-accuse_type-input content" value="1"/>
        </div>
        <div class="s-accuse_operate">
            <button class="c-btn s-accuse_confirm-btn">确认</button>
            <button class="c-btn s-accuse_cancel-btn">取消</button>
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
    $(".s-accuse_confirm-btn").unbind('click').bind('click',function () {
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

        console.log(content);
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
    $(".s-accuse_cancel-btn").unbind('click').bind('click',function () {
        $(".meaning").val("");
        $(".number").val("");
    });
    //编辑
    $(".s-accuse_type-edit-btn").click(function () {
        //输入框可编辑
        $(".content").removeAttr('readOnly');
        $(".updateSave").css('display','block');
    });
</script>