<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_channel.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_channel extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'channel' );
	}

	/**
	 * 检查频道数据
	 *
	 * @param unknown $name
	 * @param unknown $id
	 */
	public function get_by_name($name, $id) {
		$array = array ('name' => $name,'id <>' => $id );
		return $this->db->where ( $array )->get ( 'channel' )->row ();
	}
}