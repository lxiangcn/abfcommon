<?php

defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Login.php
 * DateTime : 2015年4月23日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

class Login extends Other_Controller {
	/**
	 * Referer
	 *
	 * @access public
	 * @var string
	 */
	public $referrer;

	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
		$this->_check_referrer ();
		$this->lang->load ( 'auth' );
		$this->data['page_title'] = '用户登录';
		if ($this->user_auth->is_admin ()) {
			redirect ( site_url ( "dashboard/welcome/index" ) );
		}
	}

	/**
	 * 检查referrer
	 *
	 * @access private
	 * @return void
	 */
	private function _check_referrer() {
		$ref = $this->input->get ( 'ref', TRUE );
		$this->referrer = ! empty ( $ref ) ? $ref : site_url ( "dashboard/welcome/index" );
	}

	/**
	 * 退出登录
	 */
	public function logout() {
		$this->user_auth->logout ();
		redirect ( site_url (), 'refresh' );
	}

	/**
	 * 用户登录
	 */
	public function index() {
		$data['title'] = "Login";
		$data['ref'] = $this->referrer;
		
		$data['csrf_name'] = $this->security->get_csrf_token_name ();
		$data['csrf_token'] = $this->security->get_csrf_hash ();
		// validate form input
		$this->form_validation->set_rules ( 'identity', 'Identity', 'required' );
		$this->form_validation->set_rules ( 'password', 'Password', 'required' );
		if ($this->form_validation->run () == true) {
			$remember = ( bool ) $this->input->post ( 'remember' );
			$captcha = $this->input->post ( 'captcha' );
			if ($this->user_auth->login ( $this->input->post ( 'identity' ), $this->input->post ( 'password' ), $captcha, $remember )) {
				$this->session->set_flashdata ( 'success', $this->user_auth->messages () );
				redirect ( $this->referrer );
			} else {
				$this->session->set_flashdata ( 'error', $this->user_auth->errors () );
				redirect ( 'auth/login' );
			}
		} else {
			$data['message'] = (validation_errors ()) ? validation_errors () : $this->session->flashdata ( 'error' );
			$data['identity'] = array(
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value ( 'identity' ) 
			);
			$data['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'type' => 'password' 
			);
			$data['captcha'] = array(
				'name' => 'captcha',
				'id' => 'captcha',
				'type' => 'text' 
			);
			$this->output ( "admin_login", array(
				'body' => 'login/index' 
			), $data );
		}
	}

}