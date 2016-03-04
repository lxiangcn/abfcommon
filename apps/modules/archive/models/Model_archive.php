<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : model_archive.php
 * DateTime : 2015年5月6日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Model_archive extends MY_Model {

	function __construct() {
		parent::__construct ();
		$this->load_table ( 'archive' );
	}

	/**
	 * 获取全部新闻分类
	 */
	function get_all_news_class() {
		$this->load_table ( 'news_class' );
		$result = $this->find_all ( '', '', '`order` asc' );
		
		if (isset ( $result )) {
			return $result; // 返回信息分类数组
		} else {
			return 0;
		}
	}

	function get_all_article_rows($conditions = NULL, $fields = '*', $order = NULL, $offset = 0, $per_page = NULL) {
		$result = $this->find_all ( $conditions, $fields, $order, $offset, $per_page );
		if (isset ( $result )) {
			foreach ( $result as $key => $value ) {
				$result [$key] ['cat_name'] = $this->get_info_class ( $value ['category_id'] );
			}
			$this->load_table ( 'archive' );
			return $result; // 返回信息分类数组
		} else {
			return;
		}
		
		// 重新设置数据表
	}

	/**
	 * 获取单条信息
	 */
	function get_info_items($id) {
		$result = $this->get_all_news_rows ( "`id`=$id", '*', NULL, 0, 1 );
		if (isset ( $result )) {
			$result = $result [0];
			$result ['cat_name'] = $this->get_info_class ( $result ['category_id'] );
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
		$this->load_table ( 'category' );
		$result = $this->field ( "id = '$cat_id'", 'name' );
		$this->load_table ( 'archive' );
		if (isset ( $result ) && ! empty ( $result )) {
			return $result;
		} else {
			return "";
		}
	}
}
