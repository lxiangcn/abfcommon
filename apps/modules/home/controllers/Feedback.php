<?php

defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );
define ( 'SECURITY', 2 );

class Feedback extends Web_Controller {

	function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_feedback', "feedback", TRUE );
	}

	/**
	 * 在线反馈页面
	 */
	function index() {
		$item['hasError'] = $item['error'] = '';
		$item['nick'] = $this->data['sess_userinfo']['username'];
		if ($this->input->post ()) {
			$this->form_validation->set_rules ( 'title', '标题', 'trim|required' );
			$this->form_validation->set_rules ( 'nick', '联系人', 'trim|required|min_length[1]|max_length[10]' );
			$this->form_validation->set_rules ( 'contact', '联系方式', 'trim|required' );
			$this->form_validation->set_rules ( 'content', '反馈内容', 'trim|required' );
			if ($this->form_validation->run () == false) {
				$item['hasError'] = 1;
			} else {
				$data['title'] = $this->input->post ( 'title', true );
				$data['nick'] = $this->input->post ( 'nick', true );
				$data['contact'] = $this->input->post ( 'contact', true );
				$data['content'] = $this->input->post ( 'content', true );
				$data['created'] = time ();
				if ($this->feedback->save ( $data )) {
					$this->data['error'] = "提交成功。";
				} else {
					$this->data['error'] = "提交失败，请联系系统管理员。";
				}
			}
		}
		$this->data['navpath'] = "在线反馈";
		$this->output ( 'feedback', $this->data );
	}

}

?>
