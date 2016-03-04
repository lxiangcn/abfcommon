<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_user_access.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_user_access extends MY_Model {
	/**
	 *
	 * @var unknown
	 */
	private $tableName = 'user_access';

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'user_access' );
	}

	/*
	 * 获取某用户组权限集
	 */
	public function getGroupAccesses($group_id) {
		$where ['group_id'] = $group_id;
		
		$result = $this->db->get_where ( $this->tableName, $where );
		if ($result->num_rows () > 0) {
			return $result->result ();
		}
		return FALSE;
	}

	/*
	 * 获取某节点权限集
	 */
	public function getNodeAccesses($node_id) {
		$where ['node_id'] = $node_id;
		
		$result = $this->db->get_where ( $this->tableName, $where );
		if ($result->num_rows () > 0) {
			return $result->result ();
		}
		return FALSE;
	}

	/**
	 * 新增权限
	 *
	 * @param unknown $group_id
	 * @param unknown $node_id
	 * @param unknown $parent_node_id
	 * @param unknown $level
	 * @return unknown|boolean
	 */
	public function addAccess($group_id, $node_id) {
		$set = array ('group_id' => $group_id,'menu_id' => $node_id );
		
		$this->db->insert ( $this->tableName, $set );
		$id = $this->db->insert_id ();
		if ($id > 0) {
			return $id;
		}
		return FALSE;
	}

	/*
	 * 删除权限
	 */
	public function delAccess($group_id, $menu_id = NULL) {
		// 若menu_id未指定,则删除指定用户组全部访问权限
		if ($menu_id !== NULL) {
			$where ['menu_id'] = $menu_id;
		}
		
		$where ['group_id'] = $group_id;
		
		$this->db->delete ( $this->tableName, $where );
		if ($this->db->affected_rows () > 0) {
			return TRUE;
		}
		return FALSE;
	}
}

/* End of file model_user_access.php */
/* Location: ./application/model/model_user_access.php */