<?php

namespace app\api\controllers;

use app\common\models\Video;

class IdentifyController extends BaseController
{
    public function actionIdentify()
    {
        $params = file_get_contents("php://input");
        if (!empty($params)) {
            $params = json_decode($params, true);
        } else {
            $params = [];
        }
        ll(var_export($params, true),'checkYellow.log');
        Video::identify(array_merge($params, $_POST));
    }
}