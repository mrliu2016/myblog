<?php

namespace app\common\extensions\WeiXinPay\LIb;

use Exception;

class WxPayException extends Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}
