<?php
use yii\widgets\LinkPager;

$this->title = '直播列表';
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>直播列表</title>
</head>
<body>
<div class="main">
<ul class="live-list">
<li>
<a href="/live/video?rtmp=ces">
<img><span></span><span class="title">标题</span><span>房间号：</span><span>简介：</span><span>描述</span>
</a>
</li>
</ul>
</div>
</div>
<script>
$(document).ready(function(){
		if ($(this).is(':checked')) {
			$.ajax({
				type: 'get',
				url: 'http://api.live.3ttech.cn/live/hot',
				dataType: 'jsonp',
				timeout: 5000
			}).done(function (data) {
				if (data.code == 0) {
					

					});


				} else {

				}
			})
})
</script>
</body>