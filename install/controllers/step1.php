<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Step1 extends CI_Controller {

    function index() {
    	$data = array();
        if (file_exists(FCPATH . "install.lock")) {
            header("Content-type: text/html; charset=utf-8"); 
            //die("您已经安装过应用程序，请勿重复安装，否则将会清空所有数据。");
            $this->load->view('lock', $data);
        }else {
			$this->form_validation->set_rules ( 'accept', '同意许可协议', 'trim|required' );
			$this->form_validation->set_message ( 'required', '你必须同意许可协议，才能安装企业建站系统。' );
			
			if ($this->form_validation->run ()) {
				redirect ( 'step2' );
			}
			
			$data ['content'] = $this->load->view ( 'step_1', $data, TRUE );
			$this->load->view ( 'global', $data );
        }
    }

}
