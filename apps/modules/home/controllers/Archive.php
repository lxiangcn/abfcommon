<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * FileName : archive.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Archive extends Web_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('model_archive', 'archive');
	}

	function index() {
		$data['list'] = array();
		$this->data['list'] = $data['list'];
		$this->output("home_layout", array("body" => 'archive/index'), $this->data);
	}

	function lists() {
		$data['list'] = array();
		$this->data['list'] = $data['list'];
		$this->output("home_layout", array("body" => $this->uri->segment(1) . '/' . $this->uri->segment(2)), $this->data);
	}

	function show($id = 0) {
		$data['list'] = array();
		$this->data['id'] = $id;
		$this->data['list'] = $data['list'];
		$this->output("home_layout", array("body" => $this->uri->segment(1) . '/' . $this->uri->segment(2)), $this->data);
	}
}