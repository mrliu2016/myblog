<?php

namespace app\common\components;

class ServerResources
{
    const TRILLION_BYTE = 1024; // 兆(M)
    const PERCENTAGE = 100; // 百分比
    /**
     * 服务器参数
     *
     * @var array
     */
    public static $serverParams = [
        'ip',           //IP
        'domain',       //服务器域名和IP及进程用户名
        'flag',         //服务器标识
        'os',           //服务器操作系统具体
        'language',     //服务器语言
        'name',         //服务器主机名
        'email',        //服务器管理员邮箱
        'webEngine',    //服务器WEB服务引擎
        'webPort',      //web服务端口
        'webPath',      //web路径
        'probePath',    //本脚本所在路径
        'sTime'         //服务器时间
    ];

    public static $systemInfo; //系统信息，windows和linux

    public static $CPU_Use;

    public static $hd = [
        't',        //硬盘总量
        'f',        //可用
        'u',        //已用
        'PCT',      //使用率
    ];

    /**
     * 网卡
     *
     * @var array
     */
    public static $netWork = [
        'netWorkName',      //网卡名称
        'netOut',           //出网总量
        'netInput',         //入网总量
        'outSpeed',         //出网速度
        'inputSpeed'        //入网速度
    ];

    /**
     * 获取操作系统
     *
     * @return string
     */
    public static function getOperatingSystem()
    {
        static::$serverParams['os'] = PHP_OS;
        return static::$serverParams['os'];
    }

    /**
     * 获取服务器时间
     *
     * @return mixed
     */
    public static function getServerTime()
    {
        static::$serverParams['sTime'] = date('Y.m.d H:i:s');
        return static::$serverParams['sTime'];
    }

    /**
     * 获取 cpu 负载信息
     */
    public static function getCpuLoadAvg()
    {
        $cpuLoadAvg = [];
        $tmpCpu = '';
        $fp = popen('top -bn2|grep Cpu|egrep -v "grep"|awk -F "[ ,]+" \'END{print $2,$4,$8,$10,$12,$14,$16}\'', 'r');
        while (!feof($fp)) {
            $tmpCpu .= fread($fp, self::TRILLION_BYTE);
        }
        pclose($fp);
        $result = explode(' ', trim($tmpCpu, "\n"));
        if (!empty($result)) {
            return [
                'us' => $result[0],
                'sy' => $result[1],
                'idle' => $result[2],
                'wa' => $result[3],
                'hi' => $result[4],
                'si' => $result[5],
                'st' => $result[6],
            ];
        }
        return $cpuLoadAvg;
    }

    /**
     * 内存负载
     *
     * @return array
     */
    public static function getMemoryLoadAvg()
    {
        $memLoadAvg = [];
        $fp = popen('top -bn1 | grep "KiB Mem" | awk \'{print ($4,$6,$8,$10)}\'', 'r');
        $mem = "";
        while (!feof($fp)) {
            $mem .= fread($fp, self::TRILLION_BYTE);
        }
        pclose($fp);
        $tmpMem = explode("\n", $mem);
        if (!empty($tmpMem)) {
            $result = explode(' ', $tmpMem[0]);
            return [
                'total' => $result[0],
                'free' => $result[1],
                'used' => $result[2],
                'buff|cache' => $result[3],
                'usedPercent' => round((($result[2] / self::TRILLION_BYTE) / ($result[1] / self::TRILLION_BYTE)) * self::PERCENTAGE, 2) . '%'
            ];
        }
        return $memLoadAvg;
    }

    /**
     * 内存负载
     *
     * @return array
     */
    public static function getMemoryLoadAvgFromMemInfo()
    {
        $memInfo = @file("/proc/meminfo");
        if (empty($memInfo))
            return [];
        $memInfo = implode('', $memInfo);
        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $memInfo, $matchesMem);
        preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $memInfo, $matchesBuffers);
        $memLoadAvg['memTotal'] = round($matchesMem[1][0] / self::TRILLION_BYTE, 2);
        $memLoadAvg['memFree'] = round($matchesMem[2][0] / self::TRILLION_BYTE, 2);
        $memLoadAvg['memBuffers'] = round($matchesBuffers[1][0] / self::TRILLION_BYTE, 2);
        $memLoadAvg['memCached'] = round($matchesMem[3][0] / self::TRILLION_BYTE, 2);
        $memLoadAvg['memUsed'] = $memLoadAvg['memTotal'] - $memLoadAvg['memFree'];
        $memLoadAvg['memPercent'] = (floatval($memLoadAvg['memTotal']) != 0) ? round($memLoadAvg['memUsed'] / $memLoadAvg['memTotal'] * self::PERCENTAGE, 2) . '%' : '0.0%';
        // 真实内存使用
        $memLoadAvg['memRealUsed'] = $memLoadAvg['memTotal'] - $memLoadAvg['memFree'] - $memLoadAvg['memCached'] - $memLoadAvg['memBuffers'];
        // 真实空闲
        $memLoadAvg['memRealFree'] = $memLoadAvg['memTotal'] - $memLoadAvg['memRealUsed'];
        // 真实内存使用率
        $memLoadAvg['memRealPercent'] = (floatval($memLoadAvg['memTotal']) != 0) ? round($memLoadAvg['memRealUsed'] / $memLoadAvg['memTotal'] * self::PERCENTAGE, 2) . '%' : '0.0%';
        // Cached内存使用率
        $memLoadAvg['memCachedPercent'] = (floatval($memLoadAvg['memCached']) != 0) ? round($memLoadAvg['memCached'] / $memLoadAvg['memTotal'] * self::PERCENTAGE, 2) . '%' : '0.0%';
        $memLoadAvg['swapTotal'] = round($matchesMem[4][0] / self::TRILLION_BYTE, 2);
        $memLoadAvg['swapFree'] = round($matchesMem[5][0] / self::TRILLION_BYTE, 2);
        $memLoadAvg['swapUsed'] = round($memLoadAvg['swapTotal'] - $memLoadAvg['swapFree'], 2);
        $memLoadAvg['swapPercent'] = (floatval($memLoadAvg['swapTotal']) != 0) ? round($memLoadAvg['swapUsed'] / $memLoadAvg['swapTotal'] * self::PERCENTAGE, 2) . '%' : '0.0%';
        return [
            'memTotal' => round($matchesMem[1][0] / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'memFree' => round($matchesMem[2][0] / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'memBuffers' => round($matchesBuffers[1][0] / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'memCached' => round($matchesMem[3][0] / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'memUsed' => round(($memLoadAvg['memTotal'] - $memLoadAvg['memFree']) / self::TRILLION_BYTE, 2),
            'memPercent' => $memLoadAvg['memPercent'],
            'memRealUsed' => round($memLoadAvg['memRealUsed'] / self::TRILLION_BYTE, 2),
            'memRealFree' => round($memLoadAvg['memRealFree'] / self::TRILLION_BYTE, 2),
            'memRealPercent' => $memLoadAvg['memRealPercent'],
            'memCachedPercent' => $memLoadAvg['memCachedPercent'],
            'swapTotal' => round($matchesMem[4][0] / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'swapFree' => round($matchesMem[5][0] / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'swapUsed' => round(($memLoadAvg['swapTotal'] - $memLoadAvg['swapFree']) / self::TRILLION_BYTE / self::TRILLION_BYTE, 2),
            'swapPercent' => $memLoadAvg['swapPercent'],
        ];
    }

    /**
     * 带宽负载
     *
     * @return array
     */
    public static function getNetworkLoadAvg()
    {
        /**
         * 下面的参数，请不要修改
         */
        $influx_kbps = 0;
        $outflux_kbps = 0;
        $influx_mbps = 0;
        $outflux_mbps = 0;
        $unsignedLongMax = 4294967295; // 做溢出处理
        $statFreq = 1;
        $ethXname = 'eth0';
        /**
         * 开始
         */
        $networkStart = static::computerNetwork($ethXname);
        sleep($statFreq);
        /**
         * 结束
         */
        $networkEnd = static::computerNetwork($ethXname);

        /**
         * 入带宽
         */
        if ($networkEnd[0] > $networkStart[0]) {
            $incoming = $networkEnd[0] - $networkStart[0];
        } else {
            $incoming = ($unsignedLongMax - $networkStart[0]) + $networkEnd[0];
        }

        /**
         * 出带宽
         */
        if ($networkEnd[8] > $networkStart[8]) {
            $outgoing = $networkEnd[8] - $networkStart[8];
        } else {
            $outgoing = ($unsignedLongMax - $networkStart[8]) + $networkEnd[8];
        }

        $incoming /= $statFreq;
        $outgoing /= $statFreq;
        /**
         * to bps
         */
        $incomingBpsByte = $incoming * 8;
        $outgoingBpsByte = $outgoing * 8;

        /**
         * to kbps
         */
        $incomingKbps = $incomingBpsByte / self::TRILLION_BYTE;
        $outgoingKbps = $outgoingBpsByte / self::TRILLION_BYTE;

        /**
         * to mbps
         */
        $incomingMbps = $incomingKbps / self::TRILLION_BYTE;
        $outgoingMbps = $outgoingKbps / self::TRILLION_BYTE;

        return [
            'incoming' => round($incomingMbps, 2), // 输入Mbps
            'outgoing' => round($outgoingMbps, 2)  // 输出Mbps
        ];
    }

    /**
     * 取出带宽值
     *
     * @param $ethXname
     * @return array
     */
    public static function computerNetwork($ethXname)
    {
        $networkStartByte = "cat /proc/net/dev|grep {$ethXname}";
        $fp = popen($networkStartByte, 'r');
        $net = "";
        while (!feof($fp)) {
            $net .= fread($fp, self::TRILLION_BYTE);
        }
        pclose($fp);
        $tmpNetwork = explode("\n", $net);
        $matches = preg_replace('/\s\s+/', ' ', $tmpNetwork[0]);
        $networkStart = str_replace(" {$ethXname}: ", '', $matches);
        return explode(' ', $networkStart);
    }

    /**
     * 获取磁盘负载
     *
     * @return mixed
     */
    public static function getDistLoadAvg()
    {
        $fp = popen('df -lh | grep -E "^(/)"', 'r');
        $rs = fread($fp, self::TRILLION_BYTE);
        $rs = preg_replace("/\s{2,}/", ' ', $rs);  // 把多个空格换成 “_”
        $hd = explode(" ", $rs);
        pclose($fp);
        return [
            'total' => trim($hd[1], 'G'), // 磁盘总大小
            'free' => trim($hd[3], 'G'), // 可用
            'used' => trim($hd[2], 'G'), // 已用
            'usage' => $hd[4], // 挂载点 百分比
        ];
    }

    public static function operatingSystem()
    {
        return DIRECTORY_SEPARATOR == '/' ? true : false;
    }

    public static function getSystemInfo()
    {
        $sysInfo = [];
        switch (PHP_OS) {
            case'Linux':
                $sysInfo = static::systemLinux();
                break;
            case'FreeBSD':
                $sysInfo = static::sysFreebsd();
                break;
            default:
                # code...
                break;
        }
        return $sysInfo;
    }

    /**
     * Linux 系统探测
     *
     * @return bool
     */
    public static function systemLinux()
    {
        $str = @file("/proc/cpuinfo");//获取CPU信息
        if (!$str) return false;
        $str = implode("", $str);
        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);//CPU 名称
        @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);//CPU频率
        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);//CPU缓存
        @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);//
        if (is_array($model[1])) {
            $cpunum = count($model[1]);
            $x1 = $cpunum > 1 ? ' ×' . $cpunum : '';
            $mhz[1][0] = ' | 频率:' . $mhz[1][0];
            $cache[1][0] = ' | 二级缓存:' . $cache[1][0];
            $bogomips[1][0] = ' | Bogomips:' . $bogomips[1][0];
            $res['cpu']['num'] = $cpunum;
            $res['cpu']['model'][] = $model[1][0] . $mhz[1][0] . $cache[1][0] . $bogomips[1][0] . $x1;
            if (is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
            if (is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
            if (is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
            if (is_array($res['cpu']['bogomips'])) $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']);
        }
        //服务器运行时间
        $str = @file("/proc/uptime");
        if (!$str) return false;
        $str = explode(" ", implode("", $str));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        $res['uptime'] = $days . "天" . $hours . "小时" . $min . "分钟";
        // LOAD AVG 系统负载
        $str = @file("/proc/loadavg");
        if (!$str) return false;
        $str = explode(" ", implode("", $str));
        $str = array_chunk($str, 4);
        $res['loadAvg'] = implode(" ", $str[0]);
        return $res;
    }

    /**
     * freeBSD系统探测
     *
     * @return mixed
     */
    public static function sysFreebsd()
    {
        $res['cpu']['num'] = static::doCommand('sysctl', 'hw.ncpu');//CPU
        $res['cpu']['model'] = static::doCommand('sysctl', 'hw.model');
        $res['loadAvg'] = static::doCommand('sysctl', 'vm.loadavg');//Load AVG  系统负载
        //uptime
        $buf = static::doCommand('sysctl', 'kern.boottime');
        $buf = explode(' ', $buf);
        $sys_ticks = time() - intval($buf[3]);
        $min = $sys_ticks / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        $res['uptime'] = $days . '天' . $hours . '小时' . $min . '分钟';
        return $res;
    }

    /**
     * 执行系统命令FreeBSD
     *
     * @param $cName
     * @param $args
     * @return bool|string
     */
    public static function doCommand($cName, $args)
    {
        $cName = empty($cName) ? 'sysctl' : trim($cName);
        if (empty($args)) return false;
        $args = '-n ' . $args;
        $buffers = '';
        $command = static::findCommand($cName);
        if (!$command) return false;
        if ($fp = @popen("$command $args", 'r')) {
            while (!@feof($fp)) {
                $buffers .= @fgets($fp, 4096);
            }
            pclose($fp);
            return trim($buffers);
        }
        return false;
    }

    /**
     * 确定shell位置
     *
     * @param $commandName
     * @return bool|string
     */
    public static function findCommand($commandName)
    {
        foreach (array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin') as $path) {
            if (@is_executable("$path/$commandName"))
                return "$path/$commandName";
        }
        return false;
    }

    public static function networkLoadAvg()
    { //网卡流量
        $strs = @file("/proc/net/dev");
        $lines = count($strs);
        for ($i = 2; $i < $lines; $i++) {
            preg_match_all("/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info);
            $res['OutSpeed'][$i] = $info[10][0];
            $res['InputSpeed'][$i] = $info[2][0];
            $res['NetOut'][$i] = static::formatSize($info[10][0]);
            $res['NetInput'][$i] = static::formatSize($info[2][0]);
            $res['NetWorkName'][$i] = $info[1][0];
        }
        return $res;
    }

    /**
     * 单位转换
     *
     * @param $size
     * @return string
     */
    public static function formatSize($size)
    {
        $danwei = array(' B ', ' K ', ' M ', ' G ', ' T ');
        $allsize = array();
        $i = 0;
        for ($i = 0; $i < 5; $i++) {
            if (floor($size / pow(self::TRILLION_BYTE, $i)) == 0) {
                break;
            }
        }
        for ($l = $i - 1; $l >= 0; $l--) {
            $allsize1[$l] = floor($size / pow(self::TRILLION_BYTE, $l));
            $allsize[$l] = $allsize1[$l] - $allsize1[$l + 1] * self::TRILLION_BYTE;
        }
        $len = count($allsize);
        for ($j = $len - 1; $j >= 0; $j--) {
            $fsize = $fsize . $allsize[$j] . $danwei[$j];
        }
        return $fsize;
    }
}