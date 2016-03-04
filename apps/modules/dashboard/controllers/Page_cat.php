<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access.' );

/**
 * FileName : Page_cat.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Page_cat extends Admin_Controller {
	private $tblName = 'page_cats';
	private $maxLevelTag = 'cat_level';

	function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_page_cats', 'page_cats', true );
	}

	/**
	 * 显示类别
	 *
	 * @param int $page_no
	 * @param int $parent_id
	 */
	public function index($page_no = 1, $parent_id = 0) {
		$per_page = 10;
		$page_no = ( int ) $page_no ? $page_no : 1;
		$offset = ($page_no - 1) * $per_page;
		$where = "1=1 ";
		$where .= " and parent_id=" . $parent_id;
		$order_str = "id desc";
		
		$total_rows = $this->page_cats->find_count ( $where );
		$pagination_link = pagination_link ( "dashboard/page_cat/index/", intval ( $total_rows ), 4, $page_no );
		$data ['info_list'] = $this->page_cats->find_all ( $where, '*', $order_str, $pagination_link ['offset'], $pagination_link ['limit'] );
		$data ['pagestr'] = $pagination_link ['link'];
		$data ["page_no"] = $page_no;
		$data ['total_rows'] = $pagination_link ['total_page'];
		$this->output ( "admin_layout", array ("body" => "page_cat/index" ), $data );
	}

	/**
	 * 添加分类
	 *
	 * @param int $page_no
	 * @param int $parent_id
	 */
	public function add($page_no = 1, $parent_id = 0) {
		$parent_id = intval ( $parent_id );
		$page_no = intval ( $page_no );
		$data ['page_no'] = $page_no;
		$where = "published = 1";
		$categories = $this->page_cats->find_all ( $where );
		$this->load->library ( 'tree' );
		$this->tree->set_array ( $categories );
		$parents = $this->tree->get_tree ();
		$no_option = array ('0' => '├─顶级分类' );
		$parents = $no_option + $this->tree->get_tree ();
		// 设置默认表单信息
		$data ['parents'] = $parents;
		$data ['published'] = 1;
		$data ['parent_id'] = 0;
		$data ['sort_order'] = 100;
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'name', '分类名称', 'trim|required' );
			$this->form_validation->set_rules ( 'sort_order', '排序', 'integer' );
			if ($this->form_validation->run () == true) {
				$name = $this->input->post ( 'name', true );
				$parent_id = $this->input->post ( 'parent_id' );
				$published = $this->input->post ( 'published' );
				$sort_order = $this->input->post ( 'sort_order' );
				$items ['name'] = $name;
				$items ['parent_id'] = $parent_id;
				$items ['published'] = $published;
				$items ['sort_order'] = $sort_order;
				$items ['created'] = time ();
				if ($this->page_cats->insert ( $items )) {
					$this->success ( "数据保存成功。" );
					redirect ( "dashboard/page_cat/index/" . $page_no );
				} else {
					$this->error ( "数据保存失败。" );
					redirect ( "dashboard/page_cat/index/" . $page_no );
				}
			}
		}
		
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "page_cat/add" ), $data );
	}

	/**
	 * 编辑分类
	 *
	 * @param int $page_no
	 * @param int $id
	 */
	public function edit($id = 0, $page_no = 1) {
		$page_no = intval ( $page_no );
		$data ['page_no'] = $page_no;
		$data ['id'] = $id;
		// 验证编号
		$id = ( int ) $id;
		if (! $id) {
			$this->error ( "错误的类别编号！" );
			redirect ( site_url ( "dashboard/page_cat/index/" . $page_no ) );
		}
		$obj = $this->page_cats->read ( $id );
		// 验证对象是否存在
		if (! $obj ['id']) {
			$this->error ( "不存在的类别编号！" );
			redirect ( site_url ( "dashboard/page_cat/index/" . $page_no ) );
		}
		$where = "published = 1";
		$categories = $this->page_cats->find_all ( $where );
		$this->load->library ( 'tree' );
		$this->tree->set_array ( $categories );
		$parents = $this->tree->get_tree ();
		$no_option = array ('0' => '├─顶级分类' );
		$parents = $no_option + $this->tree->get_tree ();
		// 设置默认表单信息
		$data ['parents'] = $parents;
		$data ['published'] = 1;
		$data ['parent_id'] = 0;
		$data ['sort_order'] = 100;
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'name', '分类名称', 'trim|required' );
			$this->form_validation->set_rules ( 'sort_order', '排序', 'integer' );
			if ($this->form_validation->run () == true) {
				$name = $this->input->post ( 'name' );
				$parent_id = $this->input->post ( 'parent_id' );
				$published = $this->input->post ( 'published' );
				$sort_order = $this->input->post ( 'sort_order' );
				$items ['name'] = $name;
				$items ['parent_id'] = $parent_id;
				$items ['published'] = $published;
				$items ['sort_order'] = $sort_order;
				$items ['created'] = time ();
				if ($this->page_cats->save ( $items, $id )) {
					$this->success ( "更新成功" );
					redirect ( site_url ( "dashboard/page_cat/index/" . $page_no ) );
				} else {
					$this->error ( "保存类别到数据库出错！" );
					redirect ( site_url ( "dashboard/page_cat/index/" . $page_no ) );
				}
			}
		}
		// 使用默认数据
		$data ['data'] = $obj;
		$data ['parents'] = $parents;
		$data ['parent_id'] = $obj ['parent_id'];
		
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "page_cat/edit" ), $data );
	}

	/**
	 * 单个删除分类
	 *
	 * @param int $id
	 */
	public function del($id) {
		$id = ( int ) $id;
		if (! $id) {
			$rd = array ('status' => 0,'msg' => "查询错误！" );
			exit ( json_encode ( $rd ) );
		}
		if ($this->page_cats->remove ( $id )) {
			$rd = array ('status' => 1,'msg' => "更新成功！",'jmp_url' => site_url ( 'page_cat/index' ) );
			exit ( json_encode ( $rd ) );
		} else {
			$rd = array ('status' => 0,'msg' => "记录不存在！" );
			exit ( json_encode ( $rd ) );
		}
	}

	/**
	 * 更改发布状态
	 *
	 * @param int $id
	 * @param int $published
	 */
	public function set_publish($id, $published) {
		// 验证编号
		$id = ( int ) $id;
		if (! $id) {
			$rd = array ('status' => 0,'msg' => "错误的编号！" );
			exit ( json_encode ( $rd ) );
		}
		
		// published 必须为数字
		$published = ( int ) $published;
		
		// 验证对象是否存在
		$obj = $this->page_cats->read ( $id );
		if (! $obj ['id']) {
			$rd = array ('status' => 0,'msg' => "信息不存在！" );
			exit ( json_encode ( $rd ) );
		}
		
		$data ['published'] = $published;
		
		if ($this->page_cats->save ( $data, $id )) {
			$this->changeByCatId ( $id, $published );
			$rd = array ('status' => 1,'msg' => "更新成功！",'jmp_url' => site_url ( 'page_cat/index' ) );
			exit ( json_encode ( $rd ) );
		} else {
			$rd = array ('status' => 0,'msg' => "没有信息需要更新！" );
			exit ( json_encode ( $rd ) );
		}
	}

	private function changeByCatId($id, $published) {
		$id = intval ( $id );
		if ($id === 0) return false;
		if ($this->page_cats->update ( array ('published' => $published ), array ('parent_id' => $id ) )) return true;
		else
			return false;
	}
}
