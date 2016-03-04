<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : photo.php
 * DateTime : 2015年4月10日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Photo extends Web_Controller {

	public function __construct() {
		parent::__construct ();
		// $this->load->model('model_news', 'news');
	}

	public function index() {
		$data ['list'] = array ();
		$this->data ['list'] = $data ['list'];
		$this->output ( "home_layout", array ("body" => "photo/index" ), $this->data );
	}

	public function lists() {
		$data ['list'] = array ();
		$this->data ['list'] = $data ['list'];
		$this->output ( "home_layout", array ("body" => "photo/lists" ), $this->data );
	}
}