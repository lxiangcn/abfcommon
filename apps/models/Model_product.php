<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_product.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_product extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'products' );
	}

	/**
	 * 获得总行数
	 *
	 * @return type
	 */
	function getAllrows() {
		return $this->db->count_all ( $this->table );
	}

	/**
	 *
	 * 获取全部产品内容
	 *
	 * @param int $id
	 */
	public function get_all_product() {
		$query = $this->db->get ( $this->table );
		$result = $query->result_array (); // 结果集
		if (isset ( $result )) {
			foreach ( $result as $key => $value ) {
				$result [$key] ['classname'] = $this->get_class ( $value ['classid'] );
				$result [$key] ['classname'] = $result [$key] ['classname'] ['classname'];
			}
			return $result;
		} else {
			return 0;
		}
	}

	/**
	 *
	 * 获取全部信息内容列表
	 *
	 * @return Array 返回信息分类的数组 无结果返回0
	 */
	function get_all_rows($conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL) {
		$result = $this->find_all ( $conditions, $fields, $order, $start, $limit );
		if (isset ( $result )) {
			foreach ( $result as $key => $value ) {
				$class = get_class_row ( $value->product_cat_id, 'id', 'product_cats' );
				$result [$key] ['cat_name'] = $class ['name'];
				// 生成图片地址
				// $result[$key]['pic'] = setting('upload_path') . $value['showpic'];
			}
			return $result; // 返回信息分类数组
		} else {
			return;
		}
		// 重新设置数据表
	}

	/**
	 *
	 * 获取单条信息内容
	 *
	 * @param int $id
	 */
	public function get_info_items($id) {
		$result = $this->read ( $id );
		if (isset ( $result )) {
			$result->cat_name = get_class_row ( $result->product_cat_id, "id", "product_cats" );
			return $result;
		} else {
			return 0;
		}
	}

	/**
	 *
	 * 获取分类名称
	 *
	 * @param int $classid
	 */
	public function get_class($classid) {
		$this->load_table ( 'tclass' );
		$result = $this->field ( "classid = '$classid'", 'classname' );
		$this->load_table ( 'products' );
		if (isset ( $result )) {
			return $result;
		} else {
			return 0;
		}
	}
}

/* End of file model_products.php */
/* Location: ./application/model/model_products.php */ 