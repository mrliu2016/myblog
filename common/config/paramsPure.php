<?php
/**
 * 纯净版配置文件
 */
$params = [

    'local' => [
        'redisServer' => [
            'default' => [
                'host' => '47.94.92.113',
                'port' => 52301,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://dev.usercenter.3ttech.cn",
        'domain' => 'http://dev.api.customize.3ttech.cn',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://dev.api.customize.3ttech.cn/notify/notify-process',
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
            'wxAppId' => 'wx70a3358e75e061f7',
            'wxMchId' => '1440798702',
            'wxPayKey' => 'QxKjAppPw1357924QxKjAppPw1357924',
            'wxAppSecret' => '32d60c56204a0622f6895574cb240c25'
        ],
        'jsApi' => [
            'wxAppId' => 'wxc157967034c8f60b',
            'wxMchId' => '1487372672',
            'wxPayKey' => '49411f78bf21870f989aa1ace1cc0d0e',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '47.94.92.113',
                'domain' => 'dev.api.demo.3ttech.cn'
            ],
            [
                'ip' => '47.94.92.113',
                'domain' => 'dev.api.demo.3ttech.cn'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.3tlive.customize.cdn.3ttech.cn',
        'appName' => 'customize',
        'pushPullAuthorityKey' => 'customize',
        'cdnFactory' => 'aLiYun',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN，创世云CSY
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '用户',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'customize'
            ]
        ],
        'webSocketSSL' => [
            'key' => '/etc/nginx/cert/dev_api_demo.key',
            'pem' => '/etc/nginx/cert/dev_api_demo.pem',
        ]
    ],

    'dev' => [
        'redisServer' => [
            'default' => [
                'host' => '47.94.92.113',
                'port' => 52301,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://dev.usercenter.3ttech.cn",
        'domain' => 'http://dev.api.customize.3ttech.cn',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://dev.api.customize.3ttech.cn/notify/notify-process',
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
            'wxAppId' => 'wx70a3358e75e061f7',
            'wxMchId' => '1440798702',
            'wxPayKey' => 'QxKjAppPw1357924QxKjAppPw1357924',
            'wxAppSecret' => '32d60c56204a0622f6895574cb240c25'
        ],
        'jsApi' => [
            'wxAppId' => 'wxc157967034c8f60b',
            'wxMchId' => '1487372672',
            'wxPayKey' => '49411f78bf21870f989aa1ace1cc0d0e',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '47.94.92.113',
                'domain' => 'dev.api.demo.3ttech.cn'
            ],
            [
                'ip' => '47.94.92.113',
                'domain' => 'dev.api.demo.3ttech.cn'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.3tlive.customize.cdn.3ttech.cn',
        'appName' => 'customize',
        'pushPullAuthorityKey' => 'customize',
        'cdnFactory' => 'aLiYun',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN，创世云CSY
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '用户',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'customize'
            ]
        ],
        'webSocketSSL' => [
            'key' => '/etc/nginx/cert/dev_api_demo.key',
            'pem' => '/etc/nginx/cert/dev_api_demo.pem',
        ]
    ],

    'pre' => [
        'redisServer' => [
            'default' => [
                'host' => 'r-2ze622244eb20b94.redis.rds.aliyuncs.com',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://pre.usercenter.3ttech.cn",
        'domain' => 'http://api.live.3ttech.cn',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://api.live.3ttech.cn/notify/notify-process',
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
            'wxAppId' => 'wx70a3358e75e061f7',
            'wxMchId' => '1440798702',
            'wxPayKey' => 'QxKjAppPw1357924QxKjAppPw1357924',
            'wxAppSecret' => '32d60c56204a0622f6895574cb240c25'
        ],
        'jsApi' => [
            'wxAppId' => 'wxc157967034c8f60b',
            'wxMchId' => '1487372672',
            'wxPayKey' => '49411f78bf21870f989aa1ace1cc0d0e',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '47.94.9.121',
                'domain' => '3tshow.3ttech.cn'
            ],
            [
                'ip' => '47.94.9.121',
                'domain' => '3tshow.3ttech.cn'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.live.cdn.3ttech.cn',
        'appName' => 'pure',
        'pushPullAuthorityKey' => '3tlivepure',
        'cdnFactory' => 'aLiYun',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN，创世云CSY
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '用户',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => 'pure'
            ]
        ],
        'webSocketSSL' => [
            'key' => '/etc/nginx/cert/3tshow.3ttech.cn.key',
            'pem' => '/etc/nginx/cert/3tshow.3ttech.cn.pem',
        ]
    ],

    'online' => [
        'redisServer' => [
            'default' => [
                'host' => 'r-2ze622244eb20b94.redis.rds.aliyuncs.com',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://usercenter.3ttech.cn",
        'domain' => 'http://api.live.3ttech.cn',
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://api.live.3ttech.cn/notify/notify-process',
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
            'wxAppId' => 'wx70a3358e75e061f7',
            'wxMchId' => '1440798702',
            'wxPayKey' => 'QxKjAppPw1357924QxKjAppPw1357924',
            'wxAppSecret' => '32d60c56204a0622f6895574cb240c25'
        ],
        'jsApi' => [
            'wxAppId' => 'wxc157967034c8f60b',
            'wxMchId' => '1487372672',
            'wxPayKey' => '49411f78bf21870f989aa1ace1cc0d0e',
            'wxAppSecret' => ''
        ],
        'cdn' => [
            'hls' => 'zbj-pull2.3ttech.cn',
            'pull' => 'zbj-pull.3ttech.cn',
            'push' => 'zbj-push.3ttech.cn',
        ],
        'wsServer' => [
            [
                'ip' => '47.94.9.121',
                'domain' => '3tshow.3ttech.cn'
            ],
            [
                'ip' => '47.94.9.121',
                'domain' => '3tshow.3ttech.cn'
            ]
        ],
        'imageExt' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'],
        'liveUrl' => 'http://3tlivedemo.oss-cn-beijing.aliyuncs.com',
        'pullDomain' => 'ali.3tlive.cdn.3ttech.cn',
        'appName' => '3tlive',
        'pushPullAuthorityKey' => '3tlive',
        'cdnFactory' => 'aLiYun',  //腾讯云qCloud，阿里云aLiYun，星域xyCDN，创世云CSY
        'rongCloud' => [
            'appKey' => 'lmxuhwagli7cd',
            'appSecret' => 'ISg11JHsFd'
        ],
        'defaultAvatar' => 'http://3tlive.oss-cn-beijing.aliyuncs.com/publishlive/900019/IMG_20180516_115240.png',
        'defaultNickName' => '用户',
        'pullStream' => [
            'CSY' => [
                'domain' => 'push.huju-tech.com',
                'pushDomain' => 'rtmp://push.huju-tech.com',
                'pullRtmpDomain' => 'rtmp://pull.huju-tech.com',
                'pullM3u8Domain' => ' http://pull.huju-tech.com',
                'pullFlvDomain' => ' http://pull.huju-tech.com',
                'appName' => '3tlive'
            ]
        ],
        'webSocketSSL' => [
            'key' => '/etc/nginx/cert/3tshow.3ttech.cn.key',
            'pem' => '/etc/nginx/cert/3tshow.3ttech.cn.pem',
        ]
    ],

];

return $params[YII_ENV];