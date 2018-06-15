<?php

namespace app\common\components;

use Yii;

class UploadUtils
{

    /**
     * 自定义上传多图
     * @param array $param
     * @return string
     */
    public static function multiUploadPicture($param = array())
    {
        static::processFile();
        $upload = new UploadFile($param);// 实例化上传类
        $upload->saveRule = false;
        $upload->uploadReplace = true;  // 覆盖同名
        //设置上传文件大小
        $upload->maxSize = 3145728;
        //设置上传文件类型
        $upload->exts = Yii::$app->params['imageExt'];
        //设置附件上传目录
        $upload->savePath = isset($param['savePath']) ? $param['savePath'] : static::savePath(); // 设置附件上传（子）目录
        $upload->autoSub = false; //是否生成日期文件夹
        if (!is_dir($upload->savePath)) {
            mkdir($upload->savePath, 0777, true);
            chmod($upload->savePath, 0777);
            @chmod(implode('/', explode('/', $upload->savePath, -2)), 0777);
        }
        if (!empty($param['isThumb'])) {
            static::processThumb($upload);
        }
        @$info = $upload->upload();
        if (!$info) {
            return $upload->getErrorMsg();
        } else {
            return $info;
        }
    }

    /**
     *
     */
    private static function processFile()
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $pathInfo = pathinfo($value['name']);
                $_FILES[$key]['name'] = RandString::randString(15) . time() . '.' . $pathInfo['extension'];
                $_FILES[$key]['savePath'] = static::savePath();
            }
        }
    }

    /**
     * 返回图片地址
     *
     * @return string
     */
    public static function getUploadFileUrlByOne()
    {
        foreach ($_FILES as $key => $value) {
            return str_replace($_SERVER['DOCUMENT_ROOT'],
                    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'], $value['savePath']) . $value['name'];
        }
        return '';
    }

    /**
     * 上传路径
     *
     * @return string
     */
    private static function savePath()
    {
        return realpath($_SERVER['DOCUMENT_ROOT']) . '/upload/' . date("Y/m/d/H/is", time()) . '/';
    }

    /**
     * 处理缩略图
     * @param $upload
     */
    protected static function processThumb($upload)
    {
        $upload->autoSub = false; //是否生成日期文件夹
        $upload->thumbRemoveOrigin = false; //设置生成缩略图后移除原图
        $upload->uploadReplace = true;  // 覆盖同名
        $upload->thumbPrefix = '';  // 前缀
        $upload->thumbSuffix = '_big,_middle,_small'; // 后缀
        $upload->thumbExt = 'jpg';

        //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = true;
        // 设置引用图片类库包路径
        $upload->imageClassPath = 'Image';
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '200,120,48';
        //设置缩略图最大高度
        $upload->thumbMaxHeight = '200,120,48';
    }
}