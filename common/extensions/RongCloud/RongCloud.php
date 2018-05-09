<?php
/**
 * 融云 Server API PHP 客户端
 * create by kitName
 * create datetime : 2016-09-05
 *
 * v2.0.1
 */

namespace app\common\extensions\RongCloud;

use app\common\extensions\RongCloud\methods\Chatroom;
use app\common\extensions\RongCloud\methods\Group;
use app\common\extensions\RongCloud\methods\Message;
use app\common\extensions\RongCloud\methods\Wordfilter;
use app\common\extensions\RongCloud\methods\Push;
use app\common\extensions\RongCloud\methods\SMS;
use app\common\extensions\RongCloud\methods\User;
use \Exception;

class RongCloud
{
    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct($appKey, $appSecret, $format = 'json')
    {
        $this->SendRequest = new SendRequest($appKey, $appSecret, $format);
    }

    public function User()
    {
        $User = new User($this->SendRequest);
        return $User;
    }

    public function Message()
    {
        $Message = new Message($this->SendRequest);
        return $Message;
    }

    public function Wordfilter()
    {
        $Wordfilter = new Wordfilter($this->SendRequest);
        return $Wordfilter;
    }

    public function Group()
    {
        $Group = new Group($this->SendRequest);
        return $Group;
    }

    public function Chatroom()
    {
        $Chatroom = new Chatroom($this->SendRequest);
        return $Chatroom;
    }

    public function Push()
    {
        $Push = new Push($this->SendRequest);
        return $Push;
    }

    public function SMS()
    {
        $SMS = new SMS($this->SendRequest);
        return $SMS;
    }

}