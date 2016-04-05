<?php
defined('BASEPATH') or die('No direct script access allowed');
class Groups extends Admin_Controller {

	function __Construct() {
		parent::__construct();
		$this->load->model('Model_groups','groups',true);
	}

	public function index($page_no=1) {
		$page_no=intval($page_no);
		if(!$page_no) {
			$page_no=1;
		}
		$where="1=1 ";
		$order_str="id asc";
		$total_rows=$this->groups->find_count($where);
		$pagination_link=pagination_link("auth/groups/index/",intval($total_rows),4,$page_no);
		$data['info_list']=$this->groups->find_all($where,'*',$order_str,$pagination_link['offset'],$pagination_link['limit']);
		$data['pagestr']=$pagination_link['link'];
		$data["page_no"]=$page_no;
		$data['total_rows']=$pagination_link['total_page'];
		$data['csrf_name']=$this->security->get_csrf_token_name();
		$data['csrf_token']=$this->security->get_csrf_hash();
		$this->output("admin_layout",array("body" =>"group/index"),$data);
	}

	public function add() {
		if($this->input->post()) {
			$this->form_validation->set_rules('group_name','名称','trim|required');
			$this->form_validation->set_rules('description','描述','trim|required');
			if($this->form_validation->run()===true) {
				$items['group_name']=$this->input->post('group_name',true);
				$items['description']=$this->input->post('description',true);
				$items['published']=$this->input->post('published',true);
				if($this->groups->insert($items)) {
					$this->success("保存数据成功。");
					redirect(site_url('auth/groups/index'));
				} else {
					$this->error("保存数据出错。");
				}
			}
		}
		$data['csrf_name']=$this->security->get_csrf_token_name();
		$data['csrf_token']=$this->security->get_csrf_hash();
		$this->output("admin_layout",array("body" =>"group/add"),$data);
	}

	public function edit($id) {
		$id=intval($id);
		$obj=$this->groups->read($id);
		if(!$id || !$obj) {
			$this->error('参数错误');
		}
		if($this->input->post()) {
			$this->form_validation->set_rules('group_name','名称','trim|required');
			$this->form_validation->set_rules('description','描述','trim|required');
			if($this->form_validation->run()===true) {
				$items['group_name']=$this->input->post('group_name',true);
				$items['description']=$this->input->post('description',true);
				$items['published']=$this->input->post('published',true);
				if($this->groups->save($items,$id)) {
					$this->success("保存数据成功。");
					redirect(site_url('auth/groups/index'));
				} else {
					$this->error("保存数据出错。");
				}
			}
		}
		$data['id']=$id;
		$data['data']=$obj;
		$data['csrf_name']=$this->security->get_csrf_token_name();
		$data['csrf_token']=$this->security->get_csrf_hash();
		$this->output("admin_layout",array("body" =>"group/edit"),$data);
	}
}
