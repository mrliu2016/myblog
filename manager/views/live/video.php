<?php
use yii\widgets\LinkPager;

$this->title = '直播间';
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>直播间</title>
</head>
<body>
<div class="main">
<div class="live-video">
 <div id="id_test_video" style="width:100%; height:auto;"></div>
</div>
</div>
<script>
var player =  new TcPlayer('id_test_video', {
"rtmp": "#",
"autoplay" : true,      //iOS下safari浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
"width" :  '640',//视频的显示宽度，请尽量使用视频分辨率宽度
"height" : '480'//视频的显示高度，请尽量使用视频分辨率高度
});</script>
</body>