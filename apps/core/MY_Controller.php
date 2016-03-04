<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : MY_Controller.php
 * DateTime : UTF-8,21:47:53,2014-5-17
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description : 全局控制
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

/* load the MX_Controller class */
require APPPATH . "third_party/MX/Controller.php";

abstract class MY_Controller extends MX_Controller {

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

    /**
     * 全局数据
     *
     * @var array
     */
    var $data = array();

    /**
     * 模板对象
     *
     * @var array
     */
    var $partials = array();

    /**
     * class
     *
     * @var string
     */
    var $class = NULL;

    /**
     * method
     *
     * @var string
     */
    var $method = NULL;

    public function __construct() {
        parent::__construct();
        $this->pre_fix = $this->db->dbprefix;
        // date_default_timezone_set ( 'Asia/Shanghai' );
        // $this->config->set_item ( 'sess_driver', 'database' );
        // $this->config->set_item ( 'sess_cookie_name', $this->pre_fix .
        // 'session' );
        // $this->config->set_item ( 'sess_expiration', 7200 );
        // $this->config->set_item ( 'sess_save_path', NULL );
        // $this->config->set_item ( 'sess_match_ip', FALSE );
        // $this->config->set_item ( 'sess_time_to_update', 300 );
        // $this->config->set_item ( 'sess_regenerate_destroy', FALSE );
        // 加载用户模型
        $this->load->model('model_users', 'users', TRUE);
        // 获取全局配置
        $this->load->model('model_configs', 'configs', TRUE);
        $this->data['config'] = array();
        $this->data['config'] = $this->configs->get_configs();
        $this->load->database();
        // 判断当前语言
        $this->data['lang'] = $this->config->item('language');
        // 模板参数
        $this->partials = array();
        // 全局js引用
        $this->data['global_js']  = array();
        $this->data['global_css'] = array();
        // 页面js引用
        $this->data['page_js']  = array();
        $this->data['page_css'] = array();
        // Check if to force ssl on controller
        if (in_uri($this->config->item('ssl_pages'))) {
            force_ssl();
        } else {
            remove_ssl();
        }
        // form_validation
        // https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        // checkAccess
        $str              = strtolower(substr($this->router->directory, 0, -1));
        $sub_len          = strrpos($str, '/');
        $module_directory = substr($str, $sub_len + 1);

        $this->class  = ($module_directory === "controllers") ? $this->router->module . '/' . $this->router->class : $this->router->module . '/' . $module_directory . '/' . $this->router->class;
        $this->method = $this->router->method;

        // class name for admin view html
        $this->data['css_class'] = $this->class;

        $this->data['sys_env'] = ENVIRONMENT;
        // Profiler
        $this->output->enable_profiler(FALSE);
        log_message('debug', "MY_Controller Controller Class Initialized");
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
     * 输出页面
     *
     * @param type $layout
     * @param type $partials
     * @param type $data
     */
    public function output($layout, $partials, $data = array()) {
        if (is_array($data) && !empty($data)) {
            $this->data = array_merge($this->data, $data);
        }
        $this->partials = array_merge($this->partials, $partials);
        $this->template->load($layout, $this->partials, $this->data);
    }
}

/**
 * 前台控制
 *
 * @author Alex Liu<lxiangcn@gmail.com>
 */
abstract class Web_Controller extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->before();
    }

    public function before() {
        if (!$this->user_auth->is_login()) {
            // redirect ( 'auth/weixinlogin?ref=' . urlencode (
            // $this->uri->uri_string () ) );
        }
        // 设置权限为 允许任何人可以访问
        $this->load->library('auth/user_auth');
        $this->user_auth->checkAccess(2, $this->class, $this->method);
        // 设置不开启双模
        $this->template->is_mobile_switch = FALSE;
        // 判断伪静态
        if ($this->data['config']['rewritetype']) {
            $this->config->set_item('index_page', ''); // 配置默认入口文件名为空
            $this->config->set_item('url_suffix', $this->data['config']['rewritetype']); // 判断设置伪静态后缀名
        } else {
            $this->config->set_item('index_page', 'index.php'); // 配置默认入口文件名为index.php
            $this->config->set_item('url_suffix', '');
        }
        $this->data['site_url'] = site_url();
    }
}

/**
 * 后台控制器扩展，如果您不了解其作用，请不要随意更改此处
 *
 * @author Alex Liu<lxiangcn@gmail.com>
 */
abstract class Admin_Controller extends MY_Controller {

    function __construct() {
        parent::__construct();
        /**
         * 检查登陆
         */
        if (!$this->user_auth->is_login()) {
            if (!$this->user_auth->is_admin() && $this->user_auth->is_login()) {
                $this->session->set_flashdata('error', __("user_permission_is_not_correct"));
            }
            redirect('auth/admin?ref=' . urlencode($this->uri->uri_string()));
        }
        // 设置不开启双模
        $this->template->is_mobile_switch = FALSE;

        // 设置权限为 拥有权限的人可以访问
        $this->load->library('auth/user_auth');
        if (!$this->user_auth->checkAccess(1, $this->class, $this->method)) {
            $this->session->set_flashdata('error', $this->user_auth->errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        // 获取登录用户数据
        $this->data['user_id']    = $this->session->userdata('user_id');
        $this->data['username']   = $this->session->userdata('username');
        $this->data['email']      = $this->session->userdata('email');
        $this->data['last_login'] = $this->session->userdata('old_last_login');
    }
}

/**
 * 默认控制
 *
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Other_Controller extends MY_Controller {

    function __construct() {
        parent::__construct();
        // 设置权限为 允许任何人可以访问
        $this->load->library('auth/user_auth');
        $this->user_auth->checkAccess(2, $this->class, $this->method);
        $this->data['globaltips'] = FALSE;
        // 设置不开启双模
        $this->template->is_mobile_switch = FALSE;
    }
}

/* End of file MY_Controller.php */
/* Location: ./apps/core/MY_Controller.php */
