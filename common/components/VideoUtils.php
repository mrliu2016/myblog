<?php

namespace app\common\components;

class VideoUtils
{
    const FFMPEG_PATH = '/usr/bin/ffmpeg -i "%s" 2>&1';
    public static function getVideoInfo($file)
    {
        $command = sprintf(self::FFMPEG_PATH, $file);
        ob_start();
        passthru($command);
        $info = ob_get_contents();
        ob_end_clean();
        $data = array();
        if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $info, $match)) {
            $data['duration'] = $match[1]; //播放时间
            $arr_duration = explode(':', $match[1]);
            $data['seconds'] = $arr_duration[0] * 3600 + $arr_duration[1] * 60 + $arr_duration[2]; //转换播放时间为秒数
            $data['start'] = $match[2]; //开始时间
            $data['bitrate'] = $match[3]; //码率(kb)
        }
        if (preg_match("/Video: (.*?)\s(.*?), (.*?), (.*?), (.*?)[,\s]/", $info, $match)) {
            $data['vcodec'] = $match[1]; //视频编码格式
            $data['vformat'] = $match[3]; //视频格式
            $data['resolution'] = $match[4]; //视频分辨率
            $arr_resolution = explode('x', $match[4]);
            if(count($arr_resolution)>1){
                $data['width'] = $arr_resolution[0];
                $data['height'] = $arr_resolution[1];
            }else{
                $data['resolution'] = $match[5]; //视频分辨率
                $arr_resolution = explode('x', $match[5]);
                $data['width'] = $arr_resolution[0];
                $data['height'] = $arr_resolution[1];
            }
        }
        if (preg_match("/Audio: (.*?)\s(.*?), (\d*) Hz/", $info, $match)) {
            $data['acodec'] = $match[1]; //音频编码
            $data['asamplerate'] = $match[3]; //音频采样频率
        }
        if (isset($data['seconds']) && isset($data['start'])) {
            $data['play_time'] = $data['seconds'] + $data['start']; //实际播放时间
        }
        return $data;
    }
}
