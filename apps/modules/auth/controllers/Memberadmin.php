<?php
class Memberadmin extends  Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('auth/Model_member','member',true);
	}

	public function index($page_no=1) {
		$page_no=intval($page_no);
		if(!$page_no) {
			$page_no=1;
		}
		$data_where='1=1';
		$order_str="id desc";
		$total_rows=$this->member->find_count($data_where);
		$pagination_link=pagination_link("auth/manage/index/",intval($total_rows),4,$page_no);
		$data['info_list']=$this->member->find_all($data_where,'*',$order_str,$pagination_link['offset'],$pagination_link['limit']);
		$data['pagestr']=$pagination_link['link'];
		$data["page_no"]=$page_no;
		$data['total_rows']=$pagination_link['total_page'];
		$this->output("admin_layout",array("body" =>"member/index"),$data);
	}

}
