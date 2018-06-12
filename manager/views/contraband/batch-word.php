<?php
$this->title = '上传文件';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="post" action="/contraband/batch-word" class="form-horizontal" id="searchForm"
                  name="searchForm" enctype="multipart/form-data">
                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-3">
                                <input type="file" class="form-control" id="File1" name="name" accept="*.xls">
                            </div>
                        </div>
                    </div>

                </fieldset>
            </form>

            <div class="form-group">
                <div class="col-sm-10">
                    <button type="submit" class="mb-sm btn btn-primary ripple" id="upload">提交</button>
                </div>
            </div>
            <form action="/contraband/download-template" id="downloadForm">
                <button type="submit" class="mb-sm btn btn-primary ripple" class="download">下载</button>
            </form>
        </div>
    </div>
</div>

<!--确认start-->
<div id="confirm_frame" style="display: none">
    <div class="c-modal-mask"></div>
    <div class="c-modal-wrap s-banlive">
        <div class="c-modal">
           <!-- <div class="c-modal-close s-banlive-close">关闭</div>-->
            <div class="s-banlive-content">
                <span class="s-banlive-confirm-text"></span>
            </div>
            <div class="c-modal-footer s-banlive-operate">
                <button class="c-btn c-btn-primary c-btn--large s-banlive-confirm">确认</button>
                <!--<button class="c-btn c-btn--large s-banlive-cancel">取消</button>-->
            </div>
        </div>
    </div>
</div>
<!--确认end-->
<script>
    //上传模板
    $("#upload").click(function (event) {
        //判断是否有文件
        var fileEl = $('#File1');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            $("#confirm_frame").css("display","block");
            $(".s-banlive-confirm-text").text("请选择一个正确的模板文件!");
            $(".s-banlive-confirm").unbind("click").bind("click",function () {
                $("#confirm_frame").css("display","none");
            });
            event.preventDefault();
            return;
        }

        var filePath = $('#File1').val().toLowerCase().split(".");
        var fileType =  filePath[filePath.length - 1];
        if(fileType == "xls"){
            $("#searchForm").submit();
        }
        else{
            $("#confirm_frame").css("display","block");
            $(".s-banlive-confirm-text").text("请选择.xls格式的文件!");
            $(".s-banlive-confirm").unbind("click").bind("click",function () {
                $("#confirm_frame").css("display","none");
            });
        }
    });
    //下载模板
    $("#download").click(function () {
        $('#downloadForm').attr('action', '/contraband/download-template');
        $("#downloadForm").submit()
    });
</script>