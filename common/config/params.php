<?php

$params = [

    'local' => [
        'redisServer' => [
            'default' => [
                'host' => '10.66.183.163',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Pkq!%#2018%*',
            ],
        ],
        'ucDomain' => "http://dev.usercenter.3ttech.cn",
        'domain' => 'http://118.25.15.37',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://118.25.15.37/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=',
            'webAccessToken' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
            'webOAuth' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
            'userInfo' => 'https://api.weixin.qq.com/cgi-bin/user/info',
            'webOAuthUserInfo' => 'https://api.weixin.qq.com/sns/userinfo',
        ],
        'app' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'jsApi' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '118.25.15.37',
                'domain' => '118.25.15.37'
            ],
            [
                'ip' => '118.25.15.37',
                'domain' => '118.25.15.37'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.tianxiang.cdn.3ttech.cn',
        'appName' => 'tianxiang',
        'pushPullAuthorityKey' => 'tianxiang',
        'cdnFactory' => 'CSY',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '天象互动',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'tianxiang'
            ]
        ]
    ],

    'dev' => [
        'redisServer' => [
            'default' => [
                'host' => '10.66.183.163',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Pkq!%#2018%*',
            ],
        ],
        'ucDomain' => "http://dev.usercenter.3ttech.cn",
        'domain' => 'http://118.25.15.37',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://118.25.15.37/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=',
            'webAccessToken' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
            'webOAuth' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
            'userInfo' => 'https://api.weixin.qq.com/cgi-bin/user/info',
            'webOAuthUserInfo' => 'https://api.weixin.qq.com/sns/userinfo',
        ],
        'app' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'jsApi' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '118.25.15.37',
                'domain' => '118.25.15.37'
            ],
            [
                'ip' => '118.25.15.37',
                'domain' => '118.25.15.37'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.tianxiang.cdn.3ttech.cn',
        'appName' => 'tianxiang',
        'pushPullAuthorityKey' => 'tianxiang',
        'cdnFactory' => 'CSY',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '天象互动',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'tianxiang'
            ]
        ]
    ],

    'pre' => [
        'redisServer' => [
            'default' => [
                'host' => '10.66.183.163',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Pkq!%#2018%*',
            ],
        ],
        'ucDomain' => "http://pre.usercenter.3ttech.cn",
        'domain' => 'http://118.25.93.124',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://118.25.93.124/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=',
            'webAccessToken' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
            'webOAuth' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
            'userInfo' => 'https://api.weixin.qq.com/cgi-bin/user/info',
            'webOAuthUserInfo' => 'https://api.weixin.qq.com/sns/userinfo',
        ],
        'app' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'jsApi' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '118.25.93.124',
                'domain' => '118.25.93.124'
            ],
            [
                'ip' => '118.25.93.124',
                'domain' => '118.25.93.124'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.tianxiang.cdn.3ttech.cn',
        'appName' => 'tianxiang',
        'pushPullAuthorityKey' => 'tianxiang',
        'cdnFactory' => 'CSY',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '天象互动',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'tianxiang'
            ]
        ]
    ],

    'online' => [
        'redisServer' => [
            'default' => [
                'host' => '10.66.183.163',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Pkq!%#2018%*',
            ],
        ],
        'ucDomain' => "http://usercenter.3ttech.cn",
        'domain' => 'http://118.25.93.124',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://118.25.93.124/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=',
            'webAccessToken' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
            'webOAuth' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
            'userInfo' => 'https://api.weixin.qq.com/cgi-bin/user/info',
            'webOAuthUserInfo' => 'https://api.weixin.qq.com/sns/userinfo',
        ],
        'app' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'jsApi' => [
            'wxAppId' => '',
            'wxMchId' => '',
            'wxPayKey' => '',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '118.25.93.124',
                'domain' => '118.25.93.124'
            ],
            [
                'ip' => '118.25.93.124',
                'domain' => '118.25.93.124'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.tianxiang.cdn.3ttech.cn',
        'appName' => 'tianxiang',
        'pushPullAuthorityKey' => 'tianxiang',
        'cdnFactory' => 'CSY',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '天象互动',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'tianxiang'
            ]
        ]
    ],

];

return $params[YII_ENV];
