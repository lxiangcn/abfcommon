<?php

defined('BASEPATH') or die('No direct script access allowed');
/**
 *    abfcommon
 *
 * @package Login
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-20 21:42:58
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Login extends Other_Controller {
    /**
     * Referer
     *
     * @access public
     * @var string
     */
    public $referrer;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->_check_referrer();
        $this->lang->load('auth');
    }

    /**
     * 检查referrer
     *
     * @access private
     * @return void
     */
    private function _check_referrer() {
        $ref            = $this->input->get('ref', TRUE);
        $this->referrer = !empty($ref) ? $ref : site_url("dashboard/welcome/index");
    }

    /**
     * admin login
     * @return
     */
    public function adminlogin() {
        $data['page_title'] = "Administrator Login";
        $ref                = $this->input->get("ref");
        $data['ref']        = $ref;
        $this->referrer     = empty($ref) ? site_url("dashboard/welcome/index") : $ref;

        if ($this->admin_auth->is_login() && $this->admin_auth->is_admin()) {
            redirect($this->referrer, 'refresh');
        }

        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        if ($this->input->post()) {
            // validate form input
            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() == true) {
                $remember        = (bool) $this->input->post('remember');
                $captcha         = $this->input->post('captcha');
                $session_captcha = $this->session->userdata('verify');
                if ($captcha != null && $captcha == $session_captcha) {
                    if ($this->admin_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                        $this->session->set_flashdata('message', $this->admin_auth->messages());
                        redirect($this->referrer);
                    } else {
                        $this->session->set_flashdata('message', $this->admin_auth->errors());
                    }
                } else {
                    $this->session->set_flashdata('message', $this->lang->line('captcha_can_not_be_empty_and_error'));
                }
            }
        }
        $data['message']  = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['identity'] = array('name' => 'identity', 'id' => 'identity', 'type' => 'text', 'value' => $this->form_validation->set_value('identity'));
        $data['password'] = array('name' => 'password', 'id' => 'password', 'type' => 'password');
        $data['captcha']  = array('name' => 'captcha', 'id' => 'captcha', 'type' => 'text');
        $this->output("admin_login", array('body' => 'login/index'), $data);
    }

    /**
     * admin user logout
     * @return
     */
    public function adminlogout() {
        $this->admin_auth->logout();
        redirect(site_url("admin"), 'refresh');
    }
}