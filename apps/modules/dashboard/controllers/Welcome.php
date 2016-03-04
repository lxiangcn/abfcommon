<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : Welcome.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Welcome extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data               = array();
        $data["page_title"] = "后台首页";
        $this->output("admin_layout", array('body' => 'welcome/index'), $data);
    }

    public function server_info() {
        $data                   = array();
        $data["page_title"]     = "PHP Info";
        $this->data['page_css'] = array(theme_url('assets/css/server_info.css'));
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();
        $data['pinfo'] = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
        $this->output("admin_layout", array('body' => 'welcome/server_info'), $data);
    }
}

?>
