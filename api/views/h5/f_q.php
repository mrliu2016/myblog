<?php
$this->title = '问题咨询';
?>
<style>
    p{
        margin-bottom:5px;
        margin-top:5px;
    }
    .task-list{
        background-color: #fff;
        border-radius: 8px;
        margin-top:15px;
        padding-left: 30px;
        font-size: 1em;
        line-height: 2em;
        margin-bottom:15px;

    }
    .task-list h4{
        font-size:1.5em;
    }
    .task-list ul li{
        list-style: none;
        padding-left:2em;
    }
    .task-name{
        margin-left:0px;
        margin-right:15px;
    }
</style>
<div class="container">
    <div class="weui-tab__bd">
        <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
            <div class="task-list">
                <div class="row task-name">Q&A: </div>
                <ul>
                    <li>问题1</li>
                    <li>问题2</li>
                    <li>问题3</li>
                    <li>问题4</li>
                </ul>
            </div>
            <div class="task-list">
                <div class="row task-name">人工客户：10:00-21.00加微信咨询</div>
                <div style="text-align: center; margin-top:30px">
                    <img src="/img/erwei.png" alt="" class="img-rounded erwei">
                </div>
            </div>
        </div>
    </div>
</div>
