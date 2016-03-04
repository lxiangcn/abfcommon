<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : error.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Error extends Web_Controller {

	function __construct() {
		parent::__construct ();
	}

	function index() {
		$data ['list'] = array ();
		$this->data ['title'] = "404 Page Not Found";
		$this->data ['list'] = $data ['list'];
		$this->output ( "home_layout", array ("body" => "error/index" ), $this->data );
	}
}