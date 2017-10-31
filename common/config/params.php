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
            'ticket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='
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
        'appId' => 1,
        'pushQrCode' => 'http://userservice.oss-cn-beijing.aliyuncs.com/project_wechat/2017/10/13/11/1835_3171.jpg',
    ],

];

return $params[YII_ENV];
