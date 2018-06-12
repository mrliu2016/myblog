<?php
//
//  Copyright (c) 2009 Facebook
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

//
// This file defines the interface iXHProfRuns and also provides a default
// implementation of the interface (class XHProfRuns).
//

/**
 * iXHProfRuns interface for getting/saving a XHProf run.
 *
 * Clients can either use the default implementation,
 * namely XHProfRuns_Default, of this interface or define
 * their own implementation.
 *
 * @author Kannan
 */
interface iXHProfRuns
{

    /**
     * Returns XHProf data given a run id ($run) of a given
     * type ($type).
     *
     * Also, a brief description of the run is returned via the
     * $run_desc out parameter.
     */
    public function get_run($run_id, $type, &$run_desc);

    /**
     * Save XHProf data for a profiler run of specified type
     * ($type).
     *
     * The caller may optionally pass in run_id (which they
     * promise to be unique). If a run_id is not passed in,
     * the implementation of this method must generated a
     * unique run id for this saved XHProf run.
     *
     * Returns the run id for the saved XHProf run.
     *
     */
    public function save_run($xhprof_data, $type, $run_id = null);
}


/**
 * XHProfRuns_Default is the default implementation of the
 * iXHProfRuns interface for saving/fetching XHProf runs.
 *
 * It stores/retrieves runs to/from a filesystem directory
 * specified by the "xhprof.output_dir" ini parameter.
 *
 * @author Kannan
 */
class XHProfRuns_Default implements iXHProfRuns
{

    private $dir = '';
    private $suffix = 'xhprof';

    private function gen_run_id($type)
    {
        return uniqid();
    }

    private function file_name($run_id, $type)
    {

        $file = "$run_id.$type." . $this->suffix;

        if (!empty($this->dir)) {
            $file = $this->dir . "/" . $file;
        }
        return $file;
    }

    public function __construct($dir = null)
    {

        // if user hasn't passed a directory location,
        // we use the xhprof.output_dir ini setting
        // if specified, else we default to the directory
        // in which the error_log file resides.
        $this->db();
        if (empty($dir)) {
            $dir = ini_get("xhprof.output_dir");
            if (empty($dir)) {

                $dir = sys_get_temp_dir();

                xhprof_error("Warning: Must specify directory location for XHProf runs. " .
                    "Trying {$dir} as default. You can either pass the " .
                    "directory location as an argument to the constructor " .
                    "for XHProfRuns_Default() or set xhprof.output_dir " .
                    "ini param.");
            } else {
                $dir = $dir . "/" . date('Ymd');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
            }
        }
        $this->dir = $dir;
    }

    protected function db()
    {
        global $_xhprof;
        $linkid = mysqli_connect($_xhprof['dbhost'], $_xhprof['dbuser'], $_xhprof['dbpass']);
        if ($linkid === FALSE) {
            xhprof_error("Could not connect to db");
            $run_desc = "could not connect to db";
            throw new Exception("Unable to connect to database");
            return false;
        }
        mysqli_select_db($linkid, $_xhprof['dbname']);
        $this->linkID = $linkid;
    }

    public function get_run($run_id, $type, &$run_desc)
    {
        $file_name = $this->file_name($run_id, $type);

        if (!file_exists($file_name)) {
            xhprof_error("Could not find file $file_name");
            $run_desc = "Invalid Run Id = $run_id";
            return null;
        }

        $contents = file_get_contents($file_name);
        $run_desc = "XHProf Run (Namespace=$type)";
        return unserialize($contents);
    }

    public function save_run($xhprof_data, $type, $run_id = null)
    {
        global $_xhprof;
        // Use PHP serialize function to store the XHProf's
        // raw profiler data.
        $sql['rt'] = isset($xhprof_data['rt']) ? $xhprof_data['rt'] : '';
        $xhprof_dataTmp = serialize($xhprof_data);

        if ($run_id === null) {
            $run_id = $this->gen_run_id($type);
        }

        $file_name = $this->file_name($run_id, $type);
        $file = fopen($file_name, 'w');

        if ($file) {
            fwrite($file, $xhprof_dataTmp);
            fclose($file);
        } else {
            xhprof_error("Could not open $file_name\n");
        }

        // echo "Saved run in {$file_name}.\nRun id = {$run_id}.\n";
        $sql['get'] = mysqli_real_escape_string($this->linkID, serialize($_GET));
        $sql['cookie'] = mysqli_real_escape_string($this->linkID, serialize($_COOKIE));

        //This code has not been tested
        if ($_xhprof['savepost']) {
            $sql['post'] = mysqli_real_escape_string($this->linkID, serialize($_POST));
        } else {
            $sql['post'] = mysqli_real_escape_string($this->linkID, serialize(array("Skipped" => "Post data omitted by rule")));
        }

        $sql['pmu'] = isset($xhprof_data['main()']['pmu']) ? $xhprof_data['main()']['pmu'] : '';
        $sql['wt'] = isset($xhprof_data['main()']['wt']) ? $xhprof_data['main()']['wt'] : '';
        $sql['cpu'] = isset($xhprof_data['main()']['cpu']) ? $xhprof_data['main()']['cpu'] : '';

        $sql['namespace'] = isset($_xhprof['namespace']) ? $_xhprof['namespace'] : '';
        $sql['uid'] = isset($xhprof_data['uid']) ? $xhprof_data['uid'] : 0;


        // The value of 2 seems to be light enugh that we're not killing the server, but still gives us lots of breathing room on
        // full production code.
        $sql['data'] = mysqli_real_escape_string($this->linkID, gzcompress(serialize($xhprof_data), 2));

        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
        $sname = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';


        $sql['url'] = mysqli_real_escape_string($this->linkID, $url);
        $sql['c_url'] = mysqli_real_escape_string($this->linkID, _urlSimilartor($_SERVER['REQUEST_URI']));
        $sql['servername'] = mysqli_real_escape_string($this->linkID, $sname);
        $sql['type'] = (int)(isset($xhprof_details['type']) ? $xhprof_details['type'] : 0);
        $sql['timestamp'] = mysqli_real_escape_string($this->linkID, $_SERVER['REQUEST_TIME']);
        $sql['server_id'] = mysqli_real_escape_string($this->linkID, $_xhprof['servername']);
        $sql['aggregateCalls_include'] = getenv('xhprof_aggregateCalls_include') ? getenv('xhprof_aggregateCalls_include') : '';

//        $query = "INSERT INTO `details` (`id`, `url`, `c_url`, `timestamp`, `server name`, `perfdata`, `type`, `cookie`, `post`, `get`, `pmu`, `wt`, `cpu`, `server_id`, `aggregateCalls_include`) VALUES('$run_id', '{$sql['url']}', '{$sql['c_url']}', FROM_UNIXTIME('{$sql['timestamp']}'), '{$sql['servername']}', '{$sql['data']}', '{$sql['type']}', '{$sql['cookie']}', '{$sql['post']}', '{$sql['get']}', '{$sql['pmu']}', '{$sql['wt']}', '{$sql['cpu']}', '{$sql['server_id']}', '{$sql['aggregateCalls_include']}')";
        $query = "INSERT INTO `details` (`runId`,`namespace`,`rt`, `url`, `c_url`, `timestamp`, `servername`, `perfdata`, `type`, `cookie`, `post`, `get`, `pmu`, `wt`, `cpu`, `server_id`, `aggregateCalls_include`,`uid`) VALUES('$run_id', '{$sql['namespace']}','{$sql['rt']}','{$sql['url']}', '{$sql['c_url']}', FROM_UNIXTIME('{$sql['timestamp']}'), '{$sql['servername']}', '{$sql['data']}', '{$sql['type']}', '{$sql['cookie']}', '{$sql['post']}', '{$sql['get']}', '{$sql['pmu']}', '{$sql['wt']}', '{$sql['cpu']}', '{$sql['server_id']}', '{$sql['aggregateCalls_include']}', '{$sql['uid']}')";

        mysqli_query($this->linkID, $query);
        if (mysqli_affected_rows($this->linkID) == 1) {
            return $run_id;
        } else {
            global $_xhprof;
            if ($_xhprof['display'] === true) {
                echo "Failed to insert: $query <br>\n";
                var_dump(mysqli_error($this->linkID));
                var_dump(mysqli_errno($this->linkID));
            }
            return -1;
        }
    }

    function list_runs()
    {
        if (is_dir($this->dir)) {
            echo "<hr/>Existing runs:\n<ul>\n";
            $files = glob("{$this->dir}/*.{$this->suffix}");
            usort($files, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
            foreach ($files as $file) {
                list($run, $source) = explode('.', basename($file));
                echo '<li><a href="' . htmlentities($_SERVER['SCRIPT_NAME'])
                    . '?run=' . htmlentities($run) . '&source='
                    . htmlentities($source) . '">'
                    . htmlentities(basename($file)) . "</a><small> "
                    . date("Y-m-d H:i:s", filemtime($file)) . "</small></li>\n";
            }
            echo "</ul>\n";
        }
    }
}
