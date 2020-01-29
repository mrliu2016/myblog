<?php

use app\common\components\AHelper;

require(__DIR__ . '/file.php');

function sms($mobile, $content) {
    if (!is_array($mobile)) {
        $mobile = [$mobile];
    }
    return \app\common\components\SmsTools::smsPost($mobile, $content);
}

/**
 * 判断字符串是否以xxx开头
 * @param $str  string 完整的字符串
 * @param $find string 需要搜索的字符串
 * @return bool
 */
function starts_with($str, $find) {
    if (strlen($str) < strlen($find)) {
        return false;
    }
    return substr($str, 0, strlen($find)) === $find;
}

/**
 * 判断字符串是否以xxx结束
 * @param $str  string 完整的字符串
 * @param $find string 需要搜索的字符串
 * @return bool
 */
function ends_with($str, $find) {
    if (strlen($str) < strlen($find)) {
        return false;
    }
    return substr($str, strlen($str) - strlen($find)) === $find;
}

/**
 * 记录本地日志
 * 日志会写入@runtime/logs/当天日期/filename中
 * filename必须以.log结尾
 * @param $message  mixed  日志内容
 * @param $filename string 文件名
 */
function local_log($message, $filename) {
    if (empty($filename) || !ends_with($filename, '.log')) {
        return;
    }
    if (is_array($message)) {
        $message = json_encode($message, JSON_FORCE_OBJECT);
    }
    $today_path = Yii::getAlias('@runtime') . '/logs/' . date('Ymd')  . '/';
    if(!is_dir($today_path)) {
        mkdir($today_path, 0777, true);
        chmod($today_path, 0777);
    }
    $filename = $today_path . $filename;
    $is_chmod = !file_exists($filename);
    error_log(date('Y-m-d H:i:s') . ' ' .$message . "\n", 3, $filename);
    if ($is_chmod) {
        chmod($filename, 0777);
    }
}

/**
 * local_log的别名
 * 记录本地日志
 * 日志会写入@runtime/logs/当天日期/filename中
 * filename必须以.log结尾
 * @param $message  mixed  日志内容
 * @param $filename string 文件名
 */
function ll($message, $filename) {
    local_log($message, $filename);
}

/**
 * 从本地文件扫描条形码
 * @param $fileName string 本地文件名 绝对路径
 * @return array|null
 */
function scan_bar_code($fileName) {
    if (!file_exists($fileName)) {
        return null;
    }
    $executableFile = YII_ENV_LOCAL ? '/usr/local/bin/zbarimg' : '/usr/bin/zbarimg';
    if (!file_exists($executableFile)) {
        return null;
    }
    $output = shell_exec("$executableFile -q $fileName");
    if (empty($output)) {
        return null;
    }
    $output = trim($output);
    $result = [];
    foreach (explode("\n", $output) as $line) {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        $line = explode(':', $line);
        if (count($line) == 2) {
            $result[] = [
                'type' => $line[0],
                'data' => $line[1],
            ];
        }
    }
    return $result;
}

/**
 * 上传文件到CDN 简洁版
 * @param $fileName string 本地文件 绝对路径
 * @return null|string
 */
function upload_local_file($fileName) {
    if (empty($fileName) || !file_exists($fileName)) {
        return null;
    }
    $uploadURL = 'http://upload.baicao.service.mogujie.org:9090/p1/upload';
    $path = AHelper::curl_post_file($fileName, $uploadURL);
    return empty($path) ? null : trim($path);
}

function map_to_array($map) {
    $array = [];
    foreach ($map as $k => $v) {
        $array[] = [
            'key'   => $k,
            'value' => $v,
        ];
    }
    return $array;
}

/**
 * 对象数组转为MAP
 * @param $objects array    对象数组
 * @param $getKey  string|callable KEY名或KEY拼接函数
 * @return array
 */
function objects_to_map($objects, $getKey) {
    $map = [];
    foreach ($objects as $object) {
        if (is_callable($getKey)) {
            $map[$getKey($object)] = $object;
        } else {
            $map[$object->$getKey] = $object;
        }
    }
    return $map;
}

function parse_spu_code($skuCode) {
    if (empty($skuCode)) return '';
    return explode('.', $skuCode)[0];
}

function get_login_user() {
    if (isset($GLOBALS['loginUser'])) {
        return $GLOBALS['loginUser']->domain;
    }
    if (php_sapi_name() == 'cli') {
        return 'system';
    }
    return 'unknown';
}

function in_team_talk() {
    return strstr(Yii::$app->request->userAgent, 'tt4ios') !== false
        || strstr(Yii::$app->request->userAgent, 'tt4android') !== false;
}

function urltoid($url) {
    return AHelper::urltoid($url);
}

function idtourl($id) {
    return AHelper::idtourl($id);
}
