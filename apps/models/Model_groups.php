<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_user_groups.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_groups extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'groups' );
	}

	/**
	 * 检查用户组
	 *
	 * @param unknown $group_name
	 */
	function get_by_name($group_name) {
		return $this->db->where ( 'group_name', $group_name )->get ( 'groups' )->row ();
	}

	/**
	 * 编辑用户组 $group_name检查
	 *
	 * @param string $group_name
	 * @param int $id
	 * @return unknown
	 */
	function get_by_id_name($group_name, $id) {
		$data = $this->db->where ( array ('group_name' => $group_name,'id <>' => $id ) )->get ( 'groups' )->row ();
		return $data;
	}
}

/* End of file user_groups.php */
/* Location: ./application/model/user_groups.php */ 