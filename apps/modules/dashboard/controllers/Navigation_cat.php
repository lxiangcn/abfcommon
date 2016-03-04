<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access.' );

/**
 * FileName : Navigation_cat.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Navigation_cat extends Admin_Controller {
	private $tblName = 'navigation_cats';

	function __construct() {
		parent::__construct ();
		$this->load->library ( 'pagination' );
		$this->load->model ( 'model_' . $this->tblName, $this->tblName, TRUE );
		$this->load->model ( 'model_navigations', 'navigations', TRUE );
	}

	/**
	 * 显示导航数据
	 */
	public function index($page_no = 1) {
		$per_page = 10;
		$page_no = ( int ) $page_no ? $page_no : 1;
		$data ['page_no'] = $page_no;
		$offset = ($page_no - 1) * $per_page;
		// 关键字
		$keyword = $this->input->get ( 'keyword' );
		if ($keyword != 0) {
			$keyword = $keyword ? $keyword : '';
		}
		$data ['keyword'] = $keyword;
		$where = "1=1 ";
		$order_str = "id desc";
		if (! empty ( $keyword )) {
			$where .= " and title like '%$keyword%'";
		}
		$total_rows = $this->navigation_cats->find_count ( $where );
		$data ['info_list'] = $this->navigation_cats->find_all ( $where, '*', $order_str, $offset, $per_page );
		
		$this->output ( "admin_layout", array ("body" => "navigation_cat/index" ), $data );
	}

	/**
	 * 添加分类
	 *
	 * @param number $page_no
	 */
	public function add($page_no = 1) {
		$page_no = intval ( $page_no );
		$data ['page_no'] = $page_no ? $page_no : 1;
		
		// 设置默认表单信息
		$data ['published'] = 1;
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'cat_key', '分类标识', 'trim|required|min_length[2]|max_length[10]' );
			$this->form_validation->set_rules ( 'name', '分类名称', 'trim|required|min_length[2]|max_length[20]' );
			if ($this->form_validation->run ()) {
				$cat_key = $this->input->post ( 'cat_key' );
				$name = $this->input->post ( 'name' );
				$published = $this->input->post ( 'published' );
				$descript = $this->input->post ( 'descript' );
				$items ['cat_key'] = $cat_key;
				$items ['name'] = $name;
				$items ['published'] = $published;
				$items ['descript'] = $descript;
				$items ['created'] = time ();
				if ($this->navigation_cats->insert ( $items )) {
					show_message ( "数据保存成功！", site_url ( 'navigation_cat/index' ), 1 );
				} else {
					show_message ( "保存类别到数据库出错！", site_url ( 'navigation_cat/index' ) );
				}
			}
		}
		$this->output ( "admin_layout", array ("body" => "navigation_cat/add" ), $data );
	}

	/**
	 * 单个编辑
	 *
	 * @param int $id
	 */
	public function edit($id, $page_no = 1) {
		$data ['page_no'] = $page_no;
		// 验证编号
		$id = ( int ) $id;
		if (! $id) {
			show_message ( "错误的类别编号！", site_url ( 'navigation_cat/index' ) );
		}
		$obj = $this->navigation_cats->read ( $id );
		// 验证对象是否存在
		if (! $obj ['id']) {
			show_message ( "不存在的类别编号！", site_url ( 'navigation_cat/index' ) );
		}
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'cat_key', '分类标识', 'trim|required|min_length[2]|max_length[10]' );
			$this->form_validation->set_rules ( 'name', '分类名称', 'trim|required|min_length[2]|max_length[20]' );
			if ($this->form_validation->run ()) {
				$cat_key = $this->input->post ( 'cat_key' );
				$name = $this->input->post ( 'name' );
				$published = $this->input->post ( 'published' );
				$descript = $this->input->post ( 'descript' );
				$items ['cat_key'] = $cat_key;
				$items ['name'] = $name;
				$items ['published'] = $published;
				$items ['descript'] = $descript;
				$items ['created'] = time ();
				if ($this->navigation_cats->save ( $items, $id )) {
					show_message ( "修改成功！", site_url ( 'navigation_cat/index' ), 1 );
				} else {
					show_message ( "保存类别到数据库出错！", site_url ( 'navigation_cat/index' ) );
				}
			}
		}
		
		$data ["id"] = $id;
		$data ['data'] = $obj;
		$this->output ( "admin_layout", array ("body" => "navigation_cat/edit" ), $data );
	}

	/**
	 * 调整导航顺序
	 */
	public function items($id, $page_no = 1) {
		$data = array ();
		$this->data ['page_js'] = array (theme_url ( '/assets/js/jquery.ui.nestedSortable.js' ) );
		$where = " navigation_cat_id = " . $id;
		$where .= " and published = 1 ";
		$this->load->model ( 'model_navigations', 'navigations', TRUE );
		$nodes = $this->navigations->find_all ( $where );
		$nodes_tree = create_tree ( $nodes );
		$nodes_tree_arr = NULL;
		$data ['info_list'] = show_navigation ( $nodes_tree );
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "navigation_cat/items" ), $data );
	}

	/**
	 * 保存导航数据
	 */
	function save_items() {
		if (! is_ajax ()) {
			$rd = array ('status' => 0,'msg' => "请求数据为空！" );
			exit ( json_encode ( $rd ) );
		}
		
		$list = $_POST ['list'];
		$data = array ();
		foreach ( $list as $id => $parent_id ) {
			$data ['parent_id'] = ($parent_id == 'root') ? "0" : $parent_id;
			$this->navigations->save ( $data, $id );
		}
		$rd = array ('status' => 1,'msg' => "保存数据成功！" );
		exit ( json_encode ( $rd ) );
	}

	/**
	 * 批量为发布状态
	 */
	public function batch_publish() {
		if ($this->input->post () && is_array ( $_POST ['ids'] ) && ! empty ( $_POST ['ids'] )) {
			$data = array ();
			foreach ( $_POST ['ids'] as $v ) {
				if ($v = ( int ) $v) {
					$data [] = $v;
				}
			}
			if (! empty ( $v )) {
				$query = 'UPDATE `' . $this->pre_fix . $this->tblName . '` SET published = 1 WHERE id in (' . implode ( ',', $data ) . ')';
				if ($this->navigation_cats->query ( $query )) {
					$rd = array ('status' => 1,'msg' => "OK！",'jmp_url' => site_url ( 'navigation_cat/index' ) );
					exit ( json_encode ( $rd ) );
				} else {
					$rd = array ('status' => 0,'msg' => "没有对象需要更新" );
					exit ( json_encode ( $rd ) );
				}
			}
		}
		$rd = array ('status' => 0,'msg' => "请求数据为空" );
		exit ( json_encode ( $rd ) );
	}

	/**
	 * 批量为未发布状态
	 */
	public function batch_unpublish() {
		if ($this->input->post () && is_array ( $_POST ['ids'] ) && ! empty ( $_POST ['ids'] )) {
			$data = array ();
			foreach ( $_POST ['ids'] as $v ) {
				if ($v = ( int ) $v) {
					$data [] = $v;
				}
			}
			if (! empty ( $v )) {
				$query = 'UPDATE `' . $this->pre_fix . $this->tblName . '` SET published = 0 WHERE id in (' . implode ( ',', $data ) . ')';
				if ($this->navigation_cats->query ( $query )) {
					$rd = array ('status' => 1,'msg' => "OK！",'jmp_url' => site_url ( 'navigation_cat/index' ) );
					exit ( json_encode ( $rd ) );
				} else {
					$rd = array ('status' => 0,'msg' => "没有对象需要更新" );
					exit ( json_encode ( $rd ) );
				}
			}
		}
		$rd = array ('status' => 0,'msg' => "请求数据为空" );
		exit ( json_encode ( $rd ) );
	}

	/**
	 * 单个删除
	 *
	 * @param int $id
	 */
	public function del($id) {
		$id = ( int ) $id;
		if (! $id) {
			$rd = array ('status' => 0,'msg' => "查询错误" );
			exit ( json_encode ( $rd ) );
		}
		$obj = $this->navigation_cats->read ( $id );
		if ($obj ['id']) {
			if ($this->navigation_cats->remove ( $id )) {
				$rd = array ('status' => 1,'msg' => "删除成功",'jmp_url' => site_url ( 'navigation_cat/index' ) );
				exit ( json_encode ( $rd ) );
			}
		} else {
			$rd = array ('status' => 0,'msg' => "分类不存在" );
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
			exit ( json_encode ( array ('result' => 0,'msg' => "查询错误" ) ) );
		}
		// published 必须为数字
		$published = ( int ) $published;
		// 验证对象是否存在
		$obj = $this->navigation_cats->read ( $id );
		if (! $obj ['id']) {
			exit ( json_encode ( array ('result' => 0,'msg' => "对象不存在" ) ) );
		}
		$data ['published'] = $published;
		
		if ($this->navigation_cats->save ( $data, $id )) {
			exit ( json_encode ( array ('result' => 1,'msg' => "OK" ) ) );
		} else {
			exit ( json_encode ( array ('result' => 0,'msg' => "没有对象需要更新" ) ) );
		}
	}
}
