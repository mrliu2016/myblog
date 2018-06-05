<?php
use yii\widgets\LinkPager;
$this->title = '用户管理';
?>

<div class="container-fluid">
    <div class="s-gift">
        <div class="s-gift-search">
            <div class="s-gift-search-content">
                <div class="s-gift-search-item">
                    <span>ID</span>
                    <input class="c-input s-gift-search-input" type="text">
                </div>
                <div class="s-gift-search-item">
                    <span>昵称</span>
                    <input class="c-input s-gift-search-input" type="text">
                </div>
                <div class="s-gift-search-item">
                    <span>房间号</span>
                    <input class="c-input s-gift-search-input" type="text">
                </div>
                <div class="s-gift-search-item">
                    <span>手机号</span>
                    <input class="c-input s-gift-search-input" type="text">
                </div>
                <div class="s-gift-search-item">
                    <span>是否认证</span>
                    <span class="select-wrap">
					<select class="c-input s-gift-search-select" name="bursts" id="borsts" default="0">
						<option value="0">否</option>
						<option value="1">是</option>
					</select>
				  </span>
                </div>
                <div class="s-gift-search-item">
                    <span>状态</span>
                    <span class="select-wrap">
					<select class="c-input s-gift-search-select" name="bursts" id="borsts" default="0">
						<option value="1">启用</option>
                        <option value="0">禁用</option>
					</select>
				  </span>
                </div>
                <div class="s-gift-search-item">
                    <input type="text" style="width: 120px;display: inline-block" id="mobile" name="id" placeholder="请输入用户ID"
                           class="form-control">
                </div>
                <div class="s-gift-search-item">
                    <input type="text" style="width: 120px;display: inline-block" id="mobile" name="id" placeholder="请输入用户ID"
                           class="form-control">
                </div>
                <button class="c-btn u-radius--circle c-btn-primary s-gift-search-btn">查询</button>

            </div>
        </div>
        <!--<div class="s-gitf-operate">
            <button class="c-btn u-radius--circle c-btn-primary">新增</button>
            <a class="c-a s-gift-setting">设置连击</a>
        </div>-->
        <div class="s-gift-table-wrap">
            <table class="c-table s-gift-table">
                <thead class="c-table-thead s-gift-thead">
                <tr>
                <tr>
                    <th>序号</th>
                    <th>ID</th>
                    <th>昵称</th>
                    <th>性别</th>
                    <th>房间号</th>
                    <th>手机号</th>
                    <th>身份证</th>
                    <th>是否认证</th>
                    <th>粉丝数</th>
                    <th>账户余额/豆</th>
                    <th>收到礼物/币</th>
                    <th>送出礼物/豆</th>
                    <th>直播次数</th>
                    <th>被举报次数</th>
                    <th>注册时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </tr>
                </thead>
                <tbody class="c-table-tbody s-gift-tbody">
                <?php foreach ($itemList as $key => $item): ?>
                    <tr>
                        <td>
                            <?= $key+1 ?>
                        </td>
                        <td>
                            <?= $item['id'] ?>
                        </td>
                        <td>
                            <a href="/user/detail?id=<?=$item['id']?>"><?= $item['nickName'] ?></a>
                        </td>
                        <td>
                            <?= (isset($item['sex'])&&$item['sex']==1)?'男':'女'?>
                        </td>
                        <td>
                            <?= $item['roomId']?>
                        </td>
                        <td>
                            <?= $item['mobile'] ?>
                        </td>
                        <td>
                            <?= $item['idCard'] ?>
                        </td>
                        <td>
                            <?= (isset($item['isValid'])&&$item['isValid']==1)?'已认证':'未认证'?>
                        </td>
                        <td>
                            <?= $item['followers_cnt'] ?>
                        </td>
                        <td>
                            <?= $item['balance'] ?>
                        </td>
                        <td>
                            <?= $item['receiveValue'] ?>
                        </td>
                        <td>
                            <?= $item['sendValue'] ?>
                        </td>
                        <td>
                            <?= $item['liveCount'] ?>
                        </td>
                        <td>
                            <?= $item['reportCount'] ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i',$item['created']) ?>
                        </td>
                        <td>
                            <?= $item['income'] ?>
                        </td>
                        <td>
                            启用
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="s-gift-count">共 <?= $count ?> 条记录</p>

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

<script type="text/javascript">
    $("#searchBtn").click(function () {
        $("#searchForm").submit()
    });
    $("#cleanBtn").click(function () {
        $(this).closest('form').find("input[type=text]").val("")
    });
</script>
