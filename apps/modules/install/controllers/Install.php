<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
 *    abfcommon
 *
 * @package Install
 * @copyright Copyright (c) 2010 - 2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-04-20 10:45:47
 * @author Alex Liu<lxiangcn@gmail.com>
 */

class Install extends MX_Controller {
    public $errors        = array();
    public $writable_dirs = array(
        '../apps/config' => FALSE,
        'data/backup'    => FALSE,
        'data/logs'      => FALSE,
        'data/cache'     => FALSE,
        'data/uploads'   => FALSE,
    );
    public $writable_subdirs = array(
        'uploads/images'  => FALSE,
        'uploads/files'   => FALSE,
        'uploads/.thumbs' => FALSE,
    );

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function index() {
        redirect('install/step1');
    }

    function step1() {
        $data = array();
        if (file_exists(FCPATH . "install.lock")) {
            header("Content-type: text/html; charset=utf-8");
            die("您已经安装过应用程序，请勿重复安装，否则将会清空所有数据。");
            //$this->load->view('lock', $data);
        } else {
            $this->form_validation->set_rules('accept', '同意许可协议', 'trim|required');
            $this->form_validation->set_message('required', '你必须同意许可协议，才能安装企业建站系统。');
            if ($this->form_validation->run()) {
                redirect('install/step2');
            }

            $data['content'] = $this->load->view('step_1', $data, TRUE);
            $this->load->view('global', $data);
        }
    }

    function step2() {
        if (file_exists(FCPATH . "install.lock")) {
            die("系统已经安装，请勿重复安装，否则会清空所有数据。");
        }
        $data = array();
        clearstatcache();

        foreach ($this->writable_dirs as $path => $is_writable) {
            $this->writable_dirs[$path] = is_writable($path);
        }

        foreach ($this->writable_subdirs as $path => $is_writable) {
            if (!file_exists($path) || (file_exists($path) && is_writable($path))) {
                unset($this->writable_subdirs[$path]);
            }
        }

        if ($this->input->post()) {
            if ($this->validate()) {
                redirect('install/step3');
            }
        }

        $data['writable_dirs'] = array_merge($this->writable_dirs, $this->writable_subdirs);
        $data['errors']        = $this->errors;
        $data['content']       = $this->load->view('step_2', $data, TRUE);
        $this->load->view('global', $data);
    }

    function step3() {
        if (file_exists(FCPATH . "install.lock")) {
            die("系统已经安装，请勿重复安装，否则会清空所有数据。");
        }
        $data = array();

        $template = file_get_contents(MODULES . 'install/assets/config/database.php');

        $this->form_validation->set_rules('site_name', 'Site Name', 'trim|required');
        $this->form_validation->set_rules('server', 'Server', 'trim|required');
        $this->form_validation->set_rules('hostname', 'Database Host', 'trim|required');
        $this->form_validation->set_rules('username', 'Database Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Database Password', 'trim');
        $this->form_validation->set_rules('database', 'Database Name', 'trim|required');
        $this->form_validation->set_rules('port', 'Database Port', 'trim|required');
        $this->form_validation->set_rules('prefix', 'Database Prefix', 'trim');
        $this->form_validation->set_rules('admin', 'Administrator Username', 'trim|required');
        $this->form_validation->set_rules('email', 'Administrator Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('admin_password', 'Administrator Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_admin_password', 'Confirm Administrator Password', 'trim|required|matches[admin_password]');

        if ($this->form_validation->run()) {
            $config['db']['hostname'] = $this->input->post('hostname');
            $config['db']['username'] = $this->input->post('username');
            $config['db']['password'] = $this->input->post('password');
            $config['db']['database'] = $this->input->post('database');
            $config['db']['prefix']   = $this->input->post('prefix');
            $config['db']['port']     = $this->input->post('port');
            $config['server']         = $this->input->post('server');
            $this->load->library('installer', $config);

            try {
                $this->installer->test_db_connection();
                //$this->installer->write_ci_config();
                $this->installer->write_db_config();
                $this->installer->db_connect();
                $this->installer->import_schema();
                //$this->installer->insert_administrator($this->input->post('admin'), $this->input->post('admin_password'), $this->input->post('email'));
                $this->installer->update_site_name($this->input->post('site_name'));
                $this->installer->update_notification_email($this->input->post('email'));
                $this->installer->write_lock_file();
                $this->installer->db_close();
                redirect('install/step4');
            } catch (Exception $e) {
                $this->installer->db_close();
                $this->errors[] = $e->getMessage();
            }
        }

        $data['rewrite_support'] = $this->test_mod_rewrite();
        $data['errors']          = $this->errors;
        $data['content']         = $this->load->view('step_3', $data, TRUE);
        $this->load->view('global', $data);
    }

    function step4() {
        $data = array();

        $data['content'] = $this->load->view('step_4', $data, TRUE);
        $this->load->view('global', $data);
    }

    private function test_mod_rewrite() {
        if (function_exists('apache_get_modules') && is_array(apache_get_modules()) && in_array('mod_rewrite', apache_get_modules())) {
            return true;
        } else if (getenv('HTTP_MOD_REWRITE') !== false) {
            return (getenv('HTTP_MOD_REWRITE') == 'On') ? true : false;
        } else {
            return false;
        }
    }

    private function validate() {
        if (!is_writable('../apps/config/config.php')) {
            $this->errors[] = '../apps/config/config.php is not writable.';
        }

        if (!is_writable('../apps/config/database.php')) {
            $this->errors[] = '../apps/config/database.php is not writable.';
        }

        $writable_dirs = array_merge($this->writable_dirs, $this->writable_subdirs);
        foreach ($writable_dirs as $path => $is_writable) {
            if (!$is_writable) {
                $this->errors[] = $path . ' is not writable.';
            }
        }

        if (phpversion() < '5.3') {
            $this->errors[] = 'You need to use PHP 5.3 or greater.';
        }

        if (!ini_get('file_uploads')) {
            $this->errors[] = 'File uploads need to be enabled in your PHP configuration.';
        }

        if (!extension_loaded('mysql')) {
            $this->errors[] = 'The PHP MySQL extension is required.';
        }

        if (!extension_loaded('gd')) {
            $this->errors[] = 'The PHP GD extension is required.';
        }

        if (!extension_loaded('curl')) {
            $this->errors[] = 'The PHP cURL extension is required.';
        }

        if (empty($this->errors)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}