<?php
$this->title = '鉴黄管理';
?>
<body>
<!-- Create the editor container -->
<div class="s-accuse-manage">
    <div class="s-accuse-manage_title">
        <span>鉴黄管理</span>
        <a class="s-accuse-manage_back" href="/live/yellow">返回</a>
    </div>
    <div class="s-accuse-manage_content">
        <ul class="s-am_list">
            <li class="s-am_item">
                <div class="s-am_item-img"><img src="<?=$yellowurl?>"/></div>
                <div class="s-am_item-info">
						<span class="s-am_item-tag">
							<span>标签：</span>
							<span><?=$information['Label']?></span>
						</span>
                    <span class="s-am_item-scene">
							<span>场景：</span>
							<span><?=$information['Scene']?></span>
						</span>
                </div>
            </li>
        </ul>
    </div>
</div>