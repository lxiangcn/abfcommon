<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Admin_auth
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-21 16:39:04
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Admin_auth {

    /**
     * 用户登录状态session
     * @var string
     */
    private $local_user_info = "";
    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->load->config('auth/admin_auth', TRUE);
        $this->local_user_info = $this->config->item("local_user_info", "admin_auth");
        $this->load->model('auth/model_admin_auth', 'admin_auth_model', TRUE);

        //auto-login the user if they are remembered
        if (!$this->is_login() && get_cookie($this->config->item('identity_cookie_name', 'admin_auth')) && get_cookie($this->config->item('remember_cookie_name', 'admin_auth'))) {
            $this->member_auth_model->login_remembered_user();
        }

        log_message('debug', "Auth Library Class Initialized");
    }

    /**
     * __call
     */
    public function __call($method, $arguments) {
        if (!method_exists($this->admin_auth_model, $method)) {
            throw new Exception('Undefined method Admin_auth::' . $method . '() called');
        }
        return call_user_func_array(array(
            $this->admin_auth_model,
            $method,
        ), $arguments);
    }

    /**
     * __get
     *
     * @access public
     * @param
     *            $var
     * @return mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }

    /**
     * forgotten password feature
     *
     * @param unknown $identity
     * @return multitype:NULL |boolean
     */
    public function forgotten_password($identity) {
        if ($this->admin_auth_model->forgotten_password($identity)) {
            $user = $this->where($this->config->item('identity', 'admin_auth'), $identity)->where('active', 1)->users()->row();

            if ($user) {
                $data = array(
                    'identity'          => $user->{$this->config->item('identity', 'admin_auth')},
                    'lost_password_key' => $user->forgotten_password_code,
                );

                if (!$this->config->item('use_ci_email', 'admin_auth')) {
                    $this->set_message('forgot_password_successful');
                    return $data;
                } else {
                    $message = $this->load->view($this->config->item('email_templates', 'admin_auth') . $this->config->item('email_forgot_password', 'admin_auth'), $data, true);
                    $this->email->clear();
                    $this->email->from($this->config->item('admin_email', 'admin_auth'), $this->config->item('site_title', 'admin_auth'));
                    $this->email->to($user->email);
                    $this->email->subject($this->config->item('site_title', 'admin_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
                    $this->email->message($message);

                    if ($this->email->send()) {
                        $this->set_message('forgot_password_successful');
                        return TRUE;
                    } else {
                        $this->set_error('forgot_password_unsuccessful');
                        return FALSE;
                    }
                }
            } else {
                $this->set_error('forgot_password_unsuccessful');
                return FALSE;
            }
        } else {
            $this->set_error('forgot_password_unsuccessful');
            return FALSE;
        }
    }

    /**
     * forgotten_password_complete
     *
     * @param unknown $code
     * @return boolean|multitype:NULL unknown
     */
    public function forgotten_password_complete($code) {
        $identity = $this->config->item('identity', 'admin_auth');
        $profile  = $this->where('lost_password_key', $code)->users()->row();

        if (!$profile) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $new_password = $this->ion_auth_model->forgotten_password_complete($code, $profile->salt);

        if ($new_password) {
            $data = array(
                'identity'     => $profile->{$identity},
                'new_password' => $new_password,
            );
            if (!$this->config->item('use_ci_email', 'admin_auth')) {
                $this->set_message('password_change_successful');
                return $data;
            } else {
                $message = $this->load->view($this->config->item('email_templates', 'admin_auth') . $this->config->item('email_forgot_password_complete', 'admin_auth'), $data, true);

                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'admin_auth'), $this->config->item('site_title', 'admin_auth'));
                $this->email->to($profile->email);
                $this->email->subject($this->config->item('site_title', 'admin_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
                $this->email->message($message);

                if ($this->email->send()) {
                    $this->set_message('password_change_successful');
                    return TRUE;
                } else {
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
        }
        return FALSE;
    }

    /**
     * forgotten_password_check
     *
     * @param unknown $code
     * @return boolean|unknown
     */
    public function forgotten_password_check($code) {
        $profile = $this->where('lost_password_key', $code)->users()->row();

        if (!is_object($profile)) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        } else {
            if ($this->config->item('lost_password_expire', 'admin_auth') > 0) {
                $expiration = $this->config->item('lost_password_expire', 'admin_auth');
                if (time() - $profile->lost_password_expire > $expiration) {
                    $this->clear_forgotten_password_code($code);
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
            return $profile;
        }
    }

    /**
     * 判断是否管理员
     *
     * @access public
     * @param boolean $return
     * @return boolean
     */
    public function is_admin() {
        if ($this->session->userdata($this->local_user_info)) {
            $user_id = $this->session->userdata($this->local_user_info)['user_id'];
        }
        return $this->admin_auth_model->is_admin($user_id);
    }

    /**
     * 判断用户是否已经登录
     *
     * @access public
     * @return void
     */
    public function is_login() {
        if ($this->session->userdata($this->local_user_info)) {
            return (bool) $this->session->userdata($this->local_user_info)['identity'];
        } else {
            return false;
        }
    }

    /**
     * 获取登录用户的id
     *
     * @return integer
     *
     */
    public function get_user_id() {
        return $this->admin_auth_model->get_user_id();
    }

    /**
     * 权限检查
     *
     * @param unknown $set_security
     * @param unknown $user_id
     * @param unknown $class_name
     * @param unknown $method_name
     * @return bool
     */
    public function checkAccess($set_security, $class_name, $method_name) {
        $user_id = $this->admin_auth_model->get_user_id();
        // 设置内用户允许访问
        if ($this->is_admin()) {
            return TRUE;
        }
        if ($this->admin_auth_model->checkAccess($set_security, $user_id, $class_name, $method_name)) {
            return TRUE;
        } else {
            $this->set_error('action_not_allowed');
            return FALSE;
        }
    }
}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */