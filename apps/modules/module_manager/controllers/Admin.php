<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : admin.php
 * DateTime : 2015年6月26日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('module_manager/module');
    }

    /*
     * We use index only for redirect
     */
    function index() {
        $data['modules'] = $this->module->get_module_list();
        $this->output("admin_layout", array("body" => "module_manager/index"), $data);
    }

}