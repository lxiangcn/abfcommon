<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access.' );

/**
 * FileName : Navigation.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Navigation extends Admin_Controller {
	private $tblName = 'navigations';

	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_' . $this->tblName, $this->tblName, TRUE );
		$this->load->model ( 'model_navigation_cats', "navigation_cats", TRUE );
	}

	/**
	 * 列表
	 *
	 * @param number $page_no
	 * @param number $id
	 */
	public function index($page_no = 1, $id = 0) {
		$where = "1=1 ";
		$order_str = "sort_order asc";
		
		$total_rows = $this->navigations->find_count ( $where );
		$pagination_link = pagination_link ( "dashboard/navigation/index/", intval ( $total_rows ), 4, $page_no );
		$data ['info_list'] = $this->navigations->find_all ( $where, '*', $order_str, $pagination_link ['offset'], $pagination_link ['limit'] );
		$data ['pagestr'] = $pagination_link ['link'];
		$data ["page_no"] = $page_no;
		$data ['total_rows'] = $pagination_link ['total_page'];
		
		$cat_where = "published = 1";
		$cats = $this->navigation_cats->find_all ( $cat_where, '*', "id desc" );
		$parents = array ();
		foreach ( $cats as $v ) {
			$parents [] = array ('id' => $v->id,'name' => $v->name,'parent_id' => 0 );
		}
		$no_option = array ('' => '顶级分类' );
		$parents = select_tree_prepare ( $parents, 0, '&nbsp;&nbsp;', 1, $no_option );
		// 设置默认表单信息
		$data ['parents'] = $parents;
		$data ['cats'] = $cats;
		
		$this->output ( "admin_layout", array ("body" => "navigation/index" ), $data );
	}

	/**
	 * 添加导航条目
	 */
	public function add($page_no = 1) {
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'name', '分类标识', 'trim|required|min_length[2]|max_length[20]' );
			$this->form_validation->set_rules ( 'link', '链接地址', 'trim|min_length[0]|max_length[255]' );
			if ($this->form_validation->run ()) {
				$name = $this->input->post ( 'name' );
				$published = $this->input->post ( 'published' );
				$navigation_cat_id = $this->input->post ( 'navigation_cat_id' );
				$sort_order = $this->input->post ( 'sort_order' );
				$link = $this->input->post ( 'link' );
				$link_type = $this->input->post ( 'link_type' );
				
				$post_link = $link;
				// 对link包装加入http://
				if ($link_type == 0 and strpos ( $post_link, 'http://' ) === false) {
					if ($post_link != "/") {
						$post_link = 'http://' . $post_link;
					}
				}
				
				$items ['name'] = $name;
				$items ['published'] = $published;
				$items ['navigation_cat_id'] = $navigation_cat_id;
				$items ['sort_order'] = $sort_order;
				$items ['link'] = $post_link;
				if ($this->navigations->insert ( $items )) {
					$this->success ( "数据保存成功！" );
					redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
				} else {
					$this->error ( "保存类别到数据库出错！" );
					redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
				}
			} else {
				$rd ['msg'] = form_error ( 'name' ) . form_error ( 'link' );
				$this->error ( $rd ['msg'] );
				redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
			}
		}
		// 分类列表：
		$cats = $this->navigation_cats->find_all ( "", 'id ,name', 'id ASC', '' );
		$parents ['-'] = "顶级分类";
		foreach ( $cats as $v ) {
			$parents [$v->id] = $v->name;
		}
		$data ['cats'] = $parents;
		$data ['page_no'] = $page_no;
		$data ['sort_order'] = 100;
		$data ['navigation_cat_id'] = 0;
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "navigation/add" ), $data );
	}

	/**
	 * 编辑导航条目
	 *
	 * @param unknown $id
	 * @param unknown $page_no
	 */
	public function edit($id, $page_no) {
		// 验证编号
		$id = ( int ) $id;
		if (! $id) {
			$this->error ( "错误的类别编号！" );
			redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
		}
		$obj = $this->navigations->read ( $id );
		// 验证对象是否存在
		if (! $obj->id) {
			$this->error ( "不存在的类别编号！" );
			redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
		}
		$data ["id"] = $id;
		$data ["page_no"] = $page_no;
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'name', '分类标识', 'trim|required|min_length[2]|max_length[20]' );
			$this->form_validation->set_rules ( 'link', '链接地址', 'trim|min_length[0]|max_length[255]' );
			if ($this->form_validation->run ()) {
				$name = $this->input->post ( 'name' );
				$published = $this->input->post ( 'published' );
				$navigation_cat_id = $this->input->post ( 'navigation_cat_id' );
				$sort_order = $this->input->post ( 'sort_order' );
				$link = $this->input->post ( 'link' );
				$link_type = $this->input->post ( 'link_type' );
				
				$post_link = $link;
				// 对link包装加入http://
				if ($link_type == 0 and strpos ( $post_link, 'http://' ) === false) {
					if ($post_link != "/") {
						$post_link = 'http://' . $post_link;
					}
				}
				
				$items ['name'] = $name;
				$items ['published'] = $published;
				$items ['navigation_cat_id'] = $navigation_cat_id;
				$items ['sort_order'] = $sort_order;
				$items ['link'] = $post_link;
				if ($this->navigations->save ( $items, $id )) {
					$this->success ( "数据保存成功！" );
					redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
				} else {
					$this->success ( "保存类别到数据库出错！" );
					redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
				}
			} else {
				$rd ['msg'] = form_error ( 'name' ) . form_error ( 'link' );
				$this->success ( $rd ['msg'] );
				redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
			}
		}
		
		// 分类列表：
		$cats = $this->navigation_cats->find_all ( "", 'id ,name', 'id ASC', '' );
		$parents ['-'] = "顶级分类";
		foreach ( $cats as $v ) {
			$parents [$v->id] = $v->name;
		}
		$data ['cats'] = $parents;
		$data ['navigation_cat_id'] = $obj->navigation_cat_id;
		$data ["data"] = $obj;
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "navigation/edit" ), $data );
	}

	/**
	 * 单个删除
	 *
	 * @param int $id
	 */
	public function delete($id, $page_no = 1) {
		$id = ( int ) $id;
		if (! $id) {
			$this->error ( "查询错误！" );
			redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
		}
		$obj = $this->navigations->read ( $id );
		if ($obj->id) {
			if ($this->navigations->remove ( $id )) {
				$this->success ( "删除成功！" );
				redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
			} else {
				$this->error ( "查询错误！" );
				redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
			}
		} else {
			$this->error ( "信息不存在！" );
			redirect ( site_url ( 'dashboard/navigation/index/' . $page_no ) );
		}
	}
}
