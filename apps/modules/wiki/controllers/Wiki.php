<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * FileName : wiki.php
 * DateTime : 2015年6月25日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Wiki extends Other_Controller {

	/**
	 * wiki
	 *
	 * @param string $type
	 */
	public function index($type = 'base') {
		$data = array ();
		$this->output ( "help_layout", array ("body" => "help/help_" . $type ), $data );
	}
}