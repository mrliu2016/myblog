<?php
namespace app\common\components;

use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use app\common\models\UnifiedLog;
use Yii;
use yii\httpclient\Client;

class AHelper extends ArrayHelper
{

    /**
     * @return string
     */
    public static function generateUniqueId()
    {
        $prefix = substr(md5(gethostname()), 0, 5);
        return uniqid($prefix);
    }

    public static function urlBase64Encode($str)
    {
        return strtr(base64_encode($str), "+/=", "-_.");
    }

    public static function urlBase64Decode($str)
    {
        return base64_decode(strtr($str, "-_.", "+/="));
    }

    /**
     * @param $string
     * @param $length
     * @param string $etc
     * @return string
     */
    public static function cutstr($string, $length, $etc = "...")
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen) {
            $result .= $etc;
        }
        return $result;
    }

    public static function getMultipleColumn($array, $group, $keepKeys = true)
    {
        $result = [];
        if ($keepKeys) {
            foreach ($array as $key => $element) {
                foreach ($group as $subKey) {
                    $result[$key][$subKey] = static::getValue($element, $subKey);
                }
            }
        } else {
            foreach ($array as $element) {
                foreach ($group as $subKey) {
                    $result[][$subKey] = static::getValue($element, $subKey);
                }
            }
        }

        return $result;
    }

    public static function curl_get($url, $timeout = 60)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function getServerIp()
    {
        $serverip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 0;
        if (empty($serverip)) {
            exec("/usr/sbin/ifconfig | /usr/bin/grep inet|/usr/bin/grep -v '127.0.0.1'|/usr/bin/awk -F ' ' '{print $2}'", $arr);
            $serverip = $arr[0];
        }
        return $serverip;
    }

    /**
     * post
     * @param $url
     * @param $data
     * @param int $timeout
     * @param string $headerAry
     * @return mixed
     */
    public static function curlPost($url, $data, $timeout = 3, $headerAry = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_HEADER, false);
        if ($headerAry != '') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerAry);
        }
        $res = curl_exec($ch);

        return $res;
    }

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public static function arraySequence($array, $field, $sort = 'SORT_DESC')
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }

    /**
     *
     * 混淆ID转换为url
     * @param id , version
     */

    public static function idtourl($id, $version = 1)
    {
        if (!is_array($id) && strlen($id) == 24) {
            return $id;
        }

        if (is_array($id)) {
            foreach ($id as $key => $value) {

                if (is_array($value) || in_array($key, needautoconvert())) {
                    $id[$key] = idtourl($value);
                }

            }
            return $id;
        } elseif (intval($id) > 0) {

            switch ($version) {
                case 1:
                    static $convert;
                    if (isset($convert[$id])) {
                        $url = $convert[$id];
                    } else {
                        $url = $version . base_convert($id * 2 + 56, 10, 36);
                        $convert[$id] = $url;
                    }
                    break;
                default:
                    $url = false;
                    break;
            }
            return $url;
        }
        return $id;
    }

    /**
     * 混淆后的URL转换为ID
     * @param $userId
     * @param $check 检查null/undefined
     */
    public static function urltoid($url, $check = false)
    {

        if (strlen($url) == 24 && preg_match('/^[0-9a-f]+$/', $url)) return $url;  //兼容MongoId
        if (is_array($url)) {
            foreach ($url as $key => $value) {
                $url[$key] = urltoid($value);
            }
            return $url;
        } elseif (false !== stripos($url, ',')) {
            $url = explode(',', $url);
            foreach ($url as $key => $value) {
                if (empty($value)) {
                    unset($url[$key]);
                    continue;
                }

                $url[$key] = urltoid($value);
            }
            return implode(',', $url);
        } else {
            if ($check && preg_match('/^(null|undefined)$/i', $url)) {
                return false;
            }

            $version = intval(substr($url, 0, 1));

            switch ($version) {
                case 1:
                    $id = (intval(base_convert(substr($url, 1), 36, 10)) - 56) / 2;
                    break;

                case 2:  //防止扫用户;
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                    $id = 0;
                    break;

                default:
                    $id = $url;
                    break;
            }
            return 0 < $id ? intval($id) : 0;
        }
    }

    //mogucdn文件上传
    public static function curl_post_file($filePath, $url, $old = false)
    {
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $ch = curl_init();
            $header = array(
                'X-If-Strip: false',
            );
            if ($old) {
                $header[] = "X-If-Compatible: true";
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            $re = curl_exec($ch);
            $in = curl_getinfo($ch);
            self::addLog('header', $in, 'curl_post_data.log');
            curl_close($ch);
            if ($in['http_code'] == 200) {
                return $re; //成功返回
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public static function returnJeson($data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function returnJson($data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 读取文件
    public static function getFileContent($fileName)
    {
        $tmpLogDir = Yii::$app->getRuntimePath() . '/tmp/';
        $logFileName = $tmpLogDir . '/' . $fileName;
        if (file_exists($logFileName)) {
            $data = file_get_contents($logFileName);
        } else {
            $data = 0;
        }
        return $data;
    }

    // 写文件
    public static function setFileContent($fileName, $times)
    {
        $tmpLogDir = Yii::$app->getRuntimePath() . '/tmp/';
        if (!file_exists($tmpLogDir)) {
            mkdir($tmpLogDir);
        }
        file_put_contents($tmpLogDir . '/' . $fileName, $times);
        return true;
    }

    // 统一操作日志函数
    public static function addLog($operation, $data, $filename = '')
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        $today_path = Yii::getAlias('@runtime') . '/logs/' . date('Ymd') . '/';
        if (!is_dir($today_path)) {
            mkdir($today_path, 0777, true);
            chmod($today_path, 0777);
        }
        if ($filename == 'Monitor.log') {
            $filename = Yii::getAlias('@runtime') . '/logs/' . $filename;
        } else {
            if (!$filename || is_array($filename) || strlen($filename) > 50) {
                $filename = $today_path . 'action.' . date('Y-m-d') . '.log';
            } else {
                $filename = $today_path . $filename;
            }
        }
        $logData = date('Y-m-d H:i:s') . '|' . $operation . "|" . $data . "\n";
        $is_chmod = !file_exists($filename);
        error_log($logData, 3, $filename);
        if ($is_chmod) {
            chmod($filename, 0777);
        }
    }

    public static function addMonitorLog($operation, $data)
    {
        $file = 'Monitor.log';
        self::addLog($operation, $data, $file);
    }

    /**
     * 二维数组排序(td是two-dimension的意思)
     *
     * @param array $arr
     * @param string $fieldA
     * @param string $sortA
     * @param string $fieldB
     * @param string $sortB
     * @param string $fieldC
     * @param string $sortC
     */
    public static function tdSort(&$arr, $fieldA, $sortA = SORT_ASC, $fieldB = '', $sortB = SORT_ASC, $fieldC = '', $sortC = SORT_ASC)
    {
        if (!is_array($arr) || count($arr) < 1) {
            return false;
        }
        $arrTmp = array();
        foreach ($arr as $rs) {
            foreach ($rs as $key => $value) {
                $arrTmp["{$key}"][] = $value;
            }
        }
        if (empty($fieldB)) {
            if (!$arrTmp[$fieldA]) {
                return false;
            }
            array_multisort($arrTmp[$fieldA], $sortA, $arr);
        } elseif (empty($fieldC)) {
            if (!$arrTmp[$fieldA] || !$arrTmp[$fieldB]) {
                return false;
            }
            array_multisort($arrTmp[$fieldA], $sortA, $arrTmp[$fieldB], $sortB, $arr);
        } else {
            if (!$arrTmp[$fieldA] || !$arrTmp[$fieldB] || !$arrTmp[$fieldC]) {
                return false;
            }
            array_multisort($arrTmp[$fieldA], $sortA, $arrTmp[$fieldB], $sortB, $arrTmp[$fieldC], $sortC, $arr);
        }
        return true;
    }

    public static function postNew($url, $data)
    {
        $httpClient = new Client();
        $httpResponse = $httpClient->createRequest()
            ->setMethod('post')
            ->setUrl($url)
            ->setData($data)
            ->send();
        if ($httpResponse->isOk) {
            return json_encode($httpResponse->data);
        } else {
            return "";
        }
    }

    public static function unicode2utf8($str)
    {
        if (!$str) return $str;
        $decode = json_decode($str);
        if ($decode) return $decode;
        $str = '["' . $str . '"]';
        $decode = json_decode($str);
        if (count($decode) == 1) {
            return $decode[0];
        }
        return $str;
    }

    // 过滤掉emoji表情
    public static function filterEmoji($str)
    {
        if (!is_string($str)) return $str;
        if (!$str || $str == 'undefined') return '';

        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
            return addslashes($str[0]);
        }, $text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }

    /**
     * 解码上面的转义
     */
    public static function userTextDecode($str)
    {
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i', function ($str) {
            return '\\';
        }, $text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }

    public static function str_split_uft8($str, $split_len = 1)
    {
        if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1)
            return false;
        $len = mb_strlen($str, 'UTF-8');
        if ($len <= $split_len)
            return array($str);
        preg_match_all('/.{' . $split_len . '}|[^\x00]{1,' . $split_len . '}$/us', $str, $ar);
        return $ar[0];
    }

    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * 给图片地址拼上域名，如果自带域名，则原样返回
     * @param $imgs
     * @return array
     */
    public static function imgFullPath($imgs)
    {
        if (empty($imgs)) {
            return $imgs;
        }
        if (!is_array($imgs)) {
            $parseUrl = parse_url($imgs);
            if (is_array($parseUrl)) {
                if (isset($parseUrl['scheme']) && isset($parseUrl['host'])) {
                    return $imgs;
                } else {
                    return self::imgCdnHost($imgs) . $imgs;
                }
            }
        } else {
            $return = [];
            foreach ($imgs as $img) {
                $parseUrl2 = parse_url($img);
                if (is_array($parseUrl2)) {
                    if (isset($parseUrl2['scheme']) && isset($parseUrl2['host'])) {
                        $return[] = $img;
                    } else {
                        $return[] = self::imgCdnHost($img) . $img;
                    }
                } else {
                    $return[] = $img;
                }
            }
            return $return;
        }
        return $imgs;
    }

    /**
     * 取图片cdn域名
     * @param $path
     */
    public static function  imgCdnHost($path)
    {
        // 美丽说老图
        if (stripos($path, '/pic/_o/') === 0) {
            return 'http://d01.res.meilishuo.net';
        } else {
            return Yii::$app->params['staticDomain'];
        }
    }

    /**
     * 判断图片是不是美丽说旧的CDN
     * @param $path
     * 是旧图片返回true
     */
    public static function  testOldImgCdn($path)
    {
        if (empty($path)) {
            return false;
        }
        // 美丽说老图
        if (stripos($path, '/pic/_o/') === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 返回图片的相关地址，截掉域名
     * @param $imgs
     * @return array
     */
    public static function imgRelativePath($imgs)
    {
        if (empty($imgs)) {
            return $imgs;
        }
        if (!is_array($imgs)) {
            $parseUrl = parse_url($imgs);
            if (is_array($parseUrl)) {
                if (isset($parseUrl['path']) && !empty($parseUrl['path'])) {
                    return $parseUrl['path'];
                } else {
                    return $imgs;
                }
            }
        } else {
            $return = [];
            foreach ($imgs as $img) {
                $parseUrl2 = parse_url($img);
                if (is_array($parseUrl2)) {
                    if (isset($parseUrl2['path']) && !empty($parseUrl2['path'])) {
                        $return[] = $parseUrl2['path'];
                    } else {
                        $return[] = $img;
                    }
                } else {
                    $return[] = $img;
                }
            }
            return $return;
        }
        return $imgs;
    }

    public static function imgSourceUrl($imgs)
    {
        if (empty($imgs)) {
            return $imgs;
        }
        if (!is_array($imgs)) {
            $strTimes = substr_count($imgs, '.jpg');
            if ($strTimes > 1) {
                $firstPalce = strpos($imgs, '.jpg');
                $newUrl = substr($imgs, 0, $firstPalce + 4);
                return $newUrl;
            } else {
                return $imgs;
            }
        } else {
            $return = [];
            foreach ($imgs as $img) {
                $strTimes = substr_count($img, '.jpg');
                if ($strTimes > 1) {
                    $firstPalce = strpos($img, '.jpg');
                    $newUrl = substr($img, 0, $firstPalce + 4);
                    $return[] = $newUrl;
                } else {
                    $return[] = $img;
                }
            }
            return $return;
        }
        return $imgs;
    }

    //  查询json解析结果是非是null
    public static function JsonisNull($str)
    {
        return is_null(json_decode($str));
    }


    /**
     * 用二维数组中某个列的值作为一唯的key
     * @param $arr
     * @param $key
     * @return array
     */
    public static function reKey($arr, $key)
    {
        $new_arr = array();
        foreach ($arr as $v) {
            $new_arr[$v[$key]] = $v;
        }
        return $new_arr;
    }

    // 对外接口格式
    public static function returnValue($data, $code = 0, $success = true, $msg = null)
    {
        echo json_encode(['code' => $code, 'data' => $data, 'msg' => $msg, 'success' => $success]);
        exit;
    }

    /**
     * 指定的字短为key组合maps
     * @param $data
     * @param $key
     * @return array
     */
    public static function formatKeyMaps($data, $key)
    {
        $newArr = array();
        if (empty($data) || empty($key)) {
            return $newArr;
        }
        foreach ($data as $val) {
            $newArr[$val[$key]][] = $val;
        }
        return $newArr;
    }

    /**
     * 把json数组中的图片路径改为相对路径
     * @param $json
     * @return jsonstring
     */
    public static function jsonImgRelativePath($json)
    {
        if (empty($json)) {
            return $json;
        }

        $imgs = json_decode($json, true);

        $resArr = array();
        foreach ($imgs as $i) {
            $resArr[] = self::imgRelativePath($i);
        }

        return json_encode($resArr);
    }


    const PREFIX_REFERNO = "MLYX";

    /**生成ERP单号给WMS
     * @param $id
     * @return string
     */
    public static function genWhReferNo($id)
    {
        return AHelper::PREFIX_REFERNO . $id;
    }

    /**解析ERP单号
     * @param $referNo
     * @return string
     */
    public static function decWhReferNo($referNo)
    {
        return substr($referNo, 4);
    }

    /**
     * 图片重传
     * @param $path
     * @return bool
     */
    public static function reUploadImg($path)
    {
        $cropImage = new CropImage('cut_image');
        $src_file = $cropImage->wget($path);
        $curl_command = Yii::$app->params['cropImage']['curl'];
        $upload_url = Yii::$app->params['uploadDomain'] . '/p1/upload';
        $command = "{$curl_command} -s -S -XPOST -T {$src_file} {$upload_url}";
        $output = '';
        $return = '';
        exec($command, $output, $return);
        if ($return != 0) {
            return false;
        }
        $result = ['output' => $output, 'status' => $return];
        $dest_file = $result['output'][0];
        if (strrpos($dest_file, '/')) {
            return $dest_file;
        }
        return false;
    }

    //获取网络图片
    public static function getWebImg($url, $path = '/tmp/tmp.jpg')
    {
        $image = file_get_contents($url);
        file_put_contents($path, $image);
        $postUrl = Yii::$app->params['uploadDomain'] . '/p1/upload';
        return Yii::$app->params['staticDomain'] . self::curl_post_file($path, $postUrl);
    }

    // 获取网络多个图片
    public static function getBatchWebImg($imgArr)
    {
        if (empty($imgArr)) {
            return '';
        }
        $imgs = [];
        foreach ($imgArr as $val) {
            $path = static::getWebImg($val);
            $imgs[] = $path;
        }
        return json_encode($imgs);
    }

    /**爬虫解析从mls商品接口返回的商品详情图片数据
     * @param $picInfo
     * @return array|null
     */
    public static function decMlsDescPic($picInfo)
    {
        if (empty($picInfo)) {
            return null;
        }
        $piece = explode('^^^', $picInfo);
        if (empty($piece)) {
            return null;
        }
        $result = [];
        foreach ($piece as $p) {
            if (empty($p)) {
                continue;
            }
            $piece1 = explode('+++', $p);
            if (empty($piece1)) {
                continue;
            }

            foreach ($piece1 as $p1) {
                if (empty($p1)) {
                    continue;
                }
                $piece2 = explode(':::', $p1);
                if (empty($piece2)) {
                    continue;
                }
                foreach ($piece2 as $p2) {
                    if (strpos($p2, "jpg") !== false || strpos($p2, "gif") !== false || strpos($p2, "png") !== false || strpos($p2, "jpeg") !== false) {
                        $t = str_replace("###", "", $p2);
                        $r = explode('&&&', $t);
                        $result = array_merge($result, $r);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 图片检查与重传
     * @param $path
     * @return bool
     */
    public static function getAndUploadImg($path)
    {
        $head = get_headers($path);
        $status = explode(' ', $head[0]);
        if ($status[1] != '200') {
            return false;
        }
        $cropImage = new CropImage('cut_image');
        $src_file = $cropImage->wget($path);
        $curl_command = Yii::$app->params['cropImage']['curl'];
        $upload_url = Yii::$app->params['uploadDomain'] . '/p1/upload';
        $command = "{$curl_command} -s -S -XPOST -T {$src_file} {$upload_url}";
        $output = '';
        $return = '';
        exec($command, $output, $return);
        if ($return != 0) {
            return false;
        }
        $result = ['output' => $output, 'status' => $return];
        $dest_file = $result['output'][0];
        if (strrpos($dest_file, '/')) {
            return $dest_file;
        }
        return false;
    }

    /**从商品编码中取出spu编码。
     * @param $itemCode
     * @return string
     */
    public static function getSpuCode($itemCode)
    {
        if (empty($itemCode)) {
            return "";
        }
        $arr = explode("--", $itemCode);

        if (isset($arr[1])) {
            return $arr[1];
        }
        return "";
    }

    /**获取今天零点的时间戳
     * @return int
     */
    public static function getCurDaytime()
    {
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        return $beginToday;
    }

    public static function pagination($pageSize, $pageNo, $count)
    {
        $pagination = new Pagination([
            'defaultPageSize' => $pageSize,
            'totalCount' => $count,
        ]);
        $pagination->setPage($pageNo);
        return $pagination;
    }

    public static function leavingTime($unixEndTime = 0)
    {
        if ($unixEndTime <= time()) { // 如果过了活动终止日期
            return '0天0时0分';
        }

        // 使用当前日期时间到活动截至日期时间的毫秒数来计算剩余天时分
        $time = $unixEndTime - time();

        $days = 0;
        if ($time >= 86400) { // 如果大于1天
            $days = (int)($time / 86400);
            $time = $time % 86400; // 计算天后剩余的毫秒数
        }

        $hour = 0;
        if ($time >= 3600) { // 如果大于1小时
            $hour = (int)($time / 3600);
            $time = $time % 3600; // 计算小时后剩余的毫秒数
        }

        $minute = (int)($time / 60); // 剩下的毫秒数都算作分

        return $days . '天' . $hour . '时' . $minute . '分';
    }

    public static function hasChinese($string)
    {
        if (preg_match("/[\x7f-\xff]/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    public static function hasEmoji($string)
    {
        if (preg_match('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $string)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkUrl($url)
    {
        $regex = "#^(http://|https://)[a-zA-Z0-9\\.]{1,100}[.](com|org)/.*$#";
        if (preg_match($regex, $url)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 取图片cdn域名
     * @param $path
     *  兼容头像这种情况
     */
    public static function  imgCdnHostPic($path)
    {
        // 美丽说老图
//        if ((stripos($path, '/pic/_o/') === 0) || (stripos($path, '/ap/')===0) || (stripos($path, 'ap/')===0) || (stripos($path,'/new1/')===0)){
        if(preg_match("#(^\/pic\/_o\/)|(^\/new1\/)|(^\/ap\/)|(^ap\/)#",$path)){
            return 'http://d01.res.meilishuo.net';
        } else {
            return Yii::$app->params['staticDomain'];
        }
    }
}
