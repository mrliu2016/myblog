<?php

namespace app\console\controllers;

use app\common\components\RedisClient;
use app\common\components\WeiXinTemplate;
use app\common\models\Application;
use app\common\models\User;
use app\common\services\Constants;
use yii\console\Controller;
use app\common\services\Base64JsonConvert;
use yii\db\Exception;

class TemplateController extends Controller
{
    /**
     * 发送队列模板
     */
    public function actionSendTemplate()
    {
        try {
            $redis = RedisClient::getInstance();
            $flag = true;
            while ($flag) {
                $message = $redis->rpop(Constants::TASK_QUEUE_NOTICE);
                if (!empty($message)) {
                    $message = Base64JsonConvert::base64ToJsonDecode($message);
                    ll($message, "send_template.log");
                    if (!empty($message)) {
                        $application = Application::queryById($message['appId']);
                        $user = User::queryByUnionId($message['unionId']);
                        $params = [
                            'openId' => $user['openId'],
                            'template_id' => 'oMYEaqmJjAFA3oBugI6O5-RK6HmOoE86LjZfq8OY9ps',
                            'topcolor' => '#FF0000',
                            'url' => $message['url'],
                            'first' => $message['description'],
                            'keyword1' => $message['name'],
                            'keyword2' => $message['taskType'],
                            'remark' => $message['remark']
                        ];
                        $result = WeiXinTemplate::processTemplate($application['wxAppId'], $application['wxAppSecret'], $params);
                        if (!empty($result)) {
                            $result = json_decode($result, true);
                            if ($result['errcode'] != Constants::CODE_SUCCESS && $result['errmsg'] != 'ok') {
                                $redis->lpush(Constants::TASK_QUEUE_NOTICE, Base64JsonConvert::jsonToBase64Encode($message));
                            }
                        }
                    }
                } else {
                    $flag = false;
                }
            }
        } catch (Exception $exception) {
            ll($exception->getMessage(), "send_template.log");
        }
    }
}