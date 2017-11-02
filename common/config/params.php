<?php

$params = [

    'local' => [
        'redisServer' => [
            'default' => [
                'host' => '47.94.92.113',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://dev.usercenter.3ttech.cn",
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://dev.api.wechat.3ttech.cn/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=',
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
        'appId' => 1,
        'pushQrCode' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/10/13/11/1835_3171.jpg',
    ],

    'dev' => [
        'redisServer' => [
            'default' => [
                'host' => '47.94.92.113',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://dev.usercenter.3ttech.cn",
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://dev.api.wechat.3ttech.cn/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='
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
        'appId' => 1,
        'pushQrCode' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/10/13/11/1835_3171.jpg',
    ],

    'pre' => [
        'redisServer' => [
            'default' => [
                'host' => '47.93.31.116',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://pre.usercenter.3ttech.cn",
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://api.wechat.3ttech.cn/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='
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
        'appId' => 1,
        'pushQrCode' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/10/13/11/1835_3171.jpg',
    ],

    'online' => [
        'redisServer' => [
            'default' => [
                'host' => '47.93.31.116',
                'port' => 6379,
                'database' => 0,
                'pwd' => 'Lining123',
            ],
        ],
        'ucDomain' => "http://usercenter.3ttech.cn",
        'oss' => [
            'accessKeyId' => 'LTAIIYGRmx5qmxkk',
            'accessKeySecret' => 'AfV0Hy6uII76bc0lEiMCJYgTKDHbN1',
            'bucket' => 'userservice',
            'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        ],
        'weiXin' => [
            'unifiedOrder' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
            'orderQuery' => 'https://api.mch.weixin.qq.com/pay/orderquery',
            'notifyUrl' => 'http://api.wechat.3ttech.cn/notify/notify-process',
            'transfers' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
            'template' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=',
            'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='
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
        'appId' => 1,
        'pushQrCode' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/10/13/11/1835_3171.jpg',
    ],

];

return $params[YII_ENV];
