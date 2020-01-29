<?php
/**
 * Created by PhpStorm.
 * User: dengbin
 * Date: 2017/7/13
 * Time: 上午10:13
 */

namespace app\common\components;

use Yii;
use yii\base\Component;
use app\common\extensions\OSS\OssClient;

class OSS extends Component
{

    public static $oss;

    public function __construct()
    {
        parent::__construct();
        $accessKeyId = Yii::$app->params['oss']['accessKeyId'];                 //获取阿里云oss的accessKeyId
        $accessKeySecret = Yii::$app->params['oss']['accessKeySecret'];         //获取阿里云oss的accessKeySecret
        $endpoint = Yii::$app->params['oss']['endPoint'];                       //获取阿里云oss的endPoint
        self::$oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);  //实例化OssClient对象
    }

    /**
     * 使用阿里云oss上传文件
     * @param $filePath   保存到阿里云oss的文件名
     * @param $fileName 文件在本地的绝对路径
     * @param $dir
     * @return 图片路径
     */
    public function upload($filePath, $fileName, $dir = 'avatar')
    {
        $object = self::filePath($fileName, $dir);
        $res = '';
        $bucket = Yii::$app->params['oss']['bucket'];               //获取阿里云oss的bucket
        if (self::$oss->uploadFile($bucket, $object, $filePath)) {  //调用uploadFile方法把服务器文件上传到阿里云oss
            $res = "http://" . $bucket . "." . Yii::$app->params['oss']['endPoint'] . "/" . $object;
        }
        return $res;
    }

    /**
     * 直接把二进制内容上传到oss
     * @param $object
     * @param $content
     * @return null
     */
    public static function uploadContent($object, $content)
    {
        $res = false;
        if (self::$oss->putObject(Yii::$app->params['oss']['bucket'], $object, $content)) {
            $res = true;
        }

        return $res;
    }

    /**
     * 删除指定文件
     * @param $object 被删除的文件名
     * @return bool   删除是否成功
     */
    public function delete($object)
    {
        $res = false;
        $bucket = Yii::$app->params['oss']['bucket'];    //获取阿里云oss的bucket
        if (self::$oss->deleteObject($bucket, $object)) { //调用deleteObject方法把服务器文件上传到阿里云oss
            $res = true;
        }

        return $res;
    }

    public function test()
    {
        echo "success";
    }

    /*
     * @param $accessKeyId
     * @param $accessKeySecret
     * @param $endpoint
     * @param $bucket
     * @param $filePath
     * @param $fileName
     * @param $dir
     * @return 图片绝对地址
     * */
    public static function upload2($accessKeyId, $accessKeySecret, $endpoint, $bucket, $filePath, $fileName, $dir = 'avatar')
    {
        $object = self::filePath($fileName, $dir);
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $res = '';
        if ($ossClient->uploadFile($bucket, $object, $filePath)) {
            $res = "http://" . $bucket . "." . $endpoint . "/" . $object;
        }
        return $res;
    }

    private static function filePath($fileName, $dir = 'avatar')
    {
        $info = pathinfo($fileName);
        $ext = isset($info['extension']) ? ("." . $info['extension']) : "";
        return $dir . date("/Y/m/d/H/is", time()) . '_' . rand(1000, 9999) . $ext;
    }

    /**
     *
     * 使用阿里云oss上传文件
     * @param string $filePath 文件在本地的绝对路径
     * @param string $fileName 保存到阿里云oss的文件名
     * @param $dir
     * @param $bucket
     * @return string
     * @throws \app\common\extensions\OSS\Core\OssException
     */
    public function uploadExtend($filePath, $fileName, $dir, $bucket)
    {
        $object = self::filePath($fileName, $dir);
        $res = '';
        if (self::$oss->uploadFile($bucket, $object, $filePath)) {  //调用uploadFile方法把服务器文件上传到阿里云oss
            $res = "http://" . $bucket . "." . Yii::$app->params['oss']['endPoint'] . "/" . $object;
        }
        return $res;
    }
}