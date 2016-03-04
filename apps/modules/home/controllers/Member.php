<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Member extends Web_Controller {

	function __construct() {
		parent::__construct ();
	}

	function index() {
		$data['user_info'] = $this->user_mdl->get_user_info ();
		$data['tpl_member_slide'] = $this->load->view ( 'common/member_slide', $this->data, true );
		$this->output ( "home_layout", array(
			'body' => 'member/index' 
		), $data );
	}

	function edit() {
		$data['tpl_member_slide'] = $this->load->view ( 'common/member_slide', $this->data, true );
		if ($this->input->post ()) {
			$data['email'] = $this->input->post ( 'email' );
			$data['username'] = $this->input->post ( 'username' );
			$data['gender'] = $this->input->post ( 'gender' );
			if ($this->username_check ()) {
				$this->show_message ( "用户名已存在", 'member/edit' );
			} else {
				unset ( $data['tpl_member_slide'] );
				$this->user_mdl->update_users ( $data );
				$this->show_message ( "账号更新成功", 'member/index' );
			}
		} else {
			$data['user_info'] = $this->user_mdl->get_user_info ();
			$this->output ( "home_layout", array(
				'body' => 'member/edit' 
			), $data );
		}
	}

	function password() {
		$data['tpl_member_slide'] = $this->load->view ( 'common/member_slide', $this->data, true );
		if ($this->input->post ()) {
			$user_info['old_pass'] = $this->input->post ( 'old_pass' );
			$user_info['new_pass'] = $this->input->post ( 'new_pass' );
			$user_info['new_pass_confirm'] = $this->input->post ( 'new_pass_confirm' );
			$this->form_validation->set_rules ( 'old_pass', "旧密码", 'required' );
			$this->form_validation->set_rules ( 'new_pass', "新密码", 'required|min_length[6]|max_length[16]|matches[new_pass_confirm]' );
			$this->form_validation->set_rules ( 'new_pass_confirm', "确认新密码", 'required|min_length[6]|max_length[16]' );
			$data['user_info'] = $user_info;
			if ($this->form_validation->run () == FALSE) {
				$this->output ( "home_layout", array(
					'body' => 'member/password' 
				), $data );
			} else {
				if ($this->old_password ()) {
					$this->user_mdl->update_user_password ( $user_info['new_pass'] );
					$this->show_message ( "密码更新成功", 'member/index' );
				} else {
					$this->show_message ( "原始密码错误", 'member/index' );
				}
			}
		} else {
			$this->output ( "home_layout", array(
				'body' => 'member/password' 
			), $data );
		}
	}

	function username_check() {
		$username = $this->input->post ( 'username' );
		$result = $this->user_mdl->get_otheruser_by_username ( $username );
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	function old_password() {
		$old_pass = $this->input->post ( 'old_pass' );
		$result = $this->user_mdl->get_user_info ();
		$password = $result->password;
		if (sha1 ( $old_pass ) != $password) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 找回密码
	 */
	public function lost_password() {
		$this->data['page_js'] = array(
			base_url ( 'templates/' . $this->data['config_custom']['site_theme'] . '/assets/js/jquery.validate.min.js' ),
			base_url ( 'templates/' . $this->data['config_custom']['site_theme'] . '/assets/js/bit.validate.js' ) 
		);
		if ($this->input->post ()) {
			$captcha = $this->input->post ( 'captcha', true );
			$to_email = $this->input->post ( 'email', true );
			$data['captcha'] = $captcha;
			$data['to_email'] = $to_email;
			if (! empty ( $captcha )) {
				if ($this->session->userdata ( 'verify' ) == $captcha) {
					$this->session->set_userdata ( 'verify', '' );
					$this->load->library ( 'mailer' );
					if ($this->user_mdl->check_email_exists ( $to_email )) {
						$password_key = random_string ( 'unique' );
						$password_expire = time () + 172800;
						if ($this->user_mdl->update_password_key ( $to_email, $password_key, $password_expire )) {
							$user = $this->user_mdl->get_full_user_by_email ( $to_email );
							$data['username'] = $user->username;
							$data['set_password_url'] = site_url ( 'member/set_password/' . $password_key );
							$this->config->load ( 'custom', TRUE );
							$site_basic = $this->config->item ( 'site_basic', 'custom' );
							$data['site_name'] = $site_basic['site_name'];
							$data['site_domain'] = $site_basic['site_domain'];
							$data['send_time'] = date ( 'Y年m月d日 H:i:s', time () );
							$data['send_date'] = date ( 'Y年m月d日', time () );
							$this->load->helper ( 'email' );
							// $send_email = MY_Send_Email($to_email, 'mail_lost_password', $data);
							$send_email = $this->mailer->sendmail ( $to_email, '(' . $to_email . ')', '', $data, 'mail_lost_password' );
							if ($send_email == 'success') {
								$this->output ( "admin_layout", array(
									'body' => 'member/lost_password_ok' 
								), array(
									'to_email' => $to_email 
								) );
								return;
							} else {
								$this->show_message ( "发送失败，请联系系统管理员。<br/>" . $send_email );
							}
						}
					} else {
						/*
						 * header('content-type:text/html;charset=utf-8');
						 * show_error('邮箱不存在');
						 * return;
						 */
						$this->show_message ( "邮箱不存在。" );
					}
				} else {
					$data['error'] = "验证码错误!";
					$this->output ( "admin_layout", array(
						'body' => 'member/lost_password' 
					), $data );
				}
			} else {
				$data['error'] = "验证码不能为空!";
				$this->output ( "admin_layout", array(
					'body' => 'member/lost_password' 
				), $data );
			}
		} else {
			$this->output ( "admin_layout", array(
				'body' => 'member/lost_password' 
			) );
		}
	}

	/**
	 * 找回密码
	 */
	public function set_password() {
		$this->data['page_js'] = array(
			base_url ( 'templates/' . $this->data['config_custom']['site_theme'] . '/assets/js/jquery.validate.min.js' ),
			base_url ( 'templates/' . $this->data['config_custom']['site_theme'] . '/assets/js/bit.validate.js' ) 
		);
		// 判断是否存在password key
		$password_key = $this->uri->segment ( 3 );
		$data['password_key'] = $password_key;
		if (! $password_key) {
			redirect ( 'welcome/index' );
		}
		// 判断password key是否过期
		$user = $this->user_mdl->get_user_by_password_key ( $password_key );
		if ($user->lost_password_expire > time ()) {
			$data['is_expire'] = false;
		} else {
			$data['is_expire'] = true;
		}
		if ($this->input->post ()) {
			// 如果条件具备，执行更新密码操作
			if ($this->input->post ( 'password' ) && ! $data['is_expire']) {
				$new_password = $this->input->post ( 'password' );
				$this->user_mdl->set_user_password ( $user->uid, $new_password );
				// 将password key清空
				$this->user_mdl->update_password_key ( $user->email, '', '' );
				$this->output ( "admin_layout", array(
					'body' => 'member/set_password_ok' 
				) );
				return;
			}
		}
		$this->output ( "admin_layout", array(
			'body' => 'member/set_password' 
		), $data );
	}

}