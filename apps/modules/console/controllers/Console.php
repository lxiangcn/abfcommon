<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Console
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-31 10:52:52
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Console extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('console/model_console', 'console');
    }

    /** readlog  show log message
     * @param $type : list is <br>
     *   init: show readlog tpl
     *   read: to read and parse log summary by log-date<br>
     *   view: view detail message near the current line<br>
     *   zip: to zip the log file for download<br>
     *   listDB: to list the import summary in database group by month<br>
     *   showDB: to show the current month logs group by days<br>
     *   importDB: to import log file into database<br>
     * @return string json-string
     */
    public function index($type = 'init') {
        $action    = $_SERVER['REQUEST_URI'];
        $versionNo = $this->getCurVersion();
        //if (!$retArr['success']) {
        //return $this->ajaxResponse($action, $versionNo, false, 'login error!', $retArr);
        // }
        switch ($type) {
        case 'init':
            $this->toLoadTpl('readlog');
            return;
        case 'read':
            $retArr = $this->console->listlog($_POST);
            echo json_encode($retArr);return;
        case 'view':
            $retArr = $this->console->viewlog($_POST);
            echo json_encode($retArr);return;
        case 'zip':
            $retArr = $this->console->ziplog($_POST);
            break;
        case 'ziperror':
            $retArr = $this->console->ziperrorlog($_POST);
            break;
        case 'listLogMonth':
            $retArr = $this->console->listLogByMonth();
            break;
        case 'listLogDay':
            $retArr = $this->console->listLogByDay($_POST);
            break;
        case 'importLogDB':
            $retArr = $this->console->importDB($_POST);
            break;
        default:
            $retArr = array('success' => false, 'message' => '未知类型[' . $type . ']。');
            break;
        }
        return $this->ajaxResponse($action, $versionNo, true, 'do over', $retArr);
    }

    /**
     * to load tpl with default layout
     * @param  string  $content   the name of tpl need to render
     * @return array( success:boolean, message: mixed )
     */
    private function toLoadTpl($content) {
        $this->output("admin_layout", array("body" => "console/index"), $data);
    }

    /**
     * varsion
     * @return [string] [description]
     */
    private function getCurVersion() {
        return '0.0.1';
    }

}
