<?php
$this->title = '创建任务团';
?>
<style>
    p {
        margin-bottom: 0px;
    }

    .container {
        background-color: #f5f5f5;
    }

    .list-center {
        text-align: center;
        width: 100%;
    }

    .list-center img {
        width: 250px;
        height: 250px;
    }

    .list-center p {
        line-height: 80px;
        font-size: 1.2em;
    }

    .container {
        padding: 0;
    }

    .weui-time {
        width: 70%;
    }

    .task-intro {
        padding: 20px 15px;
        line-height: 2em;
        font-size: 1em;

    }
</style>
<script src="/js/date.js"></script>
<div class="container" style="display: block">
    <div class="list-center">
        <?php if (!empty($qrCode)): ?>
            <img width="100" height="100" src="<?= $qrCode ?>">
        <?php endif; ?>
        <p><?= $applicationName ?></p>
    </div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">任务团名称</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="最多20个字" id="taskname" maxlength="20">
            </div>
        </div>
        <div class="weui-cell weui-cell_select weui-cell_select-after">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">契约金</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select" name="select2" id="taskmoney">
                    <option value="0.01">0.01</option>
                    <option value="50">50</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                </select>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label for="" class="weui-label">日期</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="date" value="" id="tasktime">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label for="" class="weui-label">持续时间</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input weui-time" type="number" value="" placeholder="单行输入" id="task-list"><span>(天)</span>
            </div>
        </div>

    </div>
    <div class="task-intro">
        <div class="weui-cells__title">任务描述</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <textarea class="weui-textarea" placeholder="请输入文本" rows="3" maxlength="200" id="description" ></textarea>

                </div>
            </div>
        </div>
    </div>
    <div id="ces"></div>
    <a href="javascript:;" class="weui-btn weui-btn_primary next-but" style="margin-top: 100px;">创建任务团</a>
    <div class="task-intro">
        <p style="text-align: center">设置任务 > 支付契约金 > 邀请成员</p>
    </div>

</div>
<div class="weui-msg" style="display: none">
  <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
  <div class="weui-msg__text-area">
    <h2 class="weui-msg__title">创建成功</h2>
  </div>
  <div class="weui-msg__opr-area">
    <p class="weui-btn-area">
      <a href="javascript:;" class="weui-btn weui-btn_primary task-next">下一步</a>
    </p>
  </div>

</div>
<script>

    $(".next-but").on("click",function () {
        var name=$("#taskname").val();
        var price=$("#taskmoney").val();
        var starts=$("#tasktime").val();
        var descripe=$("#description").val();
        var startTime=$.myTime.DateToUnix(''+starts+'');
        var nums=$("#task-list").val();
        var endTime =nums*3600*24 +startTime;
        var aid=getUrlParam("aid");
        var unionId=getUrlParam("unionid");
        if(name==''){
            $.alert("任务名称不能为空");
        }else if(price==''){
            $.alert("契约金不能为空");
        }else if(starts==''){
            $.alert("开始时间不能为空");
        }else if(endTime==''){
            $.alert("持续时间不能为空");
        }else if(descripe==''){
            $.alert("任务描述不能为空");
        }else{
            $.ajax({
                    type:"post",
                    dataType:"json",
                    url:"http://dev.api.wechat.3ttech.cn/task/create-task",
                    data:{
                        name:name,
                        unionId:unionId,
                        price:price,
                        startTime:startTime,
                        endTime:endTime,
                        aid:aid,
                        days:nums,
                        description:descripe


                    },
                    success:function (data) {
                        info = eval(data);
                        if(info.code==0){
                            $(".container").hide();
                            $(".weui-msg").show();
                            var taskid=info.data.taskId;
                            $('.task-next').attr('href',"/h5/pay?unionId="+unionId+"&aid="+aid+"&taskid="+taskid+"&price="+price);

                            //var stateObject = {};
                            //var title = "支付契约金";
                            //var newUrl = "http://dev.api.wechat.3ttech.cn/h5/pay?unionId="+unionId+"&aid="+aid;
                            // history.pushState(stateObject,title,newUrl);

                        }

                    },

                }
            )
        }



    });

</script>
