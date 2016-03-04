<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Model_user_wechat.php
 * DateTime : 2015年7月24日
 *
 * author : liuxiang<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */
class Weixinlogin extends Other_Controller {

	function __construct() {
		parent::__construct ();
		$this->load->library ( 'weixin/Weixin' );
		$this->load->model ( "auth/model_user_wechat", 'user_wechat', true );
	}

	/**
	 * wechat callback
	 */
	function index() {
		$code = $this->input->get ( 'code' );
		$ref = $this->input->get ( 'ref' );
		$callback_url = site_url ( 'auth/weixinlogin?ref=' . $ref );
		$openid = $this->weixin->find_openid ( $callback_url, $code );
		if (empty ( $openid )) {
			log_message ( 'info', 'Invalid openid' );
			return;
		}
		
		$retArr = $this->user_wechat->isOpenIdExists ( $openid );
		if ($retArr ['success']) {
			if ($retArr ['active'] == 0) {
				redirect ( site_url ( 'auth/weixinlogin/activetip/' . $openid ) );
			}
			$this->user_wechat->loginWechatUser ( $openid );
			if (empty ( $ref )) {
				redirect ( site_url () );
			} else {
				redirect ( $ref );
			}
		} else {
			redirect ( site_url ( 'auth/weixinlogin/login/' . $openid ) );
		}
	}

	/**
	 * 显示绑定提示页面
	 *
	 * @param string $openid
	 */
	public function verification($openid = null) {
		$data ['openid'] = $openid;
		$this->output ( "home_layout", array ('body' => 'auth/weixinverification' ), $data );
	}

	/**
	 * 登录，未审核验证
	 *
	 * @param string $openid
	 */
	public function activetip($openid = null) {
		$data ['openid'] = $openid;
		$this->output ( "home_layout", array ('body' => 'auth/activetip' ), $data );
	}

	/**
	 * 登录绑定
	 *
	 * @param string $openid
	 */
	function login($openid = '') {
		$data = array ();
		$userinfo = $this->user_wechat->getUserInfoByWechat ( $openid );
		if ($userinfo) {
			if ($this->user_wechat->initWeixinUser ( $openid, $userinfo )) {
				if ($this->user_wechat->wechatlogin ( $openid )) {
					redirect ( site_url () );
				} else {
					$this->session->set_flashdata ( 'error', "登录错误" );
				}
			} else {
				$this->session->set_flashdata ( 'error', "添加用户错误" );
			}
		} else {
			$this->session->set_flashdata ( 'error', "数据获取失败" );
		}
		$data ['message'] = (validation_errors ()) ? validation_errors () : $this->session->flashdata ( 'error' );
		redirect ( site_url ( 'home/welcome/index' ) );
	}

	/**
	 * 回调验证用户
	 *
	 * @param unknown $username
	 * @return boolean
	 */
	public function check_nickname($nickname, $mobile) {
		// 验证用户是否成功绑定
		$this->load->model ( "model_users", 'users', true );
		$where = array ('nickname' => $nickname,'mobile' => $mobile );
		$user_info = $this->users->find ( $where, '*' );
		if (! empty ( $user_info )) {
			$this->form_validation->set_message ( 'check_nickname', ' 当前用户和手机已经成功绑定，如有疑问请通过申诉处理。' );
			return FALSE;
		}
		return TRUE;
	}
}