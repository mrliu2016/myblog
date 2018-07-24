<?php

namespace app\manager\controllers;

use app\common\components\AHelper;
use app\common\components\CdnUtils;
use app\common\components\RedisClient;
use app\common\models\User;
use app\common\models\Video;
use app\common\models\VideoRecord;
use app\common\services\BroadcastService;
use app\common\services\Constants;
use Yii;

class LiveController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    const PAGE_SIZE = 10;
    public $enableCsrfValidation = false;

    /**
     * 直播管理  直播查看   昵称查询最好去掉
     * 先查询数据，再过滤nickName
     * 按照昵称查询的  先查询用户表，再查询直播表
     * 昵称为空的，先查询直播表，再查询用户表
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->getQueryParams();
        $ip = Yii::$app->params['wsServer'][Constants::CODE_SUCCESS]['ip'];
        $redis = RedisClient::getInstance();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        if (!empty($params['startTime']) && strtotime($params['startTime']) <= strtotime('1970-1-1')) {
            $params['startTime'] = '1970-1-1';
        }
        if (!empty($params['endTime']) && strtotime($params['endTime']) <= strtotime('1970-1-1')) {
            $params['endTime'] = '1970-1-1';
        }
        if (!empty($params['endTime'])) {
            $params['endTime'] = date('Y-m-d', strtotime($params['endTime']) + 86400);
        }
        $params['isLive'] = 1;
        $list = array();
        //通过昵称查询
        if (!empty($params['nickName'])) {
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v) {
                $params['userId'] = $v['id'];
                $list = Video::queryInfo($params);
                foreach ($list as &$elem) {
                    $elem['nickName'] = $v['nickName'];
                    $elem['status'] = $v['status'];
                    $elem['liveUrl'] = CdnUtils::getPullUrl($elem['id']);
                    $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $elem['roomId'];
                    $num = $redis->hGetAll($keyWSRoomFD);
                    $elem['num'] = !empty($num) ? count($num) : Constants::CODE_SUCCESS;
                }
            }
        } else {
            $list = Video::queryInfo($params);
            foreach ($list as &$val) {
                $userInfo = User::queryById($val['userId']);
                $val['nickName'] = $userInfo['nickName'];
                $val['status'] = $userInfo['status'];
                $val['liveUrl'] = CdnUtils::aliAppPullRtmpStream($val['id']);
                $keyWSRoomFD = Constants::WS_ROOM_FD . $ip . '_' . $val['roomId'];
                $num = $redis->hGetAll($keyWSRoomFD);
                $val['num'] = !empty($num) ? count($num) : Constants::CODE_SUCCESS;
            }
        }
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('index', [
            'itemList' => $list,
//            'pagination' => self::pagination($pageNo, $count),
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count,
            'page' => BroadcastService::pageBanner('/live/index', $pageNo + 1, $count, 10, 5, 's-gift-page-hover')
        ]);
    }

    //直播记录
    public function actionLiveRecord()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        if (!empty($params['startTime']) && strtotime($params['startTime']) <= strtotime('1970-1-1')) {
            $params['startTime'] = '1970-1-1';
        }
        if (!empty($params['endTime']) && strtotime($params['endTime']) <= strtotime('1970-1-1')) {
            $params['endTime'] = '1970-1-1';
        }
        if (!empty($params['endTime'])) {
            $params['endTime'] = date('Y-m-d', strtotime($params['endTime']) + 86400);
        }
        $params['isLive'] = 2;
        $list = array();
        //通过昵称查询
        if (!empty($params['nickName'])) {
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v) {
                $params['userId'] = $v['id'];
                $list = VideoRecord::queryInfo($params);
                foreach ($list as &$elem) {
                    $elem['nickName'] = $v['nickName'];
                }
            }
            $count = count($list);
        } else {
            $list = VideoRecord::queryInfo($params);
            foreach ($list as &$val) {
                $userInfo = User::queryById($val['userId']);
                $val['nickName'] = $userInfo['nickName'];
            }
            $count = VideoRecord::queryInfoNum($params);
        }
        unset($params['defaultPageSize']);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('live-record', [
            'itemList' => $list,
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count,
            'page' => BroadcastService::pageBanner('/live/live-record', $pageNo + 1, $count, self::PAGE_SIZE, 5, 's-gift-page-hover')
        ]);
    }

    public function actionYellow()
    {
        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        if (!empty($params['startTime']) && strtotime($params['startTime']) <= strtotime('1970-1-1')) {
            $params['startTime'] = '1970-1-1';
        }
        if (!empty($params['endTime']) && strtotime($params['endTime']) <= strtotime('1970-1-1')) {
            $params['endTime'] = '1970-1-1';
        }
        if (!empty($params['endTime'])) {
            $params['endTime'] = date('Y-m-d', strtotime($params['endTime']) + 86400);
        }

        $params['type'] = 3;
        $list = array();
        //通过昵称查询
        if (!empty($params['nickName'])) {
            $result = User::queryInfoByNickName($params['nickName']);//查询出昵称的用户id
            unset($params['nickName']);
            foreach ($result as $k => $v) {
                $params['userId'] = $v['id'];
                $list = Video::JianYellow($params);
                foreach ($list as &$elem) {
                    $elem['nickName'] = $v['nickName'];
                }
            }
        } else {
            $list = Video::JianYellow($params);
            foreach ($list as &$val) {
                $userInfo = User::queryById($val['userId']);
                $val['nickName'] = $userInfo['nickName'];
            }
        }
        $count = Video::queryInfoNum($params);
        $pageNo = !empty($params['page']) ? $params['page'] - 1 : 0;
        return $this->render('yellow', [
            'itemList' => $list,
            'params' => Yii::$app->request->getQueryParams(),
            'count' => $count,
            'page' => BroadcastService::pageBanner('/live/yellow', $pageNo + 1, $count, self::PAGE_SIZE, 5, 's-gift-page-hover')
        ]);
    }

    //鉴黄查看
    public function actionYellowCheck()
    {

        $params = Yii::$app->request->getQueryParams();
        $params['defaultPageSize'] = self::PAGE_SIZE;
        $id = $params['id'];

        //通过id获取鉴黄信息
        $result = Video::JianYellowById($id);
//        print_r($result);die;
        return $this->render('yellow-check', [
            'itemList' => $result,
            'yellowurl' => $result['yellowurl'],
            'information' => $result['information'],
        ]);
    }

    //查看  获取视频的地址
    public function actionCheck()
    {
        $params = Yii::$app->request->getQueryParams();
        $id = $params['id'];
        $result = Video::queryById($id);
        $videoSrc = $result['videoSrc'];
        return $this->render('check', [
            'videoSrc' => $videoSrc
        ]);
    }

    //查看回访
    public function actionPlayBack()
    {
        $params = Yii::$app->request->get();
        $id = $params['id'];
        $result = VideoRecord::queryById($id);
        $videoSrc = $result['videoSrc'];
        return $this->render('play-back', [
            'videoSrc' => $videoSrc
        ]);
    }

    //禁播
    public function actionNoplay()
    {
        $params = Yii::$app->request->post();

        $type = $params['type'];
        $messageType = '';
        $message = '';
        switch ($type) {
            case 1:
                $messageType = Constants::MESSAGE_TYPE_PROHIBIT_LIVE_ONE_DAY_REQ;
                $message = '禁播24h';
                break;
            case 2:
                $messageType = Constants::MESSAGE_TYPE_PROHIBIT_LIVE_30_DAYS_REQ;
                $message = '禁播30天';
                break;
            case 3:
                $messageType = Constants::MESSAGE_TYPE_PERPETUAL_PROHIBIT_LIVE_REQ;
                $message = '永久禁播';
                break;
            case 4:
                $messageType = Constants::MESSAGE_TYPE_PROHIBIT_ACCOUNT_NUMBER_REQ;
                $message = '封禁账号';
                break;
        }
        $params['message'] = $message;
        if (User::operateNoplay($params)) {
            //直播
            $isLive = $params['isLive'];
            if ($isLive) {//是直播
                $roomId = $params['roomId'];
                //推送信息
                $data = array(
                    'messageType' => $messageType,
                    'data' => array(
                        'userId' => $params['userId'],
                        'roomId' => $params['roomId'],
                        'message' => $message,
                    ),
                );
                $url = Yii::$app->params['shareUrl'] . '/server/location?roomId=' . $roomId;//http://dev.api.customize.3ttech.cn/server/location
                $result = AHelper::curl_get($url);
                $result = json_decode($result, true);
                $roomServer = $result['data']['roomServer'];
                $host = $roomServer['host'];
                $port = $roomServer['port'];
                $url = 'http://' . $host . ':' . $port;
                $result = AHelper::curlPost($url, json_encode($data));
                $this->jsonReturnSuccess(0);
            } else {//发送系统消息
                $this->jsonReturnError(-1);//只能直播才推送消息
            }
        } else {
            $this->jsonReturnError(-1);
        }
    }
}
