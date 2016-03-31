<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Model_console
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-31 15:43:13
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Model_console extends MY_Model {

    function __construct() {
        parent::__construct();
        // $this->load_table('console');
    }

    private $__read_log__ = null;
    public function listlog($post) {
        $date = $post['date'];
        log_message('debug', '=>1.to list log message, date[' . $date . ']');
        $filename = FCPATH . 'data/logs/log-' . $date . '.php'; //文件路径
        if (!file_exists($filename)) {
            log_message('error', '# list log error, log not exists.');
            return $this->makeRetMsg(false, '日志文件不存在。');
        }
        $logNums = array('DEBUG' => 0, 'INFO' => 0, 'ERROR' => 0, 'SQL' => 0);
        $errs    = array();
        //create current today temp log
        $filename = $this->createTemplog($date, $filename);
        $strList  = new SplFileObject($filename);
        $j        = 0;
        $msgMap   = array();
        foreach ($strList as $idx => $line) {
            $lv = trim(substr($line, 0, 5));
            if (isset($logNums[$lv])) {
                $logNums[$lv] += 1;
            }
            if ($lv == 'ERROR') {
                $po = $this->parseLog($idx, $line);
                if (isset($msgMap[$po['msg']])) {
                    $place                = $msgMap[$po['msg']];
                    $errs[$place]['nums'] = $errs[$place]['nums'] + 1;
                    $errs[$place]['at']   = $po['at'];
                    $errs[$place]['time'] = $po['time'];
                } else {
                    $msgMap[$po['msg']] = $j;
                    $tmp                = $po['msg'];
                    $jsonRet            = json_encode($tmp);
                    if (empty($jsonRet)) {
                        $po['msg'] = '不是合法的json格式,记录内容可能存在乱码,请检查。';
                    }
                    $errs[$j]         = $po;
                    $errs[$j]['nums'] = 1;
                    $j++;
                }
            }
        }
        //delete current today temp log
        $this->removeTemplog($date, $filename);

        log_message('debug', '<=1.list log over, err lines[' . count($errs) . '].');
        return array('success' => true, 'message' => 'list log over.', 'nums' => $logNums, 'errs' => $errs);
    }

    /**
     * parse line in log, return:{ at:lineno,  time:..,  msg:.. }
     * @param  [type] $idx  [description]
     * @param  [type] $line [description]
     * @return [type]       [description]
     */
    private function parseLog($idx, $line) {
        if (strlen($line) < 6) {
            return array('at' => $idx, 'time' => '', 'msg' => '');
        }
        $list = explode(',', substr($line, 7));
        $time = $list[0];
        $msg  = substr($line, strpos($line, '-->') + 4);
        return array('at' => $idx, 'time' => $time, 'msg' => $msg);
    }

    /**
     * [viewlog description]
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public function viewlog($post) {
        $date = $post['date'];
        $at   = intval($post['at']);
        log_message('debug', '=>1.to view log, date[' . $date . '], line[' . $at . ']');
        $filename = FCPATH . 'data/logs/log-' . $date . '.php'; //文件路径
        if (!file_exists($filename)) {
            log_message('error', '# list log error, log not exists.');
            return $this->makeRetMsg(false, '日志文件不存在。');
        }
        $from  = max(1, $at - 15);
        $limit = 30;
        //create current today temp log
        $filename = $this->createTemplog($date, $filename);

        $texts  = $this->getFileLines($filename, $from, $limit);
        $logDat = array();
        foreach ($texts as $idx => $line) {
            $jsonRet = json_encode($line);
            if (empty($jsonRet)) {
                $line = substr($line, 0, strpos($line, '-->') + 3) . '不是合法的json格式,记录内容可能存在乱码。';
            }
            array_push($logDat, $line);
        }
        //delete current today temp log
        $this->removeTemplog($date, $filename);

        return array('success' => true, 'logs' => $logDat);
    }

    /**
     * get some line text from big file
     * @param  [type]  $filename  [description]
     * @param  integer $startLine [description]
     * @param  integer $limitLine [description]
     * @return [type]             [description]
     */
    private function getFileLines($filename, $startLine = 1, $limitLine = 50) {
        $content = false;
        $fp      = new SplFileObject($filename, 'rb');
        $fp->seek($startLine - 1); // 转到第N行, seek方法参数从0开始计数
        for ($i = 0; $i < $limitLine; $i++) {
            $content[] = $fp->current(); // current()获取当前行内容
            $fp->next(); // 下一行
            if (!$fp->current()) {
                break;
            }

        }
        return $content;
    }

    /**
     * [ziplog description]
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public function ziplog($post) {
        $date = $post['date'];
        log_message('debug', '=>1.to zip log file, date[' . $date . ']');
        $pfile = FCPATH . 'data/logs/log-' . $date . '.php'; //log file
        if (!file_exists($pfile)) {
            log_message('error', '# zip log error, log not exists.');
            return $this->makeRetMsg(false, '日志文件不存在。');
        }
        $zip = new ZipArchive();
        // create zip file
        $zfile = FCPATH . 'data/logs/log-' . $date . '.zip';
        if (!$zip->open($zfile,ZipArchive::OVERWRITE)) {
            log_message('error', 'failed to create zip, name is [' . $zfile . ']');
            return $this->makeRetMsg(false, '创建压缩文件失败。');
        }
        log_message('debug', '<= 1.create empty zip ok.');
        $target = basename($pfile);

        if (!$zip->addFile($pfile, $target)) {
            log_message('error', 'failed to zip log file.');
            return $this->makeRetMsg(false, '压缩文件失败，无法压缩日志文件。');
        }
        return $this->makeRetMsg(true, '创建日志压缩文件成功。');
    }

    /**
     * 压缩error日志
     * @param $post date
     * @return json
     */
    public function ziperrorlog($post) {
        $date = $post['date'];
        log_message('debug', '=>1.to zip log file, date[' . $date . ']');
        $pfile = FCPATH . 'data/logs/error-log-' . $date . '.php'; //log file
        if (!file_exists($pfile)) {
            log_message('error', '# zip log error, log not exists.');
            return $this->makeRetMsg(false, '日志文件不存在。');
        }
        $zip = new ZipArchive();
        // create zip file
        $zfile = FCPATH . 'data/logs/error-log-' . $date . '.zip';
        if (!$zip->open($zfile, ZipArchive::OVERWRITE)) {
            log_message('error', 'failed to create zip, name is [' . $zfile . ']');
            return $this->makeRetMsg(false, '创建压缩文件失败。');
        }
        log_message('debug', '<= 1.create empty zip ok.');
        $target = basename($pfile);
        if (!$zip->addFile($pfile, $target)) {
            log_message('error', 'failed to zip log file.');
            return $this->makeRetMsg(false, '压缩文件失败，无法压缩日志文件。');
        }
        return $this->makeRetMsg(true, '创建日志压缩文件成功。');
    }

    public function downlog($date) {
        // send HTTP header name
        header("Content-type: application/x-zip-compressed");
        $strName  = FCPATH . 'data/logs/log-' . $date . '.zip';
        $filename = basename($strName);
        log_message('error', $filename);
        // process file name in Chinese
        $ua               = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } elseif (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        // send the to the client
        readfile($strName);
    }

    /**
     * down error log
     * @param $date
     */
    public function downerrorlog($date) {
        // send HTTP header name
        header("Content-type: application/x-zip-compressed");
        $strName  = FCPATH . 'data/logs/error-log-' . $date . '.zip';
        $filename = basename($strName);
        log_message('error', $filename);
        // process file name in Chinese
        $ua               = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } elseif (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        // send the to the client
        readfile($strName);
    }
    public function listLogByMonth() {
        log_message('debug', '=>1.to list log db records.');
        $sql = "select schema_name from information_schema.schemata
            where schema_name = 'fast_log' ";
        $rt = $this->executeReadSql($sql);
        if (count($rt) == 0) {
            $this->executeWriteSql('create database if not exists fast_log ');
            return array();
        }
        $sql2 = "SELECT `TABLES`.`TABLE_NAME` tname  FROM  `information_schema`.`TABLES`
     WHERE `TABLES`.`TABLE_TYPE` = 'base table' AND `TABLES`.`TABLE_SCHEMA` = 'fast_log'
        AND `TABLES`.`TABLE_NAME` like 'log_gravity_%'
     ORDER BY `TABLES`.`TABLE_NAME` DESC";
        $rt2 = $this->executeReadSql($sql2);
        if (count($rt2) == 0) {return array();}
        $logmonths = array();
        foreach ($rt2 as $idx => $obj) {
            $table       = $obj['tname'];
            $sql3        = "SELECT count(DISTINCT t.C_DATE) days, count(1) logs FROM fast_log.$table t";
            $rt3         = $this->executeReadSql($sql3);
            $logmonths[] = array(
                'table' => $table,
                'month' => substr($table, 12, 4) . '年' . substr($table, 16, 2) . '月',
                'days'  => count($rt3) > 0 ? $rt3[0]['days'] : 0,
                'logs'  => count($rt3) > 0 ? $rt3[0]['logs'] : 0,
            );
        }
        return array('success' => true, 'logmonth' => $logmonths);
    }
    public function listLogByDay($post) {
        $table = $post['table'];
        $sql   = "SELECT `TABLES`.`TABLE_NAME` tname  FROM  `information_schema`.`TABLES`
     WHERE `TABLES`.`TABLE_TYPE` = 'base table' AND `TABLES`.`TABLE_SCHEMA` = 'fast_log'
        AND `TABLES`.`TABLE_NAME` = '$table' limit 1 ";
        $rt = $this->executeReadSQL($sql);
        if (count($rt) == 0) {
            return $this->makeRetMsg(false, '日志记录月份表不存在！');
        }
        $sql2 = "SELECT C_DATE date, sum(IF(C_SEVIRITY='ERROR',1,0)) errs,
          sum(IF(C_SEVIRITY='DEBUG',1,0)) debugs,  sum(IF(C_SEVIRITY='INFO',1,0)) infos,
          sum(IF(C_SEVIRITY='WARN',1,0)) warns,   sum(IF(C_SEVIRITY='SQL',1,0)) sqls
        FROM fast_log.$table group by date order by date asc ";
        $logdays = $this->executeReadSQL($sql2);
        return array('success' => true, 'logday' => $logdays);
    }
    public function importDB($post) {
        $dateList = $post['dates'];
        if (empty($dateList) || !is_array($dateList)) {
            return $this->makeRetMsg(false, '需要导入的日期项未指定！');
        }
        $conn = $this->mysqlConnect();
        if (!$conn) {
            return $this->makeRetMsg(false, '未发现可用的数据库连接!');
        }
        foreach ($dateList as $idx => $date) {
            $this->importDB_day($date, $conn);
        }
        $this->mysqlClose($conn);
        return $this->makeRetMsg(true, '导入日志成功！');
    }
    //interface provice to console task call
    public function import_log($date, $overwrite) {
        $conn = $this->mysqlConnect();
        if (!$conn) {
            echo 'failed to database connnect.';
            return false;
        }
        //$retMsg = $this->importDB_day($date,$conn,true);
        $retMsg = $this->importDB_day($date, $conn, false);
        if ($retMsg == 'OK') {
            echo 'log at [' . $date . '] import over.';
        } else {
            echo $retMsg;
        }
        $this->mysqlClose($conn);
        return true;
    }
    private function mysqlConnect() {
        //use mysql connect to avoid execute sql be logged
        include APPPATH . 'config/' . ENVIRONMENT . '/database.php';
        if (!isset($db)) {
            return false;
        }
        $host   = $db['default']['hostname'];
        $uname  = $db['default']['username'];
        $upass  = $db['default']['password'];
        $dbname = 'fast_log';
        $conn   = @mysqli_connect($host, $uname, $upass, $dbname);
        return $conn;
    }
    private function mysqlClose($conn) {
        @mysqli_close($conn);
    }

    /**
     * when deal the today log, make temp log file to avoid dead loop
     * @param  [type] $date     [description]
     * @param  [type] $filename [description]
     * @return [type]           [description]
     */
    private function createTemplog($date, $filename) {
        $today = date('Y-m-d', time());
        if ($date != $today) {return $filename;}

        $souce  = FCPATH . 'data/logs/log-' . $today . '.php';
        $target = FCPATH . 'data/logs/log-' . $today . '_tmp.php';
        try {@copy($souce, $target);} catch (Exception $e) {}
        return $target;
    }
    private function removeTemplog($date, $filename) {
        $today = date('Y-m-d', time());
        if ($date != $today) {return;}
        try {@unlink($filename);} catch (Exception $e) {}
    }
    /**
     * u1.make common return msg
     * @param  [type] $success [description]
     * @param  [type] $msg     [description]
     * @return [type]          [description]
     */
    private function makeRetMsg($success, $msg) {
        return array('success' => $success, 'message' => $msg);
    }
}
?>