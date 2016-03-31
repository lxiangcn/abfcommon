<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon 全局控制
 *
 * @package Base_Controller
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-31 15:26:20
 * @author Alex Liu<lxiangcn@gmail.com>
 */

/* load the MX_Controller class */
require APPPATH . "third_party/MX/Controller.php";

class Base_Controller extends MX_Controller {

    /**
     * 返回消息类型 参数有 'error', 'notice', 'success'
     *
     * @var string
     */
    public $message_type = '';

    /**
     * 返回消息内容
     *
     * @var string
     */
    public $message = '';

    /**
     * 返回URL
     *
     * @var string
     */
    public $redirect = '';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 设置返回错误信息
     *
     * @param string
     * @param array
     */
    public function error($message, $addon_data = NULL) {
        $this->message_type = 'error';
        $this->message      = $message;

        if (!isset($this->redirect) && !empty($_SERVER['HTTP_REFERER'])) {
            $this->redirect = $_SERVER['HTTP_REFERER'];
        }

        $this->response($addon_data);
    }

    /**
     * 设置返回成功信息
     *
     * @param string
     * @param array
     */
    public function success($message, $addon_data = NULL) {
        $this->message_type = 'success';
        $this->message      = $message;

        $this->response($addon_data);
    }

    /**
     * 设置返回通知信息
     *
     * @param string
     * @param array
     */
    public function notice($message, $addon_data = NULL) {
        $this->message_type = 'notice';
        $this->message      = $message;

        $this->response($addon_data);
    }

    /**
     * 设置返回的提示信息
     *
     * @param array
     */
    public function response($addon_data = NULL) {
        $data = array(
            'message_type' => $this->message_type,
            'message'      => $this->message,
        );

        if (!empty($addon_data)) {
            $data = array_merge($data, $addon_data);
        }

        $this->session->set_flashdata($this->message_type, ($this->message));
    }

    /**
     * jsonResponse : JSON response
     * @param  array $data
     * @return none
     */
    protected function jsonResponse($data) {
        $this->echoHeaderForJson();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return;
    }
    protected function htmlResponse($data) {
        $this->echoHeaderForHtml();
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * normalResponse : normal response
     * @param  array    $data
     * @return none
     */
    protected function normalResponse($data) {
        return $this->view();
    }

    //u1.parese before json_encode,change return unicode to chinese*/
    private function url_encode($obj) {
        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (is_bool($value)) {continue;} //dont change boolean variable
                $obj[urlencode($key)] = $this->url_encode($value);
            }
        } else if (is_object($obj)) {
            foreach ($obj as $key => $value) {
                if (is_bool($value)) {continue;} //dont change boolean variable
                $key       = urlencode($key);
                $obj->$key = $this->url_encode($value);
            }
        } else {
            $obj = urlencode($obj);
        }
        return $obj;
    }

    /**
     * ajaxResponse : client response
     * @param  string $action    requested action URL
     * @param  string $versionNo version no.
     * @param  bool   $result    operation result
     * @param  string $message   message
     * @param  array  $params    additional paramaters
     * @return none
     */
    protected function ajaxResponse($action, $versionNo, $result, $message = '', $params = array()) {
        if (empty($action)) {
            $action = dirname(base_url()) . (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '');
        }
        $arrConfig = array(
            'action'    => $action,
            'versionNo' => $versionNo,
            'result'    => $result ? 1 : 0,
            'result'    => $result, // TODO : need to check with existing code
            'message'   => $message,
        );
        if (is_array($params) && count($params) > 0) {
            foreach ($params as $key => $value) {
                $arrConfig[$key] = $value;
            }
        }
        $this->jsonResponse($arrConfig);
    }


    /**
     * echoHeaderForJson : Echo header for ajax request.
     *
     * @access public
     * @return none
     */
    protected function echoHeaderForJson($type='application/json') {
        $encoding = strtolower($this->config->item('charset'));
        header("Content-type: $type; charset=$encoding", true, 200 );
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                       // Date in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          // always modified
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");// HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");                                             // HTTP/1.0
    }
    /**
     * echoHeaderForHtml : Echo header for ajax request.
     *
     * @access public
     * @return none
     */
    protected function echoHeaderForHtml() {
        return $this->echoHeaderForJson('text/html');
    }

    /**
     * echoHeaderForExcel : Echo header for excel response.
     *
     * @access public
     * @return none
     */
    protected function echoHeaderForExcel() {

        header('Content-type: "application/vnd.ms-excel"');
        header("Content-Disposition: attachment;Filename=data.xls" );
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                       // Date in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          // always modified
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");// HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        return;
    }
}