<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access.' );

/**
 * FileName : Page.php
 * DateTime : 2015年4月4日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Page extends Admin_Controller {
	private $maxLevelTag = "cat_level";

	function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_page_cats', 'page_cats', true );
		$this->load->model ( 'model_pages', 'pages', true );
	}

	/**
	 * 显示page数据
	 */
	public function index($page_no = 1, $parent_id = 0) {
		$per_page = 10;
		$page_no = ( int ) $page_no ? $page_no : 1;
		$offset = ($page_no - 1) * $per_page;
		$where = "1=1 ";
		$order_str = "id desc";
		
		$total_rows = $this->pages->find_count ( $where );
		$pagination_link = pagination_link ( "admin/page/index/", intval ( $total_rows ), 4, $page_no );
		$data ['info_list'] = $this->pages->find_all ( $where, '*', $order_str, $pagination_link ['offset'], $pagination_link ['limit'] );
		$data ['pagestr'] = $pagination_link ['link'];
		$data ["page_no"] = $page_no;
		$data ['total_rows'] = $pagination_link ['total_page'];
		
		$this->output ( "admin_layout", array ("body" => "page/index" ), $data );
	}

	/**
	 * 添加单页面
	 *
	 * @param int $page_no
	 */
	public function add($page_no = 0) {
		$page_no = intval ( $page_no );
		$data ['page_no'] = $page_no;
		
		// 分类列表：
		$cats = $this->page_cats->find_all ( "", 'id ,name,parent_id', 'id ASC', '' );
		$parents = array ();
		$maxLevel = $this->data ['config'] [$this->maxLevelTag];
		foreach ( $cats as $v ) {
			if (getCatLevel ( $v ['mapping'] ) < $maxLevel) {
				$parents [] = array ('id' => $v ['id'],'name' => $v ['name'],'parent_id' => $v ['parent_id'] );
			}
		}
		$no_option = array ('' => '顶级分类' );
		$parents = select_tree_prepare ( $parents, 0, '&nbsp;&nbsp;', 1, $no_option );
		// 设置默认表单信息
		$data ['parents'] = $parents;
		$data ['published'] = 1;
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'title', '标题', 'trim|required|htmlspecialchars|min_length[1]|max_length[40]' );
			$this->form_validation->set_rules ( 'keywords', '关键词', 'htmlspecialchars|min_length[1]|max_length[40]' );
			$this->form_validation->set_rules ( 'description', '描述信息', 'htmlspecialchars|min_length[1]|max_length[40]' );
			if ($this->form_validation->run () == true) {
				$title = $this->input->post ( 'title' );
				$keywords = $this->input->post ( 'keywords' );
				$description = $this->input->post ( 'description' );
				$page_cat_id = $this->input->post ( 'page_cat_id' );
				$published = $this->input->post ( 'published' ) ? 1 : 0;
				$content = $this->input->post ( 'content' );
				$img = $this->input->post ( 'image' );
				$items ['title'] = $title;
				$items ['keywords'] = $keywords;
				$items ['description'] = $description;
				$items ['page_cat_id'] = $page_cat_id;
				$items ['published'] = $published;
				$items ['content'] = $content;
				$items ['image'] = $img;
				$items ['created'] = time ();
				if ($this->pages->insert ( $items )) {
					$this->success ( "数据保存成功。" );
					redirect ( site_url ( 'dashboard/page/index' ) );
				} else {
					$this->error ( "数据保存失败。" );
				}
			}
		}
		
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "page/add" ), $data );
	}

	/**
	 * 单个编辑
	 *
	 * @param type $id
	 * @param type $page_no
	 */
	public function edit($id = 0, $page_no = 0) {
		$page_no = ( int ) $page_no;
		// 验证编号
		$id = ( int ) $id;
		if (! $id) {
			$this->error ( '错误的信息编号！' );
			redirect ( site_url ( 'dashboard/page/index' ) );
		}
		$obj = $this->pages->read ( $id );
		// 验证对象是否存在
		if (! $obj->id) {
			$this->error ( '不存在的信息编号！' );
			redirect ( site_url ( 'dashboard/page/index' ) );
		}
		
		$this->data ['page_css'] = array ();
		$page_no = intval ( $page_no );
		$data ['page_no'] = $page_no;
		
		$cats = $this->page_cats->find_all ( "", 'id ,name,parent_id', 'id ASC', '' );
		$parents = array ();
		$maxLevel = $this->data ['config'] [$this->maxLevelTag];
		foreach ( $cats as $v ) {
			if (getCatLevel ( $v ['mapping'] ) < $maxLevel) {
				$parents [] = array ('id' => $v ['id'],'name' => $v ['name'],'parent_id' => $v ['parent_id'] );
			}
		}
		$no_option = array ('' => '顶级分类' );
		$parents = select_tree_prepare ( $parents, 0, '&nbsp;&nbsp;', 1, $no_option );
		// 设置默认表单信息
		$data ['parents'] = $parents;
		$data ['published'] = 1;
		$data ['page_no'] = $page_no;
		$data ['parent_id'] = $obj->page_cat_id;
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'title', '标题', 'trim|required|htmlspecialchars|min_length[1]|max_length[40]' );
			$this->form_validation->set_rules ( 'keywords', '关键词', 'htmlspecialchars|min_length[1]|max_length[40]' );
			$this->form_validation->set_rules ( 'description', '描述信息', 'htmlspecialchars|min_length[1]|max_length[40]' );
			if ($this->form_validation->run () == true) {
				$title = $this->input->post ( 'title' );
				$keywords = $this->input->post ( 'keywords' );
				$description = $this->input->post ( 'description' );
				$page_cat_id = $this->input->post ( 'page_cat_id' );
				$published = $this->input->post ( 'published' ) ? 1 : 0;
				$content = $this->input->post ( 'content' );
				$image = $this->input->post ( 'image' );
				$items ['title'] = $title;
				$items ['keywords'] = $keywords;
				$items ['description'] = $description;
				$items ['page_cat_id'] = $page_cat_id;
				$items ['published'] = $published;
				$items ['content'] = $content;
				$items ['image'] = $image;
				if ($this->pages->save ( $items, $id )) {
					$this->success ( "数据保存成功。" );
					redirect ( site_url ( 'dashboard/page/index/' . $page_no ) );
				} else {
					$this->error ( "数据保存失败。" );
				}
			}
		}
		$data ['data'] = $obj;
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "page/edit" ), $data );
	}

	/**
	 * 单个删除
	 *
	 * @param int $id
	 */
	public function delete($id = NULL, $page_no = 1) {
		$id = ( int ) $id;
		if (! $id) {
			$this->error ( '查询错误' );
			redirect ( "dashboard/page/index" );
		}
		$obj = $this->pages->read ( $id );
		if ($obj->id) {
			if ($this->pages->remove ( $id )) {
				$this->success ( '删除数据成功！' );
				redirect ( "dashboard/page/index" );
			} else {
				$this->error ( '查询错误' );
				redirect ( "dashboard/page/index" );
			}
		} else {
			$this->error ( '信息不存在' );
			redirect ( "dashboard/page/index" );
		}
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
				$query = $this->pages->query ( 'UPDATE `' . $this->pre_fix . 'pages` SET published = 1 WHERE id in (' . implode ( ',', $data ) . ')' );
				if ($query) {
					exit ( json_encode ( array ('result' => 1,'msg' => "OK" ) ) );
				} else {
					exit ( json_encode ( array ('result' => 0,'msg' => "没有对象需要更新" ) ) );
				}
			}
		}
		exit ( json_encode ( array ('result' => 0,'msg' => "请求数据为空" ) ) );
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
				$query = $this->pages->query ( 'UPDATE `' . $this->pre_fix . 'pages` SET published = 0 WHERE id in (' . implode ( ',', $data ) . ')' );
				if ($query) {
					exit ( json_encode ( array ('result' => 1,'msg' => "OK" ) ) );
				} else {
					exit ( json_encode ( array ('result' => 0,'msg' => "没有对象需要更新" ) ) );
				}
			}
		}
		exit ( json_encode ( array ('result' => 0,'msg' => "请求数据为空" ) ) );
	}

	public function del_attach($id, $img) {
		$id = ( int ) $id;
		$img = ( int ) $img;
		if (! $id) {
			$this->show_error ( '错误的信息编号' );
		}
		if (! $img) {
			$this->show_error ( '错误的图像附件编号' );
		}
		$obj = ORM::factory ( 'page', $id );
		if ($obj->loaded) {
			$img = 'img' . $img;
			if ($obj->$img) {
				@unlink ( DOCROOT . 'upload' . DIRECTORY_SEPARATOR . 'page' . DIRECTORY_SEPARATOR . str_replace ( '/', DIRECTORY_SEPARATOR, $obj->$img ) );
				@unlink ( DOCROOT . 'upload' . DIRECTORY_SEPARATOR . 'page' . DIRECTORY_SEPARATOR . substr ( $obj->$img, 0, strrpos ( $obj->$img, '/' ) ) . DIRECTORY_SEPARATOR . 't_' . substr ( $obj->$img, strrpos ( $obj->$img, '/' ) + 1 ) );
				$obj->$img = null;
				$obj->save ();
			}
		} else {
			$this->show_error ( '图像不存在' );
		}
		url::redirect ( 'admin/page/edit/' . $id );
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
			exit ( json_encode ( array ('result' => 0,'msg' => "错误的编号" ) ) );
		}
		// published 必须为数字
		$published = ( int ) $published;
		
		// 验证对象是否存在
		$obj = $this->pages->read ( $id );
		if (! $obj ['id']) {
			exit ( json_encode ( array ('result' => 0,'msg' => "信息不存在" ) ) );
		}
		$obj ['published'] = $published;
		
		if ($this->pages->save ( $obj, $id )) {
			exit ( json_encode ( array ('result' => 1,'msg' => "OK" ) ) );
		} else {
			exit ( json_encode ( array ('result' => 0,'msg' => "没有信息需要更新" ) ) );
		}
	}
}
