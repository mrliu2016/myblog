<?php
$this->title = '上传文件';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="post" action="/robot/batch-add" class="form-horizontal" id="searchForm"
                  name="searchForm" enctype="multipart/form-data">
                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-3">
                                <input type="file" class="form-control" id="name" name="name" accept="*.csv,*.xls,*.xlsx">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button type="submit" class="mb-sm btn btn-primary ripple">提交</button>
                        </div>
                    </div>
                </fieldset>
            </form>

            <form action="/robot/download-template" id="downloadForm">
                <button type="submit" class="mb-sm btn btn-primary ripple" class="download">下载</button>
            </form>

        </div>
    </div>
</div>
<script>
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    //导出EXCEL
    $("#download").click(function () {
        alert(1);
        $('#downloadForm').attr('action', '/robot/download-template');
        $("#downloadForm").submit()
    });
</script>