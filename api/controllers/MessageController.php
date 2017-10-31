<?php

namespace app\api\controllers;

use app\common\models\WeChatMessage;

class MessageController extends BaseController
{
    public function actionProcess()
    {
        $data = file_get_contents("php://input");
        $message['get'] = $_GET;
        $message['post'] = $data;
        ll($message, 'weChatMessage.log');
        if (isset($_GET['echostr'])) {
            WeChatMessage::valid();
        } else {
            WeChatMessage::responseMsg();
        }
    }
}