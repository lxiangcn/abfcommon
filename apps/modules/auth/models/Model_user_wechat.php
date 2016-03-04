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
class Model_user_wechat extends MY_Model {
	private $wechat_password;

	function __construct() {
		parent::__construct ();
		$this->wechat_password = "wechat_password";
		$this->load_table ( 'users' );
		$this->load->library ( 'auth/User_auth', 'user_auth', true );
	}

	/**
	 * isOpenIdExists : detect openid is exists in db, return { success:true, userid:xxx }
	 *
	 * @param unknown $openid
	 * @param string $active_only
	 * @return boolean|multitype:boolean string
	 */
	public function isOpenIdExists($openid, $active_only = true) {
		log_message ( 'debug', 'check openid[' . $openid . '] exists' );
		$this->load->model ( 'model_users', 'users', true );
		$user_info = $this->users->find ( array (
			'openid' => $openid 
		) );
		if (! empty ( $user_info ) && count ( $user_info ) > 0) {
			return array (
				'success' => true,
				'uid' => $user_info->id,
				'user_name' => $user_info->username,
				'nickname' => $user_info->nickname,
				'active' => $user_info->active 
			);
		} else {
			log_message ( 'info', 'Invalid open id has been provided : ' . $openid );
			return array (
				'success' => false,
				'uid' => '',
				'user_name' => '',
				'nickname' => '',
				'active' => '' 
			);
		}
	}

	/**
	 * wechat login
	 *
	 * @param string $openId
	 * @param string $authCode
	 */
	public function loginWechatUser($openId = '', $authCode = '') {
		if ($authCode === 'undefined') {
			$authCode = '';
		}
		if (empty ( $openId )) {
			return false;
		} else {
			$rt = $this->doAutoLogin ( $openId, $authCode );
			return $rt;
		}
	}

	/**
	 * auto login
	 *
	 * @param string $openid
	 * @param string $authCode
	 * @return boolean
	 */
	public function doAutoLogin($openid, $authCode = '') {
		$retArr = $this->isOpenIdExists ( $openid );
		if (! $retArr ['success']) {
			log_message ( 'debug', 'current openid[' . $openid . '] is not exists.' );
			$rt = $this->user_auth->is_login ();
			if ($rt && $this->user_auth->get_expand () !== $openid) {
				$this->user_auth->logout ();
				$rt = false;
			}
			return $rt;
		} else {
			log_message ( 'debug', 'current openid[' . $openid . '] is exists.' );
		}
		
		if ($this->user_auth->is_login ()) {
			if ($retArr ["username"] === $this->user_auth->get_username ()) {
				log_message ( 'debug', 'current user is logged in.' );
				$oid = $this->session->userdata ( 'openId' );
				log_message ( 'debug', 'autoLogin successed $openid:' . $oid );
				return true;
			} else {
				log_message ( 'debug', 'the logged user is not for current openid' );
			}
		}
		$user_id = isset ( $retArr ['uid'] ) ? $retArr ['uid'] : '';
		$this->load->model ( 'model_users', 'users', true );
		$arrRst = $this->users->find ( array (
			'id' => $user_id 
		) );
		if (! empty ( $arrRst ) && count ( $arrRst ) > 0) {
			$uname = $arrRst->username;
		} else {
			log_message ( 'error', 'doAutoLogin, failed to get username by openid : ' . $user_id );
			return false;
		}
		
		if ($this->wechatlogin ( $uname )) {
			$oid = $this->session->userdata ( 'openId' );
			log_message ( 'debug', 'autoLogin successed $openid:' . $oid );
		} else {
			log_message ( 'error', 'failed to do auto login' );
			return false;
		}
		
		return $this->user_auth->is_login ();
	}

	/**
	 * 微信登录
	 *
	 * @param string $username
	 * @return boolean
	 */
	public function wechatlogin($username) {
		$this->user_auth->login ( $username, $this->wechat_password );
		return $this->user_auth->is_login ();
	}

	/**
	 * get wechat user info
	 *
	 * @param string $openid
	 */
	public function getUserInfoByWechat($openid = null) {
		$wxuser = array ();
		if (empty ( $openid )) {
			return false;
		}
		
		$userinfo = $this->weixin->getUserInfo ( $openid );
		if ($userinfo) {
			$wxuser = array (
				'open_id' => $openid,
				'nickname' => empty ( $userinfo ['nickname'] ) ? "" : $userinfo ['nickname'],
				'sex' => intval ( $userinfo ['sex'] ) ? "male" : "female",
				'location' => $userinfo ['province'] . '-' . $userinfo ['city'],
				'avatar' => $userinfo ['headimgurl'] 
			);
		} else {
			return false;
		}
		return $wxuser;
	}

	/**
	 * 自动初始化微信用户
	 *
	 * @param string $openid
	 * @param array $data
	 * @return boolean
	 */
	public function initWeixinUser($openid, $data) {
		$username = $openid;
		$password = $this->wechat_password;
		$email = time () . '@weixin.com';
		$info = array (
			'avatar_remote' => isset ( $data ['avatar'] ) ? "" : $data ['avatar'],
			'openid' => $openid,
			'created' => time (),
			'nickname' => $data ['nickname'],
			'mobile' => $data ['mobile'],
			'gender' => $data ['sex']
		);
		
		if ($this->user_auth->register ( $username, $password, $email, $info )) {
			return true;
		}
		return false;
	}
}
