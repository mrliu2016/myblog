<?php
use yii\widgets\LinkPager;
$this->title = '直播记录';
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="get" action="/live/live-record" class="form-horizontal" id="searchForm"
                  name="searchForm">
                <fieldset style="height: 20px">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="col-md-2">
                                <div class="col-md-2" style="display: flex;">
                                    <div class="query" style="white-space: nowrap;">
                                        ID <input type="text" style="width: 120px;display: inline-block" id="content" name="id" placeholder="请输入用户ID"
                                                  class="form-control"
                                            <?php if (!empty($params['id'])): ?>
                                                value="<?= $params['id'] ?>"
                                            <?php endif; ?>>
                                    </div>
                                    <div class="query" style="white-space: nowrap;">
                                        昵称<input type="text" style="width: 120px;display: inline-block;" id="nickName" name="nickName" placeholder="昵称"
                                                 class="form-control">
                                    </div>
                                    <div class="query" style="white-space: nowrap;">
                                        房间号<input type="text" style="width: 120px;display: inline-block" id="roomId" name="roomId" placeholder="房间号"
                                                  class="form-control">
                                    </div>
                                    <input type="text" style="width: 120px" id="startTime" name="startTime"
                                           class="form-control datepicker-pop">

                                    <!--直播时间-->
                                    <input type="text" style="width: 120px" id="endTime" name="endTime"
                                           class="form-control datepicker-pop">

                                    <button type="button" class="mb-sm btn btn-primary ripple" id="searchBtn"
                                            name="searchBtn">查询
                                    </button>
                                </div>
                        </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-md-1">序号</th>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-1">房间号</th>
                    <th class="col-md-1">主播昵称</th>
                    <th class="col-md-1">观众数</th>
                    <th class="col-md-1">开始时间</th>
                    <th class="col-md-1">结束时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemList as $key=>$item): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <?= $item['roomId'] ?>
                        </td>
                        <td>
                            <?= $item['nickName'] ?>
                        </td>
                        <td>
                            <?= $item['viewerNum']?>
                        </td>
                        <td>
                             <?= date('Y-m-d H:i', $item['created']) ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i', $item['updated']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <nav class="text-center">
        <table>
            <tr>
                <td> <?= LinkPager::widget(['pagination' => $pagination]) ?></td>
                <td>共<?= $count ?> 条</td>
            </tr>
        </table>
    </nav>

</div>

