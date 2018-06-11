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
                                <input type="file" class="form-control" id="File1" name="name" accept="*.xls,*.xlsx">
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
<script>
    // $("#searchBtn").click(function () {
    //     $("#searchForm").submit()
    // });
    //上传模板
    $("#upload").click(function (event) {
        //判断是否有文件
        var fileEl = $('#File1');
        if (typeof(fileEl[0].files[0])=='undefined'){
            fileEl[0].focus();
            // html = '<h4 style="color: red">请选择一个文件' + '</h4>';
            alert('请选择一个模板文件');
            event.preventDefault();
            return;
        }
        $("#searchForm").submit();
    });

    //下载模板
    $("#download").click(function () {
        alert(1);
        $('#downloadForm').attr('action', '/contraband/download-template');
        $("#downloadForm").submit()
    });
</script>