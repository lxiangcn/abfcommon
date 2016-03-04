<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Step2 extends CI_Controller {

    public $errors = array();
    public $writable_dirs = array(
        'application/config' => FALSE,
        'data/logs' => FALSE,
        'data/cache' => FALSE,
        'data/cache/cached' => FALSE,
        'data/cache/compiled' => FALSE,
        'uploads' => FALSE,
    );
    public $writable_subdirs = array(
        'uploads/images' => FALSE,
        'uploads/files' => FALSE,
        'uploads/.thumbs' => FALSE,
    );

    function index() {
        if (file_exists(FCPATH . "install.lock")) {
            die("系统已经安装，请勿重复安装，否则会清空所有数据。");
        }
        $data = array();
        clearstatcache();

        foreach ($this->writable_dirs as $path => $is_writable) {
            $this->writable_dirs[$path] = is_writable(CMS_ROOT . $path);
        }

        foreach ($this->writable_subdirs as $path => $is_writable) {
            if (!file_exists(CMS_ROOT . $path) || (file_exists(CMS_ROOT . $path) && is_writable(CMS_ROOT . $path))) {
                unset($this->writable_subdirs[$path]);
            }
        }

        if ($this->input->post()) {
            if ($this->validate()) {
                redirect('step3');
            }
        }

        $data['writable_dirs'] = array_merge($this->writable_dirs, $this->writable_subdirs);
        $data['errors'] = $this->errors;
        $data['content'] = $this->load->view('step_2', $data, TRUE);
        $this->load->view('global', $data);
    }

    private function validate() {
        if (!is_writable(CMS_ROOT . 'application/config/config.php')) {
            $this->errors[] = CMS_ROOT . 'application/config/config.php is not writable.';
        }

        if (!is_writable(CMS_ROOT . 'application/config/database.php')) {
            $this->errors[] = CMS_ROOT . 'application/config/database.php is not writable.';
        }

        $writable_dirs = array_merge($this->writable_dirs, $this->writable_subdirs);
        foreach ($writable_dirs as $path => $is_writable) {
            if (!$is_writable) {
                $this->errors[] = CMS_ROOT . $path . ' is not writable.';
            }
        }

        if (phpversion() < '5.1.6') {
            $this->errors[] = 'You need to use PHP 5.1.6 or greater.';
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
