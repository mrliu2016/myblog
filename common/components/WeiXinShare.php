<?php

namespace app\common\components;

use Yii;

class WeiXinShare
{
    /**
     * @param $appId
     * @param $appSecret
     * @param bool $isShare
     * @return string
     */
    public static function share($appId, $appSecret, $isShare = true)
    {
        if (!$isShare) {
            return static::hideOptionMenu();
        }
        $accessToken = RedisClient::getInstance()->get($appId . '_ACCESS_TOKEN');
        if (empty($accessToken)) {
            $accessTokenInfo = WeiXinApi::getAccessToken($appId, $appSecret);
            $accessToken = $accessTokenInfo['access_token'];
            RedisClient::getInstance()->set($appId . '_ACCESS_TOKEN', $accessToken);
            RedisClient::getInstance()->expire($appId . '_ACCESS_TOKEN', $accessTokenInfo['expires_in']);
        }
        $ticket = static::getAuthorityTicket($appId, $accessToken);
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return static::createHtml(static::sign($appId, $ticket, $url));
    }

    /**
     * @param $appId
     * @param $accessToken
     * @return bool|string
     */
    protected static function getAuthorityTicket($appId, $accessToken)
    {
        $ticket = RedisClient::getInstance()->get($appId . '_JS_API_TICKET');
        if (empty($ticket)) {
            $weiXinConfig = Yii::$app->params['weiXin'];
            $url = $weiXinConfig['ticket'] . $accessToken . "&type=jsapi";
            CurlRequests::Instance()->setRequestMethod('GET');
            $result = json_decode(CurlRequests::Instance()->request($url, []), true);
            if ($result['errcode'] == 0 && $result['errmsg'] == 'ok') {
                RedisClient::getInstance()->set($appId . '_JS_API_TICKET', $result['ticket']);
                RedisClient::getInstance()->expire($appId . '_JS_API_TICKET', $result['expires_in']);
                return $result['ticket'];
            }
            return $ticket;
        }
        return $ticket;
    }

    /**
     * 签名
     *
     * @param $appId
     * @param $ticket
     * @param $url
     * @return array
     */
    protected static function sign($appId, $ticket, $url)
    {
        $timestamp = time();
        $nonceStr = rand(100000, 999999);
        $array = array(
            "noncestr" => $nonceStr,
            "jsapi_ticket" => $ticket,
            "timestamp" => $timestamp,
            "url" => $url,
        );
        ksort($array);
        $signPars = '';
        foreach ($array as $k => $v) {
            if ("" != $v && "sign" != $k) {
                if ($signPars == '') {
                    $signPars .= $k . "=" . $v;
                } else {
                    $signPars .= "&" . $k . "=" . $v;
                }
            }
        }
        return [
            'appId' => $appId,
            'timestamp' => $timestamp,
            'nonceStr' => $nonceStr,
            'url' => $url,
            'signature' => SHA1($signPars),
        ];
    }

    /**
     * @param $sign_data
     * @return string
     */
    protected static function createHtml($sign_data)
    {
        $html = <<<EOM
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
	<script type="text/javascript">
		wx.config({
		  debug: false,
		  appId: 	'{$sign_data['appId']}',
		  timestamp: {$sign_data['timestamp']},
		  nonceStr: '{$sign_data['nonceStr']}',
		  signature: '{$sign_data['signature']}',
		  jsApiList: [
	    	'checkJsApi',
		    'onMenuShareTimeline',
		    'onMenuShareAppMessage',
		    'onMenuShareQQ',
		    'onMenuShareWeibo',
			'openLocation',
			'getLocation',
			'addCard',
			'chooseCard',
			'openCard',
			'hideMenuItems'
		  ]
		});
	</script>
	<script type="text/javascript">
	wx.ready(function () {
	  // 2. 分享接口
	  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
	    wx.onMenuShareAppMessage({
			title: window.shareData.tTitle,
			desc: window.shareData.tContent,
			link: window.shareData.sendFriendLink,
			imgUrl: window.shareData.imgUrl,
		    type: '', // 分享类型,music、video或link，不填默认为link
		    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () {
		    },
		    cancel: function () {
		    }
		});
	  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
		wx.onMenuShareTimeline({
			title: window.shareData.fTitle?window.shareData.fTitle:window.shareData.tTitle,
			link: window.shareData.sendFriendLink,
			imgUrl: window.shareData.imgUrl,
		    success: function () {
		    },
		    cancel: function () {
		    }
		});	
	  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
		wx.onMenuShareWeibo({
			title: window.shareData.tTitle,
			desc: window.shareData.tContent,
			link: window.shareData.sendFriendLink,
			imgUrl: window.shareData.imgUrl,
		    success: function () {
		    },
		    cancel: function () { 
		    }
		});
		wx.error(function (res) {
			if(res.errMsg){
			}
		});
	});
</script>
EOM;
        return $html;
    }

    public static function hideOptionMenu()
    {
        return <<<EOM
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
    function onBridgeReady(){
        WeixinJSBridge.call('hideOptionMenu');
    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>
EOM;
    }
}