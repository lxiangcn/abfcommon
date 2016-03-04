<?php

defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );
define ( 'SECURITY', 2 );

/**
 * FileName : content.php
 * DateTime : 2014-5-17 21:35:29 build as UTF-8
 *
 * @author : alex
 * @E-mail : i@orzm.net
 * Description : OrzCMS 企业建站系统
 * Copyright (c) 2014 http://orzm.net All Rights Reserved.
 */
class Page extends Web_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_pages', 'pages' );
	}

	public function index() {
	
	}
	
	// 重新定义方法的调用规则 获取URI第二段值
	public function _remap($arg, $arg_array) {
		if ($arg == "index") {
			$this->_read ( $arg_array[0] );
		} else {
			$this->_read ( $arg_array[0] );
		}
	}

	/**
	 * 单页信息处理
	 */
	public function _read($id) {
		$this->data['id'] = $id;
		// 获取单页信息
		$data = $this->pages->get_info_items ( $id );
		if (isset ( $data ) && ! empty ( $data )) {
			// 更新点击数
			$add_num = 1;
			$this->db->set ( 'hits', "hits+$add_num", false );
			$this->db->where ( 'id', $id );
			$this->db->update ( $this->pages->_table );
			$this->data['data'] = $data;
			$this->data['pagename'] = $data->title;
			$this->output ( 'page', $this->data );
		} else {
			$this->show_message ( "你查询的页面不存在，请联系管理员！" );
		}
	}

	/**
	 * 单页信息处理
	 */
	public function _page($id) {
		$this->data['id'] = $id;
		// 获取单页信息
		$data = $this->pages->get_info_items ( $id );
		// 判断是否外链
		if ($infodata->url) {
			redirect ( $infodata->url );
		}
		// 更新点击数
		$add_num = 1;
		$this->db->set ( 'hits', "hits+$add_num", false );
		$this->db->where ( 'id', $id );
		$this->db->update ( $this->pages->_table );
		$this->data['data'] = $data;
		$this->data['page_keywords'] = $data->title;
		$this->output ( 'page', $this->data );
	}

	/**
	 * 获取分类下单页信息菜单
	 */
	function _leftmenu($cat_id, $num = 10, $order = '`id` asc') {
		if ($cat_id) {
			$where = " id in ($cat_id)";
		}
		$result = $this->pages->find_all ( $where, 'id,title,page_cat_id', $order, 0, $num );
		return $result;
	}

}

/* End of file content.php */
/* Location: ./application/controllers/content.php */ 