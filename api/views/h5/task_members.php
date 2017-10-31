<?php
$this->title = '任务团成员';
?>
<style>
    .weui-grid {
        width: 20%;
        padding:5px 2px;
    }
    .weui-grid img{
        border-radius: 10px;
    }

    .weui-grid:before {
        content: none;
    }

    .weui-grid:after {
        border: none;
    }

    .weui-grids:after {
        border: none;
    }

    .weui-grids:before {
        content: none;
    }
</style>
<div class="container">
    <h4 style="margin:30px auto;">任务团成员</h4>
    <div class="weui-grids">

    </div>
</div>
<script>
    $(document).ready(function () {
        var taskid=getUrlParam("taskid");
        $.ajax({
            type:"get",
            url:'/task/task-member?taskId='+taskid,
            dataType:"json",
            success:function (data) {
                var info=eval(data);
                if(info.code==0){
                    var data=info.data;
                    var members=data.list;
                    var length=members.length;
                    for(i=0;i<length;i++){
                        var html='';
                        html+='<a href="" class="weui-grid js_grid"><div class="weui-grid__icon">';
                        html+='<img src="'+members[i].avatar+'" alt=""> </div> <p class="weui-grid__label">'+members[i].nickName+'</p></a>';
                        $(".weui-grids").append(html);
                    }

                }

            }
        })

    })
</script>
