<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
 *  abfcommon 全局控制
 *
 * @package MY_Controller
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-31 11:04:16
 * @author Alex Liu<lxiangcn@gmail.com>
 */

/* load the Base_Controller class */
//require APPPATH . "core/Base_Controller.php";

abstract class MY_Controller extends Base_Controller {

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
        $this->load->model('auth/model_admin', 'admin', TRUE);
        $this->load->library('auth/admin_auth');
        $this->load->library('auth/member_auth');
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

        //security
        $this->data['csrf_name']  = $this->security->get_csrf_token_name();
        $this->data['csrf_token'] = $this->security->get_csrf_hash();
        set_cookie($this->data['csrf_name'], $this->data['csrf_token'], 86500);

        // Profiler
        $this->output->enable_profiler(FALSE);
        log_message('debug', "MY_Controller Controller Class Initialized");
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
        if (!$this->member_auth->is_login()) {
            // redirect ( 'auth/weixinlogin?ref=' . urlencode (
            // $this->uri->uri_string () ) );
        }
        // 设置权限为 允许任何人可以访问
        $this->member_auth->checkAccess(2, $this->class, $this->method);
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
        if (!$this->admin_auth->is_login()) {
            if (!$this->admin_auth->is_admin() && $this->admin_auth->is_login()) {
                $this->session->set_flashdata('error', __("user_permission_is_not_correct"));
            }
            redirect('auth/admin/login?ref=' . urlencode($this->uri->uri_string()));
        }
        // 设置不开启双模
        $this->template->is_mobile_switch = FALSE;

        // 设置权限为 拥有权限的人可以访问
        if (!$this->admin_auth->checkAccess(1, $this->class, $this->method)) {
            $this->session->set_flashdata('error', $this->admin_auth->errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        // 获取登录用户数据
        $userdata                 = $this->admin_auth->get_userdata();
        $this->data['user_id']    = $userdata['user_id'];
        $this->data['username']   = $userdata['username'];
        $this->data['email']      = $userdata['email'];
        $this->data['last_login'] = $userdata['old_last_login'];
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
        $this->member_auth->checkAccess(2, $this->class, $this->method);
        $this->data['globaltips'] = FALSE;
        // 设置不开启双模
        $this->template->is_mobile_switch = FALSE;
    }
}

/* End of file MY_Controller.php */
/* Location: ./apps/core/MY_Controller.php */
