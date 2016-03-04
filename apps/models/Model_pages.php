<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_pages.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_pages extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'pages' );
	}

	/**
	 * 获取全部page分类
	 */
	function get_all_page_cats() {
		$this->load_table ( 'page_cats' );
		$result = $this->find_all ( '', '', '`id` asc' );
		if (isset ( $result )) {
			return $result;
		} else {
			return 0;
		}
	}

	/**
	 *
	 * @param type $conditions
	 * @param type $fields
	 * @param type $order
	 * @param type $start
	 * @param type $limit
	 * @return string
	 */
	function get_all_page_rows($conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL) {
		$result = $this->find_all ( $conditions, $fields, $order, $start, $limit );
		if (isset ( $result )) {
			foreach ( $result as $key => $value ) {
				$result [$key] ['cat_name'] = $this->get_info_class ( $value ['page_cat_id'] );
			}
			$this->load_table ( 'pages' );
			return $result;
		} else {
			return;
		}
	}

	/**
	 * 获取单条信息
	 */
	function get_info_items($id) {
		$result = $this->get_all_page_rows ( "id={$id}", '*', NULL, 0, 1 );
		if (isset ( $result ) && ! empty ( $result )) {
			$result = $result [0];
			$result ['cat_name'] = $this->get_info_class ( $result ['page_cat_id'] );
			return $result;
		} else {
			return;
		}
	}

	/**
	 *
	 * 获取分类数据
	 *
	 * @param int $classid
	 */
	public function get_info_class($cat_id) {
		$this->load_table ( 'page_cats' );
		$result = $this->field ( "id = '$cat_id'", 'name' );
		$this->load_table ( 'pages' );
		if (isset ( $result )) {
			return $result;
		} else {
			return 0;
		}
	}
}

/* End of file model_pages.php */
/* Location: ./application/model/model_pages.php */ 