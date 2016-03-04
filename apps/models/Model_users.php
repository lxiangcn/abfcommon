<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_users.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_users extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'users' );
	}

	/**
	 * 检查用户是否通过验证
	 *
	 * @access public
	 * @param string - $name 用户名
	 * @return mixed - FALSE/uid
	 */
	public function validate_user($username) {
		$data = array ();
		$this->db->select ( 'users.*,user_groups.name as group_name' );
		$this->db->from ( "users" );
		$this->db->join ( 'user_groups', "user_groups.id = users.gid" );
		$this->db->where ( 'users.username', $username )->limit ( 1 );
		$query = $this->db->get ();
		if ($query->num_rows () == 1) {
			$data = $query->row_array ();
		}
		$query->free_result ();
		return $data;
	}

	/**
	 * 检查用户密码
	 *
	 * @param unknown $login_password
	 * @param unknown $password
	 * @param unknown $salt
	 */
	public function validate_password($login_password, $password, $salt) {
		$data = FALSE;
		$data = (hash_validate ( $login_password, $password, $salt )) ? TRUE : FALSE;
		return $data;
	}

	/**
	 * 修改用户信息
	 *
	 * @access public
	 * @param int - $data 用户信息
	 * @param int - $uid 用户ID
	 * @return boolean - success/failure
	 */
	public function update_user($data, $uid) {
		$this->db->where ( 'id', intval ( $uid ) );
		$this->db->update ( "users", $data );
		
		return ($this->db->affected_rows () > 0) ? TRUE : FALSE;
	}

	/**
	 * 查询用户数据
	 *
	 * @param string $id
	 */
	function get_user_by_id($id = '') {
		$data = array ();
		$this->db->select ( 'users.*,user_groups.name as group_name' );
		$this->db->from ( "users" );
		$this->db->join ( 'user_groups', "user_groups.id = users.gid" );
		$this->db->where ( 'users.id', $id )->limit ( 1 );
		$query = $this->db->get ();
		if ($query->num_rows () == 1) {
			$data = $query->row_array ();
		}
		$query->free_result ();
		return $data;
	}

	/**
	 * 检查用户email
	 *
	 * @param unknown $email
	 */
	function get_user_by_email($email) {
		return $this->db->where ( 'email', $email )->get ( 'users' )->row ();
	}

	/**
	 * 编辑用户email检查
	 *
	 * @param unknown $email
	 * @param unknown $id
	 * @return unknown
	 */
	function get_user_by_id_email($email, $id) {
		$data = $this->db->where ( array ('email' => $email,'id <>' => $id ) )->get ( 'users' )->row ();
		return $data;
	}
}