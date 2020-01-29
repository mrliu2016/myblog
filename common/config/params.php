<?php

$params = [
    'local' => [
        'redisServer' => [
            'default' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
                'pwd' => '123456',
            ],
            'im' => [
                'host' => '127.0.0.1',
                'port' => 6380,
                'database' => 0,
                'pwd' => '123456',
            ],
        ],
        'domain' => 'http://local.api.comicmj.cn',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.3tlive.customize.cdn.3ttech.cn',
        'appName' => 'live',
        'pushPullAuthorityKey' => 'live',
        'cdnFactory' => 'aLiYun',
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '用户',
    ],

];

return $params[YII_ENV];
