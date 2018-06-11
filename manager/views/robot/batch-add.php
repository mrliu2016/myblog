<?php
$this->title = '上传文件';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="post" action="/robot/batch-add" class="form-horizontal" id="uploadForm"
                  name="uploadForm" enctype="multipart/form-data">
                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-3">
                                <input type="file" class="form-control" id="selectTemplate" name="name" accept="*.csv,*.xls,*.xlsx">
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
            <form action="/robot/download-template" id="downloadForm">
                <button type="submit" class="mb-sm btn btn-primary ripple" class="download">下载</button>
            </form>

        </div>
    </div>
</div>
<script>
    $("#upload").click(function () {
        var fileEl = $('#selectTemplate');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            alert('请选择一个模板文件');
            event.preventDefault();
            return;
        }
        $("#uploadForm").submit();
    });
    //导出EXCEL
    $("#download").click(function () {
        $('#downloadForm').attr('action', '/robot/download-template');
        $("#downloadForm").submit()
    });
</script>