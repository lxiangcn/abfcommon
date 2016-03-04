<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : channel.php
 * DateTime : 2015年4月3日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Channel extends Admin_Controller {
	private $tblName = 'channel';

	function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_' . $this->tblName, $this->tblName, TRUE );
	}

	/**
	 * 频道列表
	 *
	 * @param type $page_no
	 */
	public function index($page_no = 1) {
		$per_page = 10;
		$page_no = ( int ) $page_no ? $page_no : 1;
		$offset = ($page_no - 1) * $per_page;
		$where = "1=1 ";
		$order_str = "id desc";
		$keyword = $this->input->get ( 'keyword' );
		if (! empty ( $keyword )) {
			$keyword = $keyword ? $keyword : '';
			$where .= " and title like '%{$keyword}%'";
		}
		$total_rows = $this->channel->find_count ( $where );
		
		$pagination_link = pagination_link ( "archive/channel/index/" . $page_no, intval ( $total_rows ), 4, $page_no );
		$data ['info_list'] = $this->channel->find_all ( $where, '*', $order_str, $pagination_link ['offset'], $pagination_link ['limit'] );
		$data ['pagestr'] = $pagination_link ['link'];
		$data ["page_no"] = $page_no;
		$data ['total_rows'] = $total_rows;
		
		$this->output ( "admin_layout", array ("body" => "channel/index" ), $data );
	}

	/**
	 * 添加频道
	 *
	 * @param number $page_no
	 */
	public function add($page_no = 1) {
		$page_no = intval ( $page_no );
		$data ['page_no'] = $page_no;
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'name', '名称', 'trim|required|min_length[2]|max_length[20]' );
			$this->form_validation->set_rules ( 'title', '标题', 'trim|required|min_length[1]|max_length[40]' );
			if ($this->form_validation->run () == true) {
				$items ['name'] = $this->input->post ( 'name' );
				$items ['title'] = $this->input->post ( 'title' );
				$items ['page_size'] = $this->input->post ( 'page_size' );
				$items ['sort_order'] = $this->input->post ( 'sort_order' );
				$items ['created'] = time ();
				if ($this->channel->insert ( $items )) {
					$this->success ( "添加频道成功！" );
					redirect ( 'archive/channel/index/' . $page_no );
				} else {
					$this->error ( "保存数据出错！" );
					redirect ( 'archive/channel/index/' . $page_no );
				}
			}
		}
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "channel/add" ), $data );
	}

	/**
	 * 编辑频道
	 *
	 * @param int $id
	 * @param int $page_no
	 */
	public function edit($id = 0, $page_no = 0) {
		$id = intval ( $id );
		$page_no = intval ( $page_no );
		$data ['id'] = $id;
		$data ['page_no'] = $page_no;
		
		// 验证编号
		if (! $id) {
			$this->error ( '错误的信息编号！' );
			redirect ( 'archive/channel/index/' . $page_no );
		}
		$obj = $this->channel->read ( $id );
		// 验证对象是否存在
		if (! $obj->id) {
			$this->error ( '不存在的信息编号！' );
			redirect ( 'archive/channel/index/' . $page_no );
		}
		// 设置默认表单信息
		$data ['page_no'] = $page_no;
		
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'name', '名称', 'trim|required|min_length[2]|max_length[20]|callback_check_name[' . $id . ']' );
			$this->form_validation->set_rules ( 'title', '标题', 'trim|required|min_length[1]|max_length[40]' );
			if ($this->form_validation->run () == true) {
				$items ['name'] = $this->input->post ( 'name' );
				$items ['title'] = $this->input->post ( 'title' );
				$items ['page_size'] = $this->input->post ( 'page_size' );
				$items ['sort_order'] = $this->input->post ( 'sort_order' );
				if ($this->channel->save ( $items, $id )) {
					$this->success ( "更新频道成功！" );
					redirect ( 'archive/channel/index/' . $page_no );
				} else {
					$this->error ( "保存数据出错！" );
				}
			}
		}
		$data ['info'] = $obj;
		$data ['csrf_name'] = $this->security->get_csrf_token_name ();
		$data ['csrf_token'] = $this->security->get_csrf_hash ();
		$this->output ( "admin_layout", array ("body" => "channel/edit" ), $data );
	}

	/**
	 * 回调验证频道名称
	 *
	 * @param unknown $username
	 * @return boolean
	 */
	public function check_name($name, $id) {
		// 验证用户数据是否存在验证表中
		$name_verify = $this->channel->get_by_name ( $name, $id );
		if (! empty ( $name_verify )) {
			$this->form_validation->set_message ( 'check_name', '已经存在相同%s的频道。' );
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 删除频道
	 *
	 * @param unknown $id
	 * @param number $page_no
	 */
	public function delete($id, $page_no = 1) {
		$id = ( int ) $id;
		if (! $id) {
			$this->error ( "查询错误" );
			redirect ( 'archive/channel/index/' . $page_no );
		}
		$obj = $this->channel->read ( $id );
		if ($obj->id) {
			if ($this->channel->remove ( $id )) {
				$this->success ( "删除成功" );
				redirect ( 'archive/channel/index/' . $page_no );
			} else {
				$this->error ( "查询错误" );
				redirect ( 'archive/channel/index/' . $page_no );
			}
		} else {
			$this->error ( "信息不存在" );
			redirect ( 'archive/channel/index/' . $page_no );
		}
	}
}